<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContestController extends Controller
{
    public function index()
    {
        $contests = Contest::latest()->get();
        return view('admin.contests', compact('contests'));
    }

    public function create()
    {
        return view('admin.create-contest');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:SINGLE,DOUBLE,GROUP,single,double,group',
            'description' => 'nullable|string',
            'judges_count' => 'nullable|integer|min:1|max:20',
            'male_count' => 'nullable|integer|min:0|max:100',
            'female_count' => 'nullable|integer|min:0|max:100',
        ]);

        $contest = Contest::create([
            'name' => $validated['name'],
            'type' => strtolower($validated['type']),
            'description' => $validated['description'] ?? null,
            'status' => 'pending',
        ]);

        $judgesCount = $validated['judges_count'] ?? 5;
        for ($i = 1; $i <= $judgesCount; $i++) {
            $contest->judges()->create([
                'name' => 'Judge ' . str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                'access_code' => strtoupper(Str::random(6)),
            ]);
        }

        return redirect()->route('contests.settings', $contest)->with('success', 'Contest initialized successfully.');
    }

    public function settings(Contest $contest)
    {
        return view('admin.contest-settings', compact('contest'));
    }

    public function update(Request $request, Contest $contest)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:single,double,group,SINGLE,DOUBLE,GROUP',
            'description' => 'nullable|string',
            'tabulator_name' => 'nullable|string|max:255',
            'status' => 'nullable|in:pending,active,completed',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if (($validated['status'] ?? null) === 'active') {
            Contest::where('id', '!=', $contest->id)
                ->where('status', 'active')
                ->update(['status' => 'completed']);
        }

        $logoPath = $contest->logo;
        if ($request->hasFile('logo')) {
            if ($contest->logo) {
                \Storage::disk('public')->delete($contest->logo);
            }
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        $contest->update([
            'name' => $validated['name'],
            'type' => strtolower($validated['type']),
            'description' => $validated['description'] ?? null,
            'tabulator_name' => $validated['tabulator_name'] ?? null,
            'status' => $validated['status'] ?? $contest->status,
            'logo' => $logoPath,
        ]);

        return back()->with('success', 'Contest settings saved.');
    }
}
