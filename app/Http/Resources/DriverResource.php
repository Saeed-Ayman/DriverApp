<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/** @mixin \App\Models\Driver */
class DriverResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'name' => $this->name,
            'username' => $this->slug,
            'favorite' => $this->favorite_exists,
            'reviews_status' => [
                'count' => $this->reviews_count,
                'average' => +$this->reviews_avg_stars,
            ],
        ];

        if ($this->hasAppended('all')) {
            $data = array_merge($data, [
                'phone' => $this->phone,
                'whatsapp' => $this->whatsapp,
                'description' => $this->description,
                'avatar' => $this->avatar,
                'user_review' => null
            ]);

            $auth = Auth::guard('sanctum');

            if ($auth->check()) {
                $rate = $this->reviews()->whereUserId($auth->id())->first();

                if ($rate) {
                    $data['user_review'] = ReviewResource::make($rate);
                }
            }
        }

        if ($this->relationLoaded('image')) {
            $data['image'] = ImageResource::make($this->image);
        }

        if ($this->relationLoaded('images')) {
            $data['images'] = ImageResource::collection($this->images);
        }

        if ($this->relationLoaded('city')) {
            $data['city'] = CityResource::make($this->city);
            $data['country'] = CountryResource::make($this->city->country);
        }

        return $data;
    }
}
