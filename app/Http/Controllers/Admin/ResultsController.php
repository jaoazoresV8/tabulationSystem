<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultsController extends Controller
{
    public function index()
    {
        return view('admin.results', [
            'currentContest' => null,
            'contests' => Contest::withCount('contestants')->latest()->get(),
        ]);
    }

    public function show(Contest $contest)
    {
        $currentContest = $contest;
        $exposures = $currentContest->exposures;
        $contestants = $this->calculateResults($contest);

        return view('admin.results', compact('currentContest', 'exposures', 'contestants'));
    }

    public function print(Contest $contest)
    {
        $currentContest = $contest;
        $exposures = $currentContest->exposures;
        $contestants = $this->calculateResults($contest);

        return view('admin.reports.results-print', compact('currentContest', 'exposures', 'contestants'));
    }

    public function scoresheet(Contest $contest)
    {
        $currentContest = $contest;
        $exposures = $contest->exposures;
        $contestants = $contest->contestants;
        $judges = $contest->judges;

        // Fetch detailed scores for the scoresheet
        $detailedScores = DB::table('scores')
            ->join('criteria', 'scores.criterion_id', '=', 'criteria.id')
            ->where('scores.exposure_id', '>', 0) // placeholder for all
            ->select('scores.*', 'criteria.name as criterion_name', 'criteria.percentage')
            ->get()
            ->groupBy(['contestant_id', 'exposure_id', 'judge_id']);

        return view('admin.reports.scoresheet', compact('currentContest', 'exposures', 'contestants', 'judges', 'detailedScores'));
    }

    public function rankings(Contest $contest)
    {
        $currentContest = $contest;
        $contestants = $this->calculateResults($contest);
        return view('admin.reports.rankings', compact('currentContest', 'contestants'));
    }

    public function export(Contest $contest)
    {
        $contestants = $this->calculateResults($contest);
        $exposures = $contest->exposures;

        $filename = "tabulation_export_" . str_replace(' ', '_', strtolower($contest->name)) . ".csv";
        $handle = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Header row
        $header = ['Rank', 'Number', 'Name'];
        foreach ($exposures as $exp) {
            $header[] = $exp->name;
        }
        $header[] = 'Grand Total';
        fputcsv($handle, $header);

        foreach ($contestants as $index => $c) {
            $row = [($index + 1), $c->number, $c->name];
            foreach ($exposures as $exp) {
                $row[] = number_format($c->segment_scores[$exp->id] ?? 0, 2);
            }
            $row[] = number_format($c->grand_total, 2) . '%';
            fputcsv($handle, $row);
        }

        fclose($handle);
        exit;
    }

    private function calculateResults(Contest $contest)
    {
        $exposures = $contest->exposures;
        $contestants = $contest->contestants;

        foreach ($contestants as $contestant) {
            $segmentScores = [];
            $grandTotal = 0;
            $totalWeight = 0;

            foreach ($exposures as $exposure) {
                $judgeCount = DB::table('scores')
                    ->where('exposure_id', $exposure->id)
                    ->distinct('judge_id')
                    ->count();

                if ($judgeCount > 0) {
                    $scores = DB::table('scores')
                        ->join('criteria', 'scores.criterion_id', '=', 'criteria.id')
                        ->where('scores.contestant_id', $contestant->id)
                        ->where('scores.exposure_id', $exposure->id)
                        ->select(
                            DB::raw("SUM(scores.score * (criteria.percentage / 100)) / $judgeCount as segment_score")
                        )
                        ->first();

                    $score = $scores->segment_score ?? 0;
                } else {
                    $score = 0;
                }

                $segmentScores[$exposure->id] = $score;
                $grandTotal += $score * ($exposure->weight / 100);
                $totalWeight += $exposure->weight;
            }

            $contestant->segment_scores = $segmentScores;
            $contestant->grand_total = $totalWeight > 0 ? ($grandTotal / ($totalWeight / 100)) : 0;
        }

        return $contestants->sortByDesc('grand_total');
    }
}
