<?php

namespace App\Interfaces;
use Illuminate\Database\Eloquent\Builder;

interface AdsServicesInterface
{
    public function modelQuery():Builder;
    public function filterAds($request):array;
    public function createAd($request):array;
    public function getAdsById($id):array;

    public function getAdsForSeller():array;
    public function updateAd($request, $id):array;
    public function deleteAd($id):array;
}
