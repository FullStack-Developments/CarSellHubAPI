<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface CarServicesInterface
{
    public function modelQuery():Builder;
    public function filterCars($request):array ;
    public function storeCar($request): array;
    public function updateCarAndImages($request, $id): array ;
    public function deleteCarAndImages($id): void ;
    public function getCarById(int $id): array ;
    public function getCarBrands(): array ;
    public function getCarsBySellerName(string $sellerName): array;
    public function getCarsForSeller():array;
}

