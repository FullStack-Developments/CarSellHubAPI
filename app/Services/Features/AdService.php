<?php

namespace App\Services\Features;

use App\Http\Resources\AdsResource;
use App\Interfaces\AdsServicesInterface;
use App\Models\Ad;
use App\Traits\ManageFilesTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdService implements AdsServicesInterface
{

    use ManageFilesTrait;
    public function modelQuery(): Builder
    {
        return Ad::query();
    }

    public function getAds(): array
    {
        // TODO: Implement getAds() method.
    }

    public function createAd($request): array
    {

        $ads_image_path = "ads";
        $image = $this->uploadImageToStorage([$request['image']], $ads_image_path);

       $advertisement = $this->modelQuery()
            ->create([
                'user_id' => auth()->id() ?? null,
                'full_name' => $request['full_name'],
                'image' => $image[0],
                'link' => $request['link'],
                'location' => $request['location'],
                'start_date' => $request['start_date'],
                'end_date' => $request['end_date'],
            ]);
       $advertisement = $advertisement->refresh();
       $resource = new AdsResource($advertisement);
       return ['advertisement' => $resource, 'message' => 'Ad created successfully'];
    }

    public function showAd($id): array
    {
        // TODO: Implement showAd() method.
    }

    public function updateAd($request, $id): array
    {
        // TODO: Implement updateAd() method.
    }

    public function deleteAd($id): array
    {
        // TODO: Implement deleteAd() method.
    }
}
