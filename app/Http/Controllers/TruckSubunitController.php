<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTruckSubunitRequest;
use App\Services\TruckSubunitService;
use Exception;
use Illuminate\Http\JsonResponse;

class TruckSubunitController extends Controller
{
    public function __construct(
        private TruckSubunitService $truckSubunitService
    ){
    }

    /**
     * @param StoreTruckSubunitRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(StoreTruckSubunitRequest $request): JsonResponse
    {
        $truckSubunit = $this->truckSubunitService->createSubunit($request->validated());

        return response()->json($truckSubunit, 201);
    }
}
