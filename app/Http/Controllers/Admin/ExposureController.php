<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\Exposure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExposureController extends Controller
{
    public function index()
    {
        return view('admin.exposures', [
            'currentContest' => null,
            'exposures' => collect(),
            'contests' => Contest::withCount('exposures')->latest()->get(),
        ]);
    }

    public function show(Contest $contest)
    {
        return view('admin.exposures', [
            'currentContest' => $contest,
            'exposures' => $contest->exposures,
            'contests' => Contest::withCount('exposures')->latest()->get(),
        ]);
    }

    public function store(Request $request, Contest $contest)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:preliminary,final'],
            'weight' => ['required', 'numeric', 'min:0', 'max:100'],
            'top_n' => ['nullable', 'integer', 'min:1'],
            'carry_over' => ['nullable', 'boolean'],
            'insert_after' => ['nullable', 'integer'],
        ]);

        $after = isset($data['insert_after']) ? $contest->exposures()->find($data['insert_after']) : null;
        if (isset($data['insert_after']) && ! $after) abort(422, 'The selected connection must belong to this contest.');
        $order = $after ? $after->order + 1 : ((int) $contest->exposures()->max('order') + 1);

        DB::transaction(function () use ($contest, $data, $order, $after) {
            $contest->exposures()->where('order', '>=', $order)->increment('order');
            $contest->exposures()->create([
                'name' => $data['name'], 'type' => $data['type'], 'weight' => $data['weight'],
                'top_n' => $data['top_n'] ?? null, 'carry_over' => (bool) ($data['carry_over'] ?? false),
                'order' => $order, 'status' => 'locked', 'previous_exposure_id' => $after?->id,
            ]);
        });

        return redirect()->route('contests.exposures', $contest)->with('success', 'Exposure node added to this contest.');
    }

    public function reconnect(Request $request, Contest $contest, Exposure $exposure)
    {
        abort_unless($exposure->contest_id === $contest->id, 404);
        $data = $request->validate(['after_exposure_id' => ['nullable', 'integer']]);
        $afterId = $data['after_exposure_id'] ?? null;
        abort_if($afterId && (int) $afterId === $exposure->id, 422, 'An exposure cannot connect to itself.');

        DB::transaction(function () use ($contest, $exposure, $afterId) {
            $after = $afterId ? $contest->exposures()->find($afterId) : null;
            if ($afterId && ! $after) abort(422, 'The selected connection must belong to this contest.');

            // 1. Remove from current position and shift others
            $contest->exposures()->where('id', '!=', $exposure->id)->where('order', '>', $exposure->order)->decrement('order');

            // 2. Refresh target node order and calculate new position
            $after?->refresh();
            $newOrder = $after ? $after->order + 1 : 1;

            // 3. Make space at new position
            $contest->exposures()->where('id', '!=', $exposure->id)->where('order', '>=', $newOrder)->increment('order');

            // 4. Update the moved node's order
            $exposure->update(['order' => $newOrder]);

            // 5. RE-SYNC ALL CONNECTIONS to maintain linear flow integrity
            // This ensures that previous_exposure_id always matches the actual order in the list
            $exposures = $contest->exposures()->orderBy('order')->get();
            $prevId = null;
            foreach ($exposures as $exp) {
                $exp->update(['previous_exposure_id' => $prevId]);
                $prevId = $exp->id;
            }
        });

        return redirect()->route('contests.exposures', $contest)->with('success', 'Exposure node reconnected and flow re-synced.');
    }

    public function swap(Request $request, Contest $contest, Exposure $exposure)
    {
        abort_unless($exposure->contest_id === $contest->id, 404);
        $data = $request->validate(['with_exposure_id' => ['required', 'integer']]);
        $with = $contest->exposures()->findOrFail($data['with_exposure_id']);

        DB::transaction(function () use ($contest, $exposure, $with) {
            $oldOrder = $exposure->order;
            $exposure->update(['order' => $with->order]);
            $with->update(['order' => $oldOrder]);

            // Re-sync connections to match the new order
            $exposures = $contest->exposures()->orderBy('order')->get();
            $prevId = null;
            foreach ($exposures as $exp) {
                $exp->update(['previous_exposure_id' => $prevId]);
                $prevId = $exp->id;
            }
        });

        return redirect()->route('contests.exposures', $contest)->with('success', 'Exposure nodes swapped and connections updated.');
    }

    public function detach(Contest $contest, Exposure $exposure)
    {
        abort_unless($exposure->contest_id === $contest->id, 404);
        $exposure->update(['previous_exposure_id' => null]);
        return redirect()->route('contests.exposures', $contest)->with('success', 'Exposure node detached.');
    }

    public function reorder(Request $request, Contest $contest)
    {
        $request->validate([
            'orders' => ['required', 'array'],
            'orders.*' => ['integer', 'exists:exposures,id'],
        ]);

        DB::transaction(function () use ($contest, $request) {
            $prevId = null;
            foreach ($request->orders as $index => $id) {
                $exposure = $contest->exposures()->find($id);
                if ($exposure) {
                    $exposure->update([
                        'order' => $index + 1,
                        'previous_exposure_id' => $prevId,
                    ]);
                    $prevId = $exposure->id;
                }
            }
        });

        return response()->json(['success' => true]);
    }

    public function updateStatus(Request $request, Exposure $exposure)
    {
        $request->validate([
            'status' => 'required|in:locked,active,completed',
        ]);

        // If activating, deactivate others in the same contest
        if ($request->status === 'active') {
            Exposure::where('contest_id', $exposure->contest_id)
                ->where('id', '!=', $exposure->id)
                ->where('status', 'active')
                ->update(['status' => 'locked']);
        }

        $exposure->update(['status' => $request->status]);

        return back()->with('success', 'Exposure status updated.');
    }
}
