<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ContestantController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.contestants', [
            'currentContest' => null,
            'contestants' => collect(),
            'contests' => Contest::withCount('contestants')->latest()->get(),
        ]);
    }

    public function show(Request $request, Contest $contest)
    {
        $query = $contest->contestants();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('number', 'like', "%{$search}%"));
        }
        if ($request->filled('gender')) $query->where('gender', $request->gender);
        return view('admin.contestants', [
            'currentContest' => $contest,
            'contestants' => $query->get(),
            'contests' => Contest::withCount('contestants')->latest()->get(),
        ]);
    }

    public function store(Request $request, Contest $contest)
    {
        $data = $request->validate($this->rules($contest));
        $contest->contestants()->create([
            'number' => strtoupper($data['number']),
            'name' => $data['name'],
            'performance_url' => $data['performance_url'] ?? null,
            'team' => $data['team'] ?? null,
            'gender' => $data['gender'] ?? null,
            'photo' => $request->hasFile('photo') ? $request->file('photo')->store('contestants', 'public') : null,
            'is_active' => true,
        ]);
        return redirect()->route('contests.contestants', $contest)->with('success', 'Contestant added successfully.');
    }

    public function updatePerformance(Request $request, \App\Models\Contestant $contestant)
    {
        $request->validate([
            'performance_url' => 'required|string|max:255',
        ]);

        $contestant->update([
            'performance_url' => $request->performance_url
        ]);

        return back()->with('success', 'Performance feed updated for ' . $contestant->name);
    }

    public function import(Request $request, Contest $contest)
    {
        $request->validate(['csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:2048']]);
        $handle = fopen($request->file('csv_file')->getRealPath(), 'r');
        $header = fgetcsv($handle);
        $header = $header ? array_map(fn ($value) => strtolower(trim(preg_replace('/^\xEF\xBB\xBF/', '', $value))), $header) : [];
        $columns = ['number', 'name', 'gender', 'team'];
        if ($header !== $columns) return back()->withErrors(['csv_file' => 'CSV headings must be exactly: number, name, gender, team.'])->withInput();

        $rows = []; $line = 1;
        while (($row = fgetcsv($handle)) !== false) {
            $line++;
            if (count($row) === 1 && trim((string) $row[0]) === '') continue;
            if (count($row) !== 4) return back()->withErrors(['csv_file' => "Row {$line} must have 4 columns."])->withInput();
            $rows[] = array_combine($columns, array_map('trim', $row));
        }
        fclose($handle);
        if (!$rows) return back()->withErrors(['csv_file' => 'The CSV does not contain any contestant rows.'])->withInput();

        $numbers = [];
        foreach ($rows as $index => $row) {
            $validator = Validator::make($row, ['number' => ['required', 'string', 'max:20'], 'name' => ['required', 'string', 'max:255'], 'gender' => ['nullable', Rule::in(['male', 'female', 'other'])], 'team' => ['nullable', 'string', 'max:255']]);
            if ($validator->fails()) return back()->withErrors(['csv_file' => 'Row ' . ($index + 2) . ': ' . $validator->errors()->first()])->withInput();
            $number = strtoupper($row['number']);
            if (isset($numbers[$number]) || $contest->contestants()->where('number', $number)->exists()) return back()->withErrors(['csv_file' => 'Row ' . ($index + 2) . ": contestant number {$number} is already in use."])->withInput();
            $numbers[$number] = true;
        }
        DB::transaction(function () use ($contest, $rows) {
            foreach ($rows as $row) $contest->contestants()->create(['number' => strtoupper($row['number']), 'name' => $row['name'], 'gender' => $row['gender'] ?: null, 'team' => $row['team'] ?: null, 'is_active' => true]);
        });
        return redirect()->route('contests.contestants', $contest)->with('success', count($rows) . ' contestants imported successfully.');
    }

    private function rules(Contest $contest): array
    {
        return [
            'number' => ['required', 'string', 'max:20', Rule::unique('contestants', 'number')->where('contest_id', $contest->id)],
            'name' => ['required', 'string', 'max:255'],
            'performance_url' => ['nullable', 'string', 'max:255'],
            'team' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'photo' => ['nullable', 'image', 'max:2048']
        ];
    }
}
