<?php

namespace App\Interfaces;
use Illuminate\Database\Eloquent\Builder;

interface AdvertisementServiceInterface
{
    public function modelQuery():Builder;
    public function filterAds($request):array;
    public function filterAllAdsForAdmin($request):array;
    public function createAd($request):array;
    public function getAdvertisementById($id):array;
    public function getAdsForSeller():array;
    public function updateAdBySeller($request, $id):array;
    public function updateAdByAdmin($request, $id):array;
    public function increaseHitsByClickedOnAd($id):array;
    public function deleteAd($id):void;
}
