<?php

namespace App\Http\Controllers\Admin\Cyber;

use App\Http\Controllers\Controller;
use App\Models\Cyber\MealSlot;
use App\Models\Cyber\MenuItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        $menuItems = MenuItem::with('mealSlot')
            ->ordered()
            ->paginate(15);

        $mealSlots = MealSlot::ordered()->get();

        return view('admin.cyber.menu.index', compact('menuItems', 'mealSlots'));
    }

    public function create(): View
    {
        $mealSlots = MealSlot::ordered()->get();

        return view('admin.cyber.menu.create', compact('mealSlots'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'meal_slot_id' => 'nullable|exists:cyber_meal_slots,id',
            'available_all_slots' => 'boolean',
            'is_available' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['available_all_slots'] = $request->boolean('available_all_slots');
        $validated['is_available'] = $request->boolean('is_available', true);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('cyber-menu', 'public');
        }

        // If available_all_slots is true, meal_slot_id should be null
        if ($validated['available_all_slots']) {
            $validated['meal_slot_id'] = null;
        }

        MenuItem::create($validated);

        return redirect()
            ->route('admin.cyber.menu.index')
            ->with('success', 'Menu item created successfully.');
    }

    public function edit(MenuItem $menuItem): View
    {
        $mealSlots = MealSlot::ordered()->get();

        return view('admin.cyber.menu.edit', compact('menuItem', 'mealSlots'));
    }

    public function update(Request $request, MenuItem $menuItem): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'meal_slot_id' => 'nullable|exists:cyber_meal_slots,id',
            'available_all_slots' => 'boolean',
            'is_available' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['available_all_slots'] = $request->boolean('available_all_slots');
        $validated['is_available'] = $request->boolean('is_available');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('cyber-menu', 'public');
        }

        // If available_all_slots is true, meal_slot_id should be null
        if ($validated['available_all_slots']) {
            $validated['meal_slot_id'] = null;
        }

        $menuItem->update($validated);

        return redirect()
            ->route('admin.cyber.menu.index')
            ->with('success', 'Menu item updated successfully.');
    }

    public function destroy(MenuItem $menuItem): RedirectResponse
    {
        $menuItem->delete();

        return redirect()
            ->route('admin.cyber.menu.index')
            ->with('success', 'Menu item deleted successfully.');
    }
}
