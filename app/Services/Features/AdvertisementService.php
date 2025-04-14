<?php

namespace App\Services\Features;

use App\Http\Resources\AdvertisementsResource;
use App\Interfaces\AdvertisementServicesInterface;
use App\Models\Advertisement;
use App\Traits\ManageFilesTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertisementService implements AdvertisementServicesInterface
{

    use ManageFilesTrait;
    public function modelQuery(): Builder
    {
        return Advertisement::query();
    }
    public function filterAds($request): array
    {
        $adBuilder = $this->modelQuery()
            ->withFilter($request)
            ->withCreator()
            ->isActive()
            ->isApproved();

        $adBuilder
            ->selectedColumn()
            ->orderBy('views', 'desc')
            ->get();

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
        $ads_image_path = "Advertisements";
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
       $resource = new AdvertisementsResource($advertisement);
       return ['advertisement' => $resource, 'message' => 'Advertisement created successfully'];
    }

    public function getAdsById($id): array
    {
        $ad = $this->modelQuery()
            ->withCreator()
            ->isActive()
            ->isApproved()
            ->where('id', $id)
            ->selectedColumn()
            ->first();
        if($ad){
            return ['advertisement' => $ad, 'message' => 'Advertisement indexes successfully'];
        }
        else{
            throw new NotFoundHttpException("Advertisement for id (${id}) not found.");
        }
    }
    public function getAdsForSeller():array
    {
        $advertisements = $this->modelQuery()
            ->where('user_id', auth()->id());
        $advertisements->orderBy('views', 'desc')->get();

        if($advertisements->count() == 0) {
            $message = 'There is no Advertisements found for you yet.';
            $advertisements = [];
        }else{
            $message = 'Advertisements indexed successfully.';
            $advertisements = $advertisements->paginate(10);
        }
        return ['advertisement' => $advertisements, 'message' => $message];
    }

    /**
     * @throws AuthorizationException
     */
    public function updateAdBySeller($request, $id): array
    {
        $advertisement = $this->modelQuery()->where('id',$id)->first();
        if(!is_null($advertisement)){
            if ((Auth::user()->hasRole('seller') && Auth::id() == $advertisement['user_id'])) {
                 if($request->hasFile('image')){
                     $this->deleteImageFromStorage([$advertisement['image']]);
                     $image = $this->uploadImageToStorage([$request['image']], 'Advertisements');
                 }
                $this->modelQuery()
                    ->where('id', $id)
                    ->update([
                        'full_name' => $request['name'] ?? $advertisement['full_name'],
                        'image' => $image[0] ?? $advertisement['image'],
                        'link' => $request['link'] ?? $advertisement['link'],
                        'location' => $request['location'] ?? $advertisement['location'],
                        'start_date' => $request['start_date'] ?? $advertisement['start_date'],
                        'end_date' => $request['end_date'] ?? $advertisement['end_date'],
                    ]);
                $advertisement->refresh();
                $message = 'Seller updated advertisement successfully.';
                return ['advertisement' => $advertisement, 'message' => $message];
            }
            else{
                throw new AuthorizationException('You are not authorized to update this advertisement.');
            }
        }
        else{
            throw new NotFoundHttpException('Advertisement not found.');
        }

    }

    /**
     * @throws AuthorizationException
     */
    public function updateAdByAdmin($request, $id): array
    {
        $advertisement = $this->modelQuery()->where('id',$id)->first();
        if(!is_null($advertisement)){
            if(Auth::user()->hasRole('admin')){
                $advertisement = $this->modelQuery()
                    ->where('id', $id)
                    ->first();
                $advertisement->status = $request['status'];
                $advertisement->save();
                $advertisement->refresh();
                $advertisement = $advertisement->where('id', $id)
                    ->withCreator()
                    ->first();
                $message = 'Admin updated advertisement successfully.';
                return ['advertisement' => $advertisement, 'message' => $message];
            }
            else{
                throw new AuthorizationException('You are not authorized to update this advertisement.');
            }
        }
        else{
            throw new NotFoundHttpException('Advertisement not found.');
        }

    }

    public function deleteAd($id): array
    {
        // TODO: Implement deleteAd() method.
    }


}
