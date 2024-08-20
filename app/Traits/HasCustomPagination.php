<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait HasCustomPagination
{
    private array $pagination;

    public function __construct($resource)
    {
        $this->pagination = [
            'total' => $resource->total(),
            'perPage' => $resource->perPage(),
            'currentPage' => $resource->currentPage(),
            'from' => $resource->firstItem(),
            'to' => $resource->lastItem(),
            'lastPage' => $resource->lastPage(),
        ];

        $resource = $resource->getCollection();

        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        $resource = $this->getJsonResource();

        return [
            'data' => $resource::collection($this->collection),
            'pagination' => $this->pagination
        ];
    }

    abstract public function getJsonResource(): string;
}
