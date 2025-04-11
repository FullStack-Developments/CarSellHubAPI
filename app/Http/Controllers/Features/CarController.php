<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCarRequest;
use App\Services\CarServices;
use Illuminate\Http\JsonResponse;

class CarController extends Controller
{
    public function __construct(private readonly CarServices $carServices){}

    public function store(StoreCarRequest $request): JsonResponse
    {
        $response = $this->carServices->uploadCar($request);
        return $this->sendSuccess($response['car'], $response['message']);
    }
}
