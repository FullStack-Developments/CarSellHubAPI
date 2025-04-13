<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ads\FilterAdsRequest;
use App\Http\Requests\Ads\StoreAdRequest;
use App\Services\Features\AdService;
use Illuminate\Http\JsonResponse;

class AdsController extends Controller
{
    public function __construct(private readonly AdService $adService){}

    public function index(FilterAdsRequest $request):JsonResponse
    {
        $response = $this->adService->filterAds($request);
        return $this->sendSuccess($response['ads'], $response['message']);
    }

    public function store(StoreAdRequest $request):JsonResponse{
        $response = $this->adService->createAd($request->validated());
        return $this->sendSuccess($response['advertisement'], $response['message']);
    }

    public function show($id):JsonResponse{
        $response = $this->adService->getAdsById($id);
        return $this->sendSuccess($response['advertisement'], $response['message']);
    }

    // only admin can update ads to accept ad or reject
    public function update($request, $id):void{}

    //only admin can delete ads
    public function destroy($id):void{}
}
