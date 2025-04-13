<?php

namespace App\Services\Features;

use App\Http\Resources\AdsResource;
use App\Interfaces\AdsServicesInterface;
use App\Models\Ad;
use App\Traits\ManageFilesTrait;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdService implements AdsServicesInterface
{

    use ManageFilesTrait;
    public function modelQuery(): Builder
    {
        return Ad::query();
    }

    public function filterAds($request): array
    {
        $adBuilder = $this->modelQuery()->filter($request)->withCreator();

        $adBuilder->latest()->get();

        if($adBuilder->count() == 0) {
            $message = 'There is no Advertisements found.';
            $adBuilder = [];
        }else{
            $message = 'Advertisements indexed successfully.';
            $adBuilder = $adBuilder->paginate(10);
        }
        return ['ads' => $adBuilder, 'message' => $message];    }

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
       return ['advertisement' => $resource, 'message' => 'Advertisement created successfully'];
    }

    public function getAdsById($id): array
    {
        $ad = $this->modelQuery()
            ->withCreator()
            ->where('id', $id)
            ->first();
        if($ad){
            return ['advertisement' => $ad, 'message' => 'Advertisement indexes successfully'];
        }
        else{
            throw new NotFoundHttpException("Advertisement for id (${id}) not found.");
        }
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
