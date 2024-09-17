<?php

namespace App\Http\Controllers;

use App\Http\Resources\DriverCollection;
use App\Http\Resources\DriverResource;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        $drivers = Driver::with('image');

        if ($request->has('q')) {
            $drivers = $drivers->where('name', 'like', '%'.$request->input('q').'%');
        }

        if ($request->has('country')) {
            $drivers->where('country_id', $request->input('country'));
        }

        if ($request->has('city')) {
            $drivers->where('city_id', $request->input('city'));
        }

        return DriverCollection::make(
            $drivers
                ->withReviewsStatus()
                ->withFavorites()
                ->orderByRaw('(reviews_avg_stars * reviews_count) / (reviews_count + 10) desc')
                ->paginate(8)
        );
    }

    public function store(Request $request)
    {
        throw new \Exception("Not available yet");

//        $data = $request->validate([
//            'name' => ['required'],
//            'whatsapp' => ['required'],
//            'phone' => ['required'],
//        ]);

//        return new DriverResource(Driver::create($data));
    }

    public function show(Driver $driver)
    {
        return DriverResource::make(
            $driver
                ->load('images', 'country', 'city')
                ->loadWithReviewsStatus()
                ->loadWithFavorites()
                ->append('all')
        );
    }

    public function update(Request $request, Driver $driver)
    {
        $data = $request->validate([
            'name' => ['required'],
            'whatsapp' => ['required'],
            'headline' => ['required'],
            'phone' => ['required'],
        ]);

        $driver->update($data);

        return new DriverResource($driver);
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();

        return response()->json();
    }
}
