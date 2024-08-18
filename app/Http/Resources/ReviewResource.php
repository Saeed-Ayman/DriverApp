<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Review */
class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'created_at' => (new Carbon($this->created_at))->diffForHumans(),
            'is_updated' => $this->created_at != $this->updated_at,
            'stars' => $this->stars,
            'content' => $this->content,
        ];

        if ($this->relationLoaded('user')) {
            $data['user'] = UserResource::make($this->user);
        }

        return $data;
    }
}
