<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTruckSubunitRequest;
use App\Models\TruckSubunit;
use App\Services\TruckSubunitService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class TruckSubunitController extends Controller
{
    public function __construct(
        private TruckSubunitService $truckSubunitService
    ){
    }

    public function store(StoreTruckSubunitRequest $request): JsonResponse
    {
        try {
            $subunit = $this->truckSubunitService->createSubunit($request->validated());

            return response()->json($subunit, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
