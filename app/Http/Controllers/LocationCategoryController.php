<?php

namespace App\Http\Controllers;

use App\Http\Resources\LocationCategoryResource;
use App\Models\LocationCategory;
use Illuminate\Http\Request;

class LocationCategoryController extends Controller
{
    public function index()
    {
        return LocationCategoryResource::collection(LocationCategory::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required'],
        ]);

        return new LocationCategoryResource(LocationCategory::create($data));
    }

    public function show(LocationCategory $locationCategory)
    {
        return new LocationCategoryResource($locationCategory);
    }

    public function update(Request $request, LocationCategory $locationCategory)
    {
        $data = $request->validate([
            'name' => ['required'],
        ]);

        $locationCategory->update($data);

        return new LocationCategoryResource($locationCategory);
    }

    public function destroy(LocationCategory $locationCategory)
    {
        $locationCategory->delete();

        return response()->json();
    }
}
