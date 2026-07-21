<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Judge;
use Illuminate\Http\Request;

use Illuminate\Support\Str;

class JudgeAuthController extends Controller
{
    public function showLogin($contest_uuid = null)
    {
        $targetContest = $contest_uuid ? \App\Models\Contest::where('uuid', $contest_uuid)->first() : null;
        return view('auth.judge-login', compact('targetContest'));
    }

    public function login(Request $request, $contest_uuid = null)
    {
        $request->validate([
            'access_code' => ['required', 'string'],
        ]);

        $query = Judge::where('access_code', strtoupper($request->access_code));

        if ($contest_uuid) {
            $contest = \App\Models\Contest::where('uuid', $contest_uuid)->firstOrFail();
            $query->where('contest_id', $contest->id);
        }

        $judge = $query->first();

        if ($judge) {
            session(['judge_id' => $judge->id]);
            $judge->update(['is_online' => true, 'last_activity' => now()]);

            return redirect()->route('judge.scoring', [
                'contest_uuid' => $judge->contest->uuid,
                'judge_slug' => Str::slug($judge->name)
            ]);
        }

        return back()->withErrors([
            'access_code' => 'Invalid Access Code for this contest.',
        ]);
    }

    public function logout(Request $request)
    {
        $judgeId = session('judge_id');
        if ($judgeId) {
            $judge = Judge::find($judgeId);
            if ($judge) {
                $judge->update(['is_online' => false]);
            }
        }

        $request->session()->forget('judge_id');

        return redirect()->route('judge.login');
    }
}
