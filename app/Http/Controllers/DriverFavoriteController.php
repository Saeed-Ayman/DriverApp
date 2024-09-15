<?php

namespace App\Http\Controllers;

use App\Http\Resources\DriverCollection;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverFavoriteController extends Controller
{
    public function index()
    {
        return DriverCollection::make(
            Driver::whereHas('favorite', fn($query) => $query->where('user_id', \Auth::id()))
                ->with('image')
                ->withReviewsStatus()
                ->paginate(8)
        );
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'username' => 'required|exists:App\Models\Driver,slug'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $driver = Driver::where('slug', $request->get('username'))->firstOrFail();
        $driver->favorite()->create([
            'user_id' => auth()->user()->id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Favorite added successfully!',
        ]);
    }

    public function destroy($username)
    {
        $driver = Driver::where('slug', $username)->firstOrFail();
        $driver->favorite()->where('user_id', \Auth::id())->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Favorite deleted successfully!',
        ]);
    }
}
