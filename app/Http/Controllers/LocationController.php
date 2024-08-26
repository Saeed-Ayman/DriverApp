<?php

namespace App\Http\Controllers;

use App\Http\Resources\LocationCollection;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        return LocationCollection::make(
            Location::with('image')
                ->withReviewsStatus()
                ->orderByRaw('(reviews_avg_stars * reviews_count) / (reviews_count + 10) desc')
                ->paginate(8)
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required'],
            'image_id' => ['required'],
            'description' => ['required'],
            'whatsapp' => ['required'],
            'phone' => ['required'],
            'landline' => ['required'],
            'prices' => ['required'],
        ]);

        return new LocationResource(Location::create($data));
    }

    public function show(Location $location)
    {
        return LocationResource::make(
            $location->load('images', 'country', 'city')
                ->loadWithReviewsStatus()
                ->append('all')
        );
    }

    public function update(Request $request, Location $location)
    {
        $data = $request->validate([
            'name' => ['required'],
            'image_id' => ['required'],
            'description' => ['required'],
            'whatsapp' => ['required'],
            'phone' => ['required'],
            'landline' => ['required'],
            'prices' => ['required'],
        ]);

        $location->update($data);

        return new LocationResource($location);
    }

    public function destroy(Location $location)
    {
        $location->delete();

        return response()->json();
    }
}
