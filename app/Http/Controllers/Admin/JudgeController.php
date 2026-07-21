<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\Judge;
use Illuminate\Http\Request;

use Illuminate\Support\Str;

class JudgeController extends Controller
{
    public function index()
    {
        return view('admin.judges', [
            'currentContest' => null,
            'judges' => collect(),
            'contests' => Contest::withCount('judges')->latest()->get(),
        ]);
    }

    public function show(Contest $contest)
    {
        return view('admin.judges', [
            'currentContest' => $contest,
            'judges' => $contest->judges,
            'contests' => Contest::withCount('judges')->latest()->get(),
        ]);
    }

    public function store(Request $request, Contest $contest)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Judge::create([
            'contest_id' => $contest->id,
            'name' => $request->name,
            'access_code' => strtoupper(Str::random(6)),
            'is_online' => false,
        ]);

        return back()->with('success', 'Judge added successfully.');
    }

    public function update(Request $request, Judge $judge)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $judge->update(['name' => $request->name]);

        return back()->with('success', 'Judge updated successfully.');
    }

    public function destroy(Judge $judge)
    {
        $judge->delete();
        return back()->with('success', 'Judge removed.');
    }

    public function regenerate(Judge $judge)
    {
        $judge->update([
            'access_code' => strtoupper(Str::random(6))
        ]);

        return back()->with('success', 'Access code regenerated.');
    }

    public function print(Contest $contest)
    {
        $judges = $contest->judges;
        $currentContest = $contest;

        return view('admin.print-judges', compact('currentContest', 'judges'));
    }
}
