<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this['car_id'] != null ? [
            'car_id' => $this['car_id'],
            'full_name' => $this['full_name'],
            'phone_number' => $this['phone_number'],
            'email' => $this['email'],
            'subject' => $this['subject'],
            'comment' => $this['comment'],
        ]: [
            'full_name' => $this['full_name'],
            'phone_number' => $this['phone_number'],
            'email' => $this['email'],
            'subject' => $this['subject'],
            'comment' => $this['comment'],
        ];
    }
}
