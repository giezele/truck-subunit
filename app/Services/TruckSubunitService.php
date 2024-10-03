<?php
namespace App\Services;

use App\Models\TruckSubunit;
use Carbon\Carbon;
use Exception;
use Illuminate\Validation\ValidationException;

class TruckSubunitService
{
    /**
     * @param array $data
     * @return TruckSubunit
     * @throws Exception
     */
    public function createSubunit(array $data): TruckSubunit
    {
        $startDate = Carbon::parse($data['start_date'])->startOfDay();
        $endDate = Carbon::parse($data['end_date'])->endOfDay();

        // Ensure the truck is not assigning itself as a subunit
        if ($data['main_truck'] === $data['subunit']) {
            throw new Exception("A truck cannot be its own subunit.");
        }

        // Check for overlapping subunits for both the main truck and subunit truck
        $this->checkOverlappingDates($data['main_truck'], $startDate, $endDate, false);
        $this->checkOverlappingDates($data['subunit'], $startDate, $endDate, true);

        $this->checkIfTruckIsAlreadySubunit($data['main_truck'], $startDate, $endDate);

        return TruckSubunit::create($data);
    }

    /**
     * @param int $truckId
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param bool $isSubunit
     * @return void
     * @throws Exception
     */
    private function checkOverlappingDates(int $truckId, Carbon $startDate, Carbon $endDate, bool $isSubunit): void
    {
        $column = $isSubunit ? 'subunit' : 'main_truck';

        $conflictExists = TruckSubunit::where($column, $truckId)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->exists();

        if ($conflictExists) {
            $message = $isSubunit
                ? "The subunit truck is already a subunit for another truck during these dates."
                : "The dates for the subunit overlap with an existing subunit for the main truck.";

            throw ValidationException::withMessages([
                'subunit' => $message,
            ]);
        }
    }

    /**
     * @param int $truckId
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return void
     * @throws ValidationException
     */
    private function checkIfTruckIsAlreadySubunit(int $truckId, Carbon $startDate, Carbon $endDate): void
    {
        $conflictExists = TruckSubunit::where('subunit', $truckId)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->exists();

        if ($conflictExists) {
            throw ValidationException::withMessages([
                'main_truck' => "This truck is already a subunit for another truck during this period and cannot be a main truck.",
            ]);
        }
    }
}
