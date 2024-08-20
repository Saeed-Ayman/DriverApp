<?php

namespace App\Http\Resources;

use App\Traits\HasCustomPagination;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/** @see \App\Models\Driver */
class DriverCollection extends ResourceCollection
{
    use HasCustomPagination;

    public function getJsonResource(): string
    {
        return DriverResource::class;
    }
}
