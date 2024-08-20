<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReviewCollection;
use App\Http\Resources\ReviewResource;
use App\Models\Driver;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewsDriverController extends Controller
{
    public function index(Driver $driver)
    {
        $auth = Auth::guard('sanctum');
        $reviews = $driver->reviews();

        if ($auth->check()) {
            $reviews->whereNot('user_id', $auth->user()->id);
        }

        return ReviewCollection::make ($reviews->latest()->Paginate(8));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'stars' => ['required'],
            'comment' => ['required'],
            'user_id' => ['required', 'exists:users'],
        ]);

        return new ReviewResource(Review::create($data));
    }

    public function show(Review $rate)
    {
        return new ReviewResource($rate);
    }

    public function update(Request $request, Review $rate)
    {
        $data = $request->validate([
            'stars' => ['required'],
            'comment' => ['required'],
            'commentable' => ['required', 'integer'],
            'user_id' => ['required', 'exists:users'],
        ]);

        $rate->update($data);

        return new ReviewResource($rate);
    }

    public function destroy(Review $rate)
    {
        $rate->delete();

        return response()->json();
    }
}
