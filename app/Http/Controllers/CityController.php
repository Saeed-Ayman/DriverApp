<?php

namespace App\Http\Controllers;

use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request, $countryId)
    {
        $cities = City::where('country_id', $countryId)->has('locations')->withCount('locations');

        if ($request->has('q')) {
            $cities = $cities->where('name_en', 'like', '%'.$request->input('q').'%');
        }

        if ($request->routeIs('locations.countries.cities.index')) {
            $cities
                ->withCount('locations')
                ->has('locations')
                ->orderByDesc('locations_count');
        }

        if ($request->routeIs('drivers.countries.cities.index')) {
            $cities
                ->withCount('drivers')
                ->has('drivers')
                ->orderByDesc('drivers_count');
        }

        return CityResource::collection($cities->limit(5)->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required'],
            'name_ar' => ['required'],
            'name_en' => ['required'],
            'country_id' => ['required', 'exists:countries'],
        ]);

        return new CityResource(City::create($data));
    }

    public function show($countryId, $cityId)
    {
        return CityResource::make(
            City::where('country_id', $countryId)
                ->where('id', $cityId)
                ->firstOrFail()
        );
    }

    public function update(Request $request, City $city)
    {
        $data = $request->validate([
            'code' => ['required'],
            'name_ar' => ['required'],
            'name_en' => ['required'],
            'country_id' => ['required', 'exists:countries'],
        ]);

        $city->update($data);

        return new CityResource($city);
    }

    public function destroy(City $city)
    {
        $city->delete();

        return response()->json();
    }
}
