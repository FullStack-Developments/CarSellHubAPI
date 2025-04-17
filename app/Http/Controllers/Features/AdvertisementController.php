<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\Controller;
use App\Http\Requests\Advertisements\FilterAdvertisementsAdminRequest;
use App\Http\Requests\Advertisements\FilterAdvertisementsClientsRequest;
use App\Http\Requests\Advertisements\StoreAdvertisementsRequest;
use App\Http\Requests\Advertisements\UpdateAdvertisementsAdminRequest;
use App\Http\Requests\Advertisements\UpdateAdvertisementsSellerRequest;
use App\Services\Features\AdvertisementService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class AdvertisementController extends Controller
{
    public function __construct(private readonly AdvertisementService $adService){}

    public function showAllAdsForClients(FilterAdvertisementsClientsRequest $request):JsonResponse
    {
        $response = $this->adService->filterAds($request);
        return $this->sendSuccess($response['ads'], $response['message']);
    }
    public function showAllAdsForAdmin(FilterAdvertisementsAdminRequest $request):JsonResponse
    {
        $response = $this->adService->filterAllAdsForAdmin($request);
        return $this->sendSuccess($response['ads'], $response['message']);
    }
    public function showAdsForSeller():JsonResponse{
        $response = $this->adService->getAdsForSeller();
        return $this->sendSuccess($response['advertisement'], $response['message']);
    }
    public function store(StoreAdvertisementsRequest $request):JsonResponse{
        $response = $this->adService->createAd($request->validated());
        return $this->sendSuccess($response['advertisement'], $response['message']);
    }
    public function showAdById($id):JsonResponse{
        $response = $this->adService->getAdvertisementById($id);
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

    public function increaseHitAdvertisement($id):JsonResponse{
        $response = $this->adService->increaseHitsByClickedOnAd($id);
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
