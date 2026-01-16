<?php

namespace App\Http\Controllers\Admin\Cyber;

use App\Http\Controllers\Controller;
use App\Models\Cyber\MealSlot;
use App\Services\MealTimeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MealSlotController extends Controller
{
    public function __construct(
        private MealTimeService $mealTimeService
    ) {}

    public function index(): View
    {
        $mealSlots = $this->mealTimeService->getSlotsWithStatus();

        return view('admin.cyber.meal-slots.index', compact('mealSlots'));
    }

    public function edit(MealSlot $mealSlot): View
    {
        return view('admin.cyber.meal-slots.edit', compact('mealSlot'));
    }

    public function update(Request $request, MealSlot $mealSlot): RedirectResponse
    {
        $validated = $request->validate([
            'display_name' => 'required|string|max:255',
            'order_start_time' => 'required|date_format:H:i',
            'order_end_time' => 'required|date_format:H:i',
            'delivery_time_label' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $mealSlot->update($validated);

        return redirect()
            ->route('admin.cyber.meal-slots.index')
            ->with('success', 'Meal slot updated successfully.');
    }

    public function toggle(MealSlot $mealSlot): RedirectResponse
    {
        $mealSlot->update(['is_active' => ! $mealSlot->is_active]);

        $status = $mealSlot->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->route('admin.cyber.meal-slots.index')
            ->with('success', "Meal slot {$status} successfully.");
    }
}
