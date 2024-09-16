<?php

namespace App\Http\Controllers;

use App\Http\Resources\LocationCollection;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationFavoriteController extends Controller
{
    public function index()
    {
        return LocationCollection::make(
            Location::whereHas('favorite', fn($query) => $query->where('user_id', \Auth::id()))
                ->with('image')
                ->withReviewsStatus()
                ->withFavorites()
                ->paginate(8)
        );
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'slug' => 'required|exists:App\Models\Location,slug'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $location = Location::where('slug', $request->get('slug'))->firstOrFail();

        if (!$location->favorite()->where('user_id', \Auth::id())->exists()) {
            $location->favorite()->create([
                'user_id' => \Auth::id(),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Favorite added successfully!',
        ]);
    }

    public function destroy($slug)
    {
        $location = Location::where('slug', $slug)->firstOrFail();
        $location->favorite()->where('user_id', \Auth::id())->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Favorite deleted successfully!',
        ]);
    }
}
