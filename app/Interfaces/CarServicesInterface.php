<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface CarServicesInterface
{
    public function modelQuery():Builder;
    public function storeCar($request): array;
    public function filterCars($request):array ;
    public function updateCarAndImages($request, $id): array ;
    public function deleteCarAndImages($id): void ;
    public function getCarById(int $id): array ;
    public function getAllCarBrands(): array ;
}

