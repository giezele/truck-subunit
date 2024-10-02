<?php

namespace App\Services;

use App\Models\Truck;
use Illuminate\Database\Eloquent\Collection;

class TruckService
{
    /**
     * Get all trucks.
     *
     * @return Collection
     */
    public function getAllTrucks(): Collection
    {
        return Truck::all();
    }

    /**
     * Create a new truck.
     *
     * @param array $data
     * @return Truck
     */
    public function createTruck(array $data): Truck
    {
        return Truck::create($data);
    }

    /**
     * Update an existing truck.
     *
     * @param Truck $truck
     * @param array $data
     * @return Truck
     */
    public function updateTruck(Truck $truck, array $data): Truck
    {
        $truck->update($data);
        return $truck;
    }

    /**
     * Delete a truck.
     *
     * @param Truck $truck
     * @return void
     */
    public function deleteTruck(Truck $truck): void
    {
        $truck->delete();
    }
}
