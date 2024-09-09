<?php

namespace App\Http\Controllers;

use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $countries = Country::withCount('locations')
            ->has('locations');

        if ($request->has('q')) {
            $countries->where('name_en', 'like', '%' . $request->input('q') . '%');
        }

        return CountryResource::collection(
            $countries->orderByDesc('locations_count')
                ->limit(5)
                ->get()
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
