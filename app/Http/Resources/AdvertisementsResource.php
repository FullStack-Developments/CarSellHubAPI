<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdvertisementsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'full_name' => $this['full_name'],
            'image' => $this['image'],
            'link' => $this['link'],
            'location' => $this['location'],
            'hits' => $this['hits'],
            'views' => $this['views'],
            'is_active' => $this['is_active'],
            'status' => $this['status'],
            'start_date' => $this['start_date'],
            'end_date' => $this['end_date'],
        ];
    }
}
