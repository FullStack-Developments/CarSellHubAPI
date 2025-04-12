<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface CarServicesInterface
{
    public function modelQuery():Builder;
    public function storeCar($request): array;
    public function filterCars($request):array ;
    public function updateCarAndImages($request, $id): array ;
}

