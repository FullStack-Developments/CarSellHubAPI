<?php

namespace App\Services\Features;

use App\Models\Setting;
use App\Traits\ManageFilesTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SettingService
{
    use ManageFilesTrait;
    public function modelQuery():Builder
    {
        return Setting::query();
    }
    public function getSettings():array
    {
        $setting = Setting::all();
        $message = 'Settings indexes Successfully!';
        return ['setting' => $setting, 'message' => $message];
    }

    /**
     * @throws AuthorizationException
     */
    public function createSetting($request):array
    {
        if(!Auth::user()->hasRole('admin')){
            throw new AuthorizationException();
        }
        $website_icon = $this->uploadImageToStorage([$request->file('website_icon')], 'settings/icons');
        $website_logo = $this->uploadImageToStorage([$request->file('website_logo')], 'settings/logos');
        $intro_images[] = $this->uploadImageToStorage($request->file('intro_images'),'settings/intros');
        $intro_encode = json_encode($intro_images[0], JSON_UNESCAPED_UNICODE);
        $keywords = json_encode($request->intro_keywords, JSON_UNESCAPED_UNICODE);
        $setting = $this->modelQuery()->create([
            'website_name' => $request->website_name,
            'website_icon' => $website_icon[0],
            'website_logo' => $website_logo[0],
            'intro_images' => $intro_encode,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'intro_keywords' => $keywords,
            'site_description' => $request->site_description,
            'facebook_url' => $request->facebook_url ?? null,
            'twitter_url' => $request->twitter_url ?? null,
            'instagram_url' => $request->instagram_url ?? null,
            'linkedin_url' => $request->linkedin_url ?? null,
            'whatsapp_url' => $request->whatsapp_url ?? null,
            'language' => $request->language,
        ]);
        $setting->refresh();
        $message = 'Settings successfully created!';
        return ['setting' => $setting, 'message' => $message];
    }

    /**
     * @throws AuthorizationException
     */
    public function updateSetting($request, $id):array
    {
        $setting = $this->modelQuery()->where('id', $id)->first();
        if(!is_null($setting)){
            if(!Auth::user()->hasRole('admin')){
                throw new AuthorizationException();
            }
            if($request->hasFile('website_icon')){
                $this->deleteImageFromStorage([$setting->website_icon], 'settings/icons');
                $website_icon = $this->uploadImageToStorage([$request->file('website_icon')], 'settings/icons');
            }
            if($request->hasFile('website_logo')){
                $this->deleteImageFromStorage([$setting->website_logo], 'settings');
                $website_logo = $this->uploadImageToStorage([$request->file('website_logo')],'settings/logos');
            }
            if($request->hasFile('intro_images')){
                $intro_decode = json_decode($setting->intro_images);
                $this->deleteImageFromStorage($intro_decode, 'settings/intros');
                $intro_images[] = $this->uploadImageToStorage($request->file('intro_images'),'settings/intros');
            }
            $setting->update([
                'website_name' => $request->website_name ?? $setting->website_name,
                'website_icon' => $website_icon[0] ?? $setting->website_icon,
                'website_logo' => $website_logo[0] ?? $setting->website_logo,
                'intro_images' => $intro_images[0] ?? $setting->intro_images,
                'contact_email' => $request->contact_email ?? $setting->contact_email,
                'contact_phone' => $request->contact_phone ?? $setting->contact_phone,
                'intro_keywords' => $request->intro_keywords ?? $setting->intro_keywords,
                'site_description' => $request->site_description ?? $setting->site_description,
                'facebook_url' => $request->facebook_url ?? $setting->facebook_url,
                'twitter_url' => $request->twitter_url ?? $setting->twitter_url,
                'instagram_url' => $request->instagram_url ?? $setting->instagram_url,
                'linkedin_url' => $request->linkedin_url ?? $setting->linkedin_url,
                'whatsapp_url' => $request->whatsapp_url ?? $setting->whatsapp_url,
                'language' => $request->language ?? $setting->language,
            ]);
            $setting->refresh();
            $message = 'Settings successfully updated!';
            return ['setting' => $setting, 'message' => $message];

        }
        else {
            throw new ModelNotFoundException('Setting not found!');
        }
    }
}
