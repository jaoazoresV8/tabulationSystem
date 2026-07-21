<?php

namespace App\Http\Controllers\Judge;

use App\Http\Controllers\Controller;
use App\Models\Contestant;
use App\Models\Criterion;
use App\Models\Exposure;
use App\Models\Judge;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class ScoringController extends Controller
{
    public function redirect()
    {
        $judge = Judge::findOrFail(session('judge_id'));
        return redirect()->route('judge.scoring', [
            'contest_uuid' => $judge->contest->uuid,
            'judge_slug' => Str::slug($judge->name)
        ]);
    }

    public function index(Request $request, $contest_uuid, $judge_slug)
    {
        $judge = Judge::findOrFail(session('judge_id'));
        $contest = $judge->contest;

        // Security check: Ensure the URL matches the session judge/contest
        if ($contest->uuid !== $contest_uuid || Str::slug($judge->name) !== $judge_slug) {
            return redirect()->route('judge.scoring', [
                'contest_uuid' => $contest->uuid,
                'judge_slug' => Str::slug($judge->name),
                'target' => $request->query('target')
            ]);
        }

        $activeExposure = $contest->exposures()->where('status', 'active')->first();

        if (!$activeExposure) {
            return view('judge.waiting', ['message' => 'Waiting for the next segment to start...']);
        }

        $contestants = $contest->contestants()->where('is_active', true)->orderBy('number')->get();

        // Use selected contestant or default to first
        $selectedId = $request->query('target');
        $contestant = $selectedId
            ? $contestants->firstWhere('id', $selectedId)
            : $contestants->first();

        if (!$contestant) {
            return view('judge.waiting', ['message' => 'No active contestants found.']);
        }

        $criteria = $activeExposure->criteria;

        // Get all scores for this judge and exposure to show progress in the list
        $allScoredIds = Score::where('judge_id', $judge->id)
            ->where('exposure_id', $activeExposure->id)
            ->where('is_final', true)
            ->distinct()
            ->pluck('contestant_id')
            ->toArray();

        // Get existing scores for current selection
        $existingScores = Score::where('judge_id', $judge->id)
            ->where('contestant_id', $contestant->id)
            ->where('exposure_id', $activeExposure->id)
            ->get()
            ->keyBy('criterion_id');

        $isLocked = $existingScores->where('is_final', true)->isNotEmpty();

        return view('judge.scoring', compact(
            'judge', 'contest', 'activeExposure',
            'contestant', 'contestants', 'criteria',
            'existingScores', 'allScoredIds', 'isLocked'
        ));
    }

    public function entry($code)
    {
        $judge = Judge::where('access_code', strtoupper($code))->first();

        if ($judge) {
            session(['judge_id' => $judge->id]);
            $judge->update(['is_online' => true, 'last_activity' => now()]);

            return redirect()->route('judge.scoring', [
                'contest_uuid' => $judge->contest->uuid,
                'judge_slug' => Str::slug($judge->name)
            ]);
        }

        return redirect()->route('judge.login')->withErrors(['access_code' => 'Invalid Access Code.']);
    }

    public function submit(Request $request)
    {
        $judge = Judge::findOrFail(session('judge_id'));
        $request->validate([
            'contestant_id' => 'required|exists:contestants,id',
            'exposure_id' => 'required|exists:exposures,id',
            'scores' => 'required|array',
            'scores.*' => 'required|numeric|min:0|max:100',
            'comments' => 'nullable|array',
        ]);

        // Check if already locked
        $alreadyFinal = Score::where([
            'judge_id' => $judge->id,
            'contestant_id' => $request->contestant_id,
            'exposure_id' => $request->exposure_id,
            'is_final' => true
        ])->exists();

        if ($alreadyFinal) {
            return back()->withErrors(['lock' => 'This ballot is already locked.']);
        }

        $isFinal = $request->has('finalize');
        $ballotHash = $isFinal ? strtoupper(substr(md5(now() . $judge->id . $request->contestant_id), 0, 8)) : null;

        foreach ($request->scores as $criterionId => $value) {
            $existing = Score::where([
                'judge_id' => $judge->id,
                'contestant_id' => $request->contestant_id,
                'criterion_id' => $criterionId,
                'exposure_id' => $request->exposure_id,
            ])->first();

            $changeCount = $existing ? ($existing->change_count + 1) : 0;

            Score::updateOrCreate(
                [
                    'judge_id' => $judge->id,
                    'contestant_id' => $request->contestant_id,
                    'criterion_id' => $criterionId,
                    'exposure_id' => $request->exposure_id,
                ],
                [
                    'score' => $value,
                    'comment' => $request->comments[$criterionId] ?? null,
                    'is_final' => $isFinal,
                    'ballot_hash' => $ballotHash,
                    'change_count' => $changeCount
                ]
            );
        }

        $judge->update(['last_activity' => now()]);

        return back()->with('success', $isFinal ? 'Ballot finalized and locked.' : 'Scores saved.');
    }
}
