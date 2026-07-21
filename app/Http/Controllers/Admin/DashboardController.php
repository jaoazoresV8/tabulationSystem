<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\Contestant;
use App\Models\Exposure;
use App\Models\Judge;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'ACTIVE CONTESTS' => Contest::where('status', 'active')->count(),
            'TOTAL CONTESTANTS' => Contestant::count(),
            'TOTAL JUDGES' => Judge::count(),
            'COMPLETED EXPOSURES' => Exposure::where('status', 'completed')->count(),
        ];

        $recentContests = Contest::latest()->take(5)->get();
        $currentContest = Contest::where('status', 'active')->first();
        $currentExposure = $currentContest ? $currentContest->exposures()->where('status', 'active')->first() : null;

        return view('welcome', compact('stats', 'recentContests', 'currentContest', 'currentExposure'));
    }
}
