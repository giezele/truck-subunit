<?php

namespace App\Services;

use App\Models\TruckSubunit;
use Carbon\Carbon;
use Exception;

class TruckSubunitService
{
    /**
     * @param array $data
     * @return TruckSubunit
     * @throws Exception
     */
    public function createSubunit(array $data): TruckSubunit
    {
        $startDate = $data['start_date'];
        $endDate = $data['end_date'];

        // Ensure the truck is not assigning itself as a subunit
        if ($data['main_truck'] === $data['subunit']) {
            throw new Exception("A truck cannot be its own subunit.");
        }

        // Check for overlapping subunits for both the main truck and subunit truck
        if ($this->hasOverlappingSubunitDates($data['main_truck'], $startDate, $endDate)) {
            throw new Exception("The dates for the subunit overlap with an existing subunit for the main truck.");
        }

        if ($this->hasOverlappingSubunitDates($data['subunit'], $startDate, $endDate, true)) {
            throw new Exception("The subunit truck is already a subunit for another truck during these dates.");
        }

        return TruckSubunit::create($data);
    }

    /**
     * @param int $truckId
     * @param string $startDate
     * @param string $endDate
     * @param bool $isSubunit
     * @return bool
     */
    private function hasOverlappingSubunitDates(int $truckId, string $startDate, string $endDate, bool $isSubunit = false): bool
    {
        $query = TruckSubunit::where($isSubunit ? 'subunit' : 'main_truck', $truckId)
            ->where(function ($query) use ($startDate, $endDate) {
                $startDateTime = Carbon::parse($startDate)->startOfDay();
                $endDateTime = Carbon::parse($endDate)->endOfDay();

                $query->whereBetween('start_date', [$startDateTime, $endDateTime])
                    ->orWhereBetween('end_date', [$startDateTime, $endDateTime])
                    ->orWhere(function ($query) use ($startDateTime, $endDateTime) {
                        $query->where('start_date', '<=', $startDateTime)
                            ->where('end_date', '>=', $endDateTime);
                    });
            });

        return $query->exists();
    }
}

