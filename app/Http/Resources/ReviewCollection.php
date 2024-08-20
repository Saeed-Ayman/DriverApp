<?php

namespace App\Http\Resources;

use App\Traits\HasCustomPagination;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReviewCollection extends ResourceCollection
{
    use HasCustomPagination;


    public function getJsonResource(): string
    {
        return ReviewResource::class;
    }
}
