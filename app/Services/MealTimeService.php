<?php

namespace App\Services;

use App\Models\Cyber\MealSlot;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class MealTimeService
{
    /**
     * Get all active meal slots with their current status.
     *
     * @return Collection<int, MealSlot>
     */
    public function getAllSlots(): Collection
    {
        return MealSlot::active()->ordered()->get();
    }

    /**
     * Get currently open slots for ordering.
     *
     * @return Collection<int, MealSlot>
     */
    public function getCurrentOpenSlots(): Collection
    {
        return $this->getAllSlots()->filter(fn (MealSlot $slot) => $slot->isOpen());
    }

    /**
     * Check if a specific slot is open.
     */
    public function isSlotOpen(int $slotId): bool
    {
        $slot = MealSlot::find($slotId);

        if (! $slot) {
            return false;
        }

        return $slot->isOpen();
    }

    /**
     * Get a slot by ID.
     */
    public function getSlot(int $slotId): ?MealSlot
    {
        return MealSlot::find($slotId);
    }

    /**
     * Get a slot by name.
     */
    public function getSlotByName(string $name): ?MealSlot
    {
        return MealSlot::where('name', strtolower($name))->first();
    }

    /**
     * Get the next delivery time for a slot.
     */
    public function getNextDeliveryTime(int $slotId): ?string
    {
        $slot = MealSlot::find($slotId);

        if (! $slot) {
            return null;
        }

        return $slot->delivery_time_label;
    }

    /**
     * Get the cutoff time for a slot.
     */
    public function getOrderCutoffTime(int $slotId): ?string
    {
        $slot = MealSlot::find($slotId);

        if (! $slot) {
            return null;
        }

        return Carbon::parse($slot->order_end_time)->format('h:i A');
    }

    /**
     * Get time remaining until slot closes.
     */
    public function getTimeRemaining(int $slotId): ?string
    {
        $slot = MealSlot::find($slotId);

        if (! $slot) {
            return null;
        }

        return $slot->getTimeRemaining();
    }

    /**
     * Get slots with their status for display.
     *
     * @return array<int, array{slot: MealSlot, is_open: bool, time_remaining: string|null, cutoff_time: string}>
     */
    public function getSlotsWithStatus(): array
    {
        return $this->getAllSlots()->map(function (MealSlot $slot) {
            return [
                'slot' => $slot,
                'is_open' => $slot->isOpen(),
                'time_remaining' => $slot->getTimeRemaining(),
                'cutoff_time' => Carbon::parse($slot->order_end_time)->format('h:i A'),
            ];
        })->toArray();
    }

    /**
     * Update meal slot times (admin function).
     */
    public function updateSlotTimes(int $slotId, string $startTime, string $endTime): bool
    {
        $slot = MealSlot::find($slotId);

        if (! $slot) {
            return false;
        }

        $slot->update([
            'order_start_time' => $startTime,
            'order_end_time' => $endTime,
        ]);

        return true;
    }

    /**
     * Toggle slot active status (admin function).
     */
    public function toggleSlotActive(int $slotId): bool
    {
        $slot = MealSlot::find($slotId);

        if (! $slot) {
            return false;
        }

        $slot->update(['is_active' => ! $slot->is_active]);

        return true;
    }

    /**
     * Get menu items available for current open slots.
     */
    public function getAvailableMenuItems(): Collection
    {
        $openSlots = $this->getCurrentOpenSlots();

        if ($openSlots->isEmpty()) {
            return collect();
        }

        return \App\Models\Cyber\MenuItem::available()
            ->where(function ($query) use ($openSlots) {
                $query->where('available_all_slots', true)
                    ->orWhereIn('meal_slot_id', $openSlots->pluck('id'));
            })
            ->ordered()
            ->get();
    }

    /**
     * Get the current active slot (if any).
     */
    public function getCurrentSlot(): ?MealSlot
    {
        return $this->getCurrentOpenSlots()->first();
    }

    /**
     * Check if any slot is currently open.
     */
    public function isAnySlotOpen(): bool
    {
        return $this->getCurrentOpenSlots()->isNotEmpty();
    }
}
