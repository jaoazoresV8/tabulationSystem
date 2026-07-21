<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\Exposure;
use App\Models\Score;
use Illuminate\Http\Request;

class TabulationController extends Controller
{
    public function index()
    {
        return view('admin.tabulation', [
            'currentContest' => null,
            'contests' => Contest::latest()->get(),
        ]);
    }

    public function show(Request $request, Contest $contest)
    {
        $currentContest = $contest;
        $exposures = $currentContest->exposures;

        $activeExposure = $request->filled('exposure_id')
            ? $currentContest->exposures()->findOrFail($request->exposure_id)
            : $currentContest->exposures()->where('status', 'active')->first() ?? $exposures->first();

        $judges = $currentContest->judges;
        $contestants = $currentContest->contestants;
        $activeContestantId = $request->query('contestant_id');
        $activeContestant = $activeContestantId
            ? $contestants->find($activeContestantId)
            : ($contestants->firstWhere('is_active', true) ?? $contestants->first());

        if (!$activeExposure) {
            return view('admin.tabulation', compact(
                'currentContest', 'activeExposure', 'exposures', 'judges', 'contestants', 'activeContestant'
            ))->with('scores', collect())->with('judgeStatuses', collect());
        }

        $scores = Score::where('exposure_id', $activeExposure->id)
            ->get()
            ->groupBy(['contestant_id', 'judge_id']);

        $judgeStatuses = $judges->mapWithKeys(function ($judge) use ($activeContestant, $scores) {
            $hasScores = $activeContestant
                && isset($scores[$activeContestant->id][$judge->id])
                && $scores[$activeContestant->id][$judge->id]->first()?->is_final;

            return [$judge->id => $hasScores];
        });

        // Check if all contestants have been scored by all judges for this segment
        $totalRequired = $contestants->count() * $judges->count();
        $totalSubmitted = Score::where('exposure_id', $activeExposure->id)
            ->where('is_final', true)
            ->distinct('judge_id', 'contestant_id')
            ->count();
        $isSegmentComplete = $totalSubmitted >= $totalRequired && $totalRequired > 0;

        return view('admin.tabulation', compact(
            'currentContest', 'activeExposure', 'exposures', 'judges', 'contestants', 'scores', 'activeContestant', 'judgeStatuses', 'isSegmentComplete'
        ));
    }

    public function unlockBallot(Request $request, Contest $contest)
    {
        $request->validate([
            'judge_id' => 'required|exists:judges,id',
            'contestant_id' => 'required|exists:contestants,id',
            'exposure_id' => 'required|exists:exposures,id',
        ]);

        Score::where([
            'judge_id' => $request->judge_id,
            'contestant_id' => $request->contestant_id,
            'exposure_id' => $request->exposure_id,
        ])->update(['is_final' => false, 'ballot_hash' => null]);

        return back()->with('success', 'Ballot unlocked for modification.');
    }
}
