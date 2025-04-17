<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\StoreSettingRequest;
use App\Http\Requests\Settings\UpdateSettingRequest;
use App\Services\Features\SettingService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    public function __construct(private readonly SettingService $settingService){}

    /**
     * @return JsonResponse
     */
    public function index():JsonResponse
    {
        $response = $this->settingService->getSettings();
        return $this->sendSuccess($response['setting'], $response['message']);
    }

    /**
     * @param StoreSettingRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreSettingRequest $request):JsonResponse
    {
        $response = $this->settingService->createSetting($request);
        return $this->sendSuccess($response['setting'], $response['message']);
    }

    /**
     * @param UpdateSettingRequest $request
     * @param $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateSettingRequest $request, $id):JsonResponse
    {
        $response = $this->settingService->updateSetting($request,$id);
        return $this->sendSuccess($response['setting'], $response['message']);
    }
}
