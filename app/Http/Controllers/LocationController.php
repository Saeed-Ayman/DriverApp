<?php

namespace App\Http\Controllers;

use App\Http\Resources\LocationCollection;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $locations = Location::with('image');

        if ($request->has('q')) {
            $locations = $locations->where('name', 'like', '%'.$request->input('q').'%');
        }

        if ($request->has('category')) {
            $locations->where('location_category_id', $request->input('category'));
        }

        if ($request->has('country')) {
            $locations->where('country_id', $request->input('country'));
        }

        if ($request->has('city')) {
            $locations->where('city_id', $request->input('city'));
        }

        return LocationCollection::make(
            $locations->withReviewsStatus()
                ->withFavorites()
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
                ->loadWithFavorites()
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
