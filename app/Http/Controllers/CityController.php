<?php

namespace App\Http\Controllers;

use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        return CityResource::collection(City::all());
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

    public function show(City $city)
    {
        return new CityResource($city);
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
