<?php

namespace App\Http\Controllers;

use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $countries = Country::query();

        if ($request->has('q')) {
            $countries->where('name_en', 'like', '%' . $request->input('q') . '%');
        }

        if ($request->routeIs('locations.countries.index')) {
            $countries
                ->withCount('locations')
                ->has('locations')
                ->orderByDesc('locations_count');
        }

        if ($request->routeIs('drivers.countries.index')) {
            $countries
                ->withCount('drivers')
                ->has('drivers')
                ->orderByDesc('drivers_count');
        }

        return CountryResource::collection(
            $countries->limit(5)->get()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_ar' => ['required'],
            'name_en' => ['required'],
            'code' => ['required'],
        ]);

        return new CountryResource(Country::create($data));
    }

    public function show($countryId)
    {
        return CountryResource::make(Country::findOrFail($countryId));
    }

    public function update(Request $request, Country $country)
    {
        $data = $request->validate([
            'name_ar' => ['required'],
            'name_en' => ['required'],
            'code' => ['required'],
        ]);

        $country->update($data);

        return new CountryResource($country);
    }

    public function destroy(Country $country)
    {
        $country->delete();

        return response()->json();
    }
}
