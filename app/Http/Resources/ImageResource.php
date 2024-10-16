<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Image */
class ImageResource extends JsonResource
{
    public function toArray(Request $request): string
    {
        return $this->image_url;
    }
}
