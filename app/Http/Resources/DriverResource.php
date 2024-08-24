<?php

namespace App\Http\Resources;

use App\Models\Driver;
use App\Models\Review;
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
            'reviews_status' => [
                'count' => $this->reviews_count,
                'average' => +$this->reviews_avg_stars,
            ],
            'user_review' => null
        ];

        if ($this->hasAppended('all')) {
            $data = array_merge($data, [
                'phone' => $this->phone,
                'whatsapp' => $this->whatsapp,
                'description' => $this->description,
                'avatar' => $this->avatar,
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

        return $data;
    }
}
