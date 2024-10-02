<?php

namespace App\Http\Controllers;

use App\Models\Truck;
use App\Http\Requests\StoreTruckRequest;
use App\Http\Requests\UpdateTruckRequest;
use App\Services\TruckService;
use Illuminate\Http\JsonResponse;

class TruckController extends Controller
{
    public function __construct(
        private TruckService $truckService
    ) {
    }

    public function index(): JsonResponse
    {
        $trucks = $this->truckService->getAllTrucks();
        return response()->json($trucks, 200);
    }

    public function store(StoreTruckRequest $request): JsonResponse
    {
        $truck = $this->truckService->createTruck($request->validated());
        return response()->json($truck, 201);
    }

    public function show(Truck $truck): JsonResponse
    {
        return response()->json($truck, 200);
    }

    public function update(UpdateTruckRequest $request, Truck $truck): JsonResponse
    {
        $truck = $this->truckService->updateTruck($truck, $request->validated());
        return response()->json($truck, 200);
    }

    public function destroy(Truck $truck): JsonResponse
    {
        $this->truckService->deleteTruck($truck);
        return response()->json(null, 204);
    }
}
