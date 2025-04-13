<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ads\StoreAdRequest;
use App\Services\Features\AdService;
use Illuminate\Http\JsonResponse;

class AdsController extends Controller
{
    public function __construct(private readonly AdService $adService){}

    public function index():void{}

    public function store(StoreAdRequest $request):JsonResponse{
        $response = $this->adService->createAd($request->validated());
        return $this->sendSuccess($response['advertisement'], $response['message']);
    }

    public function showAd($id):void{}

    // only admin can update ads to accept ad or reject
    public function update($request, $id):void{}

    //only admin can delete ads
    public function destroy($id):void{}
}
