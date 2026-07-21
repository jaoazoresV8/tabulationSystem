<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exposure;
use App\Models\Criterion;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CriteriaController extends Controller
{
    public function index(Request $request)
    {
        $exposure = Exposure::findOrFail($request->exposure_id);
        $criteria = $exposure->criteria;

        return view('admin.criteria', compact('exposure', 'criteria'));
    }

    public function store(Request $request, Exposure $exposure)
    {
        $data = $this->validatedData($request);
        $this->ensureTotalDoesNotExceedOneHundred($exposure, $data['percentage']);
        $exposure->criteria()->create($data);
        return redirect()->route('criteria', ['exposure_id' => $exposure->id])->with('success', 'Criterion added successfully.');
    }

    public function update(Request $request, Criterion $criterion)
    {
        $data = $this->validatedData($request);
        $this->ensureTotalDoesNotExceedOneHundred($criterion->exposure, $data['percentage'], $criterion->id);
        $criterion->update($data);
        return redirect()->route('criteria', ['exposure_id' => $criterion->exposure_id])->with('success', 'Criterion updated successfully.');
    }

    public function destroy(Criterion $criterion)
    {
        $exposureId = $criterion->exposure_id;
        $criterion->delete();
        return redirect()->route('criteria', ['exposure_id' => $exposureId])->with('success', 'Criterion removed successfully.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'percentage' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);
    }

    private function ensureTotalDoesNotExceedOneHundred(Exposure $exposure, float $percentage, ?int $ignoreId = null): void
    {
        $query = $exposure->criteria();
        if ($ignoreId) $query->where('id', '!=', $ignoreId);
        $total = (float) $query->sum('percentage') + $percentage;
        if ($total > 100) {
            throw ValidationException::withMessages(['percentage' => 'The total formula weight cannot exceed 100%.']);
        }
    }
}
