<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\Controller;
use App\Http\Requests\Advertisements\FilterAdvertisementsRequest;
use App\Http\Requests\Advertisements\StoreAdvertisementsRequest;
use App\Http\Requests\Advertisements\UpdateAdvertisementsAdminRequest;
use App\Http\Requests\Advertisements\UpdateAdvertisementsSellerRequest;
use App\Services\Features\AdvertisementService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class AdvertisementController extends Controller
{
    public function __construct(private readonly AdvertisementService $adService){}

    public function index(FilterAdvertisementsRequest $request):JsonResponse
    {
        $response = $this->adService->filterAds($request);
        return $this->sendSuccess($response['ads'], $response['message']);
    }

    public function store(StoreAdvertisementsRequest $request):JsonResponse{
        $response = $this->adService->createAd($request->validated());
        return $this->sendSuccess($response['advertisement'], $response['message']);
    }

    public function showAdsById($id):JsonResponse{
        $response = $this->adService->getAdsById($id);
        return $this->sendSuccess($response['advertisement'], $response['message']);
    }

    public function showAdsForSeller():JsonResponse{
        $response = $this->adService->getAdsForSeller();
        return $this->sendSuccess($response['advertisement'], $response['message']);
    }

    /**
     * @throws AuthorizationException
     */
    public function updateBySeller(UpdateAdvertisementsSellerRequest $request, $id):JsonResponse
    {
        $response = $this->adService->updateAdBySeller($request, $id);
        return $this->sendSuccess($response['advertisement'], $response['message']);
    }

    /**
     * @throws AuthorizationException
     */
    public function updateByAdmin(UpdateAdvertisementsAdminRequest $request, $id):JsonResponse
    {
        $response = $this->adService->updateAdByAdmin($request, $id);
        return $this->sendSuccess($response['advertisement'], $response['message']);
    }

    //only admin can delete ads

    /**
     * @throws AuthorizationException
     */
    public function destroy($id):JsonResponse
    {
        $this->adService->deleteAd($id);
        return $this->sendSuccess([],'Advertisement deleted successfully.');
    }
}
