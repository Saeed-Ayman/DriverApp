<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReviewCollection;
use App\Http\Resources\ReviewResource;
use App\Models\Driver;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class ReviewsDriverController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show']),
        ];
    }

    public function index(Driver $driver)
    {
        $auth = Auth::guard('sanctum');
        $reviews = $driver->reviews();

        if ($auth->check()) {
            $reviews->whereNot('user_id', $auth->user()->id);
        }

        return ReviewCollection::make($reviews->latest()->Paginate(8));
    }

    public function store(Request $request, $driverId)
    {
        $validator = \Validator::make($request->all(), [
            'stars' => ['required', 'numeric', 'between:0,5'],
            'content' => ['required', 'min:3', 'max:250'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $driver = Driver::where('slug', $driverId)->first();

        if (is_null($driver)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Driver not found!',
            ]);
        }

        $reviews = $driver->reviews();
        $existingReview = $reviews->where('user_id', Auth::id())->first();

        if (!is_null($existingReview)) {
            return response()->json([
                'status' => 'error',
                'message' => 'User is already reviewed!',
            ], 400);
        }

        $data = $validator->validated();
        $data['user_id'] = Auth::id();

        $review = $reviews->create($data);

        return ReviewResource::make($review);
    }

    public function show(Review $rate)
    {
        return new ReviewResource($rate);
    }

    public function update(Request $request, $driverId)
    {
        $validator = \Validator::make($request->all(), [
            'stars' => ['required', 'numeric', 'between:0,5'],
            'content' => ['required', 'min:3', 'max:250'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $driver = Driver::where('slug', $driverId)->first();

        if (is_null($driver)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Driver not found!',
            ]);
        }

        $review = $driver->reviews()->where('user_id', Auth::id())->first();

        if (is_null($review)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Review not found!',
            ], 404);
        }

        if ($review->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'You can\'t edit this review!'
            ], 401);
        }

        $review->update($validator->validated());

        return ReviewResource::make($review);
    }

    public function destroy($driverId)
    {
        $driver = Driver::where('slug', $driverId)->first();

        if (is_null($driver)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Driver not found!',
            ]);
        }

        $review = $driver->reviews()->where('user_id', Auth::id())->first();

        if (is_null($review)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Review not found!',
            ], 404);
        }

        if ($review->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'You can\'t edit this review!',
            ], 401);
        }

        $review->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Review successfully deleted!'
        ]);
    }
}
