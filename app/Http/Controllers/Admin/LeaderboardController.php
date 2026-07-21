<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $currentContest = Contest::where('status', 'active')->first();
        if (!$currentContest) return redirect()->route('dashboard');

        $activeExposure = $currentContest->exposures()->where('status', 'active')->first()
                        ?? $currentContest->exposures()->first();

        // Calculate rankings for the active segment
        $rankings = DB::table('scores')
            ->join('criteria', 'scores.criterion_id', '=', 'criteria.id')
            ->join('contestants', 'scores.contestant_id', '=', 'contestants.id')
            ->select(
                'contestants.id',
                'contestants.name',
                'contestants.number',
                DB::raw('SUM(scores.score * (criteria.percentage / 100)) / (SELECT COUNT(DISTINCT judge_id) FROM scores s2 WHERE s2.exposure_id = scores.exposure_id) as final_score')
            )
            ->where('scores.exposure_id', $activeExposure->id)
            ->groupBy('contestants.id', 'contestants.name', 'contestants.number')
            ->orderByDesc('final_score')
            ->get();

        // Calculate judge completion
        $totalJudges = $currentContest->judges()->count();
        $judgesFinalized = DB::table('scores')
            ->where('exposure_id', $activeExposure->id)
            ->where('is_final', true)
            ->distinct('judge_id')
            ->count('judge_id');

        return view('admin.leaderboard', compact('currentContest', 'activeExposure', 'rankings', 'totalJudges', 'judgesFinalized'));
    }
}
