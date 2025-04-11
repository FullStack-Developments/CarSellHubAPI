<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cars\FilterCarsRequest;
use App\Http\Requests\Cars\StoreCarRequest;
use App\Services\Features\CarServices;
use Illuminate\Http\JsonResponse;

class CarController extends Controller
{
    public function __construct(private readonly CarServices $carServices){}
    public function index(FilterCarsRequest $request): JsonResponse{
        $response = $this->carServices->filterCars($request);
        return $this->sendSuccess([$response->paginate()],'Cars indexes Successfully');
    }
    public function store(StoreCarRequest $request): JsonResponse
    {
        $response = $this->carServices->storeCar($request);
        return $this->sendSuccess($response['car'], $response['message']);
    }
}
