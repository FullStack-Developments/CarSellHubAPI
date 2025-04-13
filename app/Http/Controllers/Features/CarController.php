<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cars\FilterCarsRequest;
use App\Http\Requests\Cars\StoreCarRequest;
use App\Http\Requests\Cars\UpdateCarRequest;
use App\Models\Car;
use App\Services\Features\CarService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function __construct(private readonly CarService $carServices){}
    public function index(FilterCarsRequest $request): JsonResponse
    {
        $response = $this->carServices->filterCars($request);
        return $this->sendSuccess($response['cars'], $response['message']);
    }
    public function store(StoreCarRequest $request): JsonResponse
    {
        $response = $this->carServices->storeCar($request);
        return $this->sendSuccess($response['car'], $response['message']);
    }
    public function show(int $id): JsonResponse{
        $response = $this->carServices->getCarById($id);
        return $this->sendSuccess($response['car'], $response['message']);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UpdateCarRequest $request, $id): JsonResponse
    {
        $response = $this->carServices->updateCarAndImages($request, $id);
        return $this->sendSuccess($response['car'], $response['message']);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy($id): JsonResponse
    {
        $this->carServices->deleteCarAndImages($id);
        return $this->sendSuccess([], 'Car deleted successfully');
    }
    public function getBrands() : JsonResponse{
        $response = $this->carServices->getCarBrands();
        return $this->sendSuccess($response['car_brands'], $response['message']);
    }
    public function getCarsBySellerName($sellerName) : JsonResponse{
        $response = $this->carServices->getCarsBySellerName($sellerName);
        return $this->sendSuccess($response['cars'], $response['message']);
    }
}
