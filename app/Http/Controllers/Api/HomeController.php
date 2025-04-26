<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Rating;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function home()
    {
        try {
            $banners = Banner::orderBy('arrange')->get();
            $categories = Category::get();

            $workers  = User::where('type', "vendor")->select(
                'users.id',
                'users.name',
                'users.image',
                'users.category_id'
            )
                ->with(
                    [
                        'category' => function ($query) {
                            $query->select('id', 'title');
                        }
                    ]
                )
                ->leftJoin('ratings', 'ratings.vendor_id', '=', 'users.id')  // Left join to include users with no ratings
                ->selectRaw('COALESCE(AVG(ratings.rating), 0) as average_rating')  // Calculate average rating, default to 0 if null
                ->groupBy('users.id', 'users.name', 'users.image', 'users.category_id')  // Group by user fields
                ->get();

            return successResponse(['banners' => $banners, "categories" => $categories, "workers" => $workers]);
        } catch (\Throwable $e) {
            return errorResponse(__('custom.data could not be retrieved' . $e->getMessage()));
        }
    }

    public function getWorkerByCategoryId($id)
    {
        try {
            $workers = User::where('category_id', $id)->where('type', "vendor")
                ->select(
                    'users.id',
                    'users.name',
                    'users.image',
                    'users.category_id'
                )
                ->with(
                    [
                        'category' => function ($query) {
                            $query->select('id', 'title');
                        }
                    ]
                )
                ->leftJoin('ratings', 'ratings.vendor_id', '=', 'users.id')  // Left join to include users with no ratings
                ->selectRaw('COALESCE(AVG(ratings.rating), 0) as average_rating')  // Calculate average rating, default to 0 if null
                ->groupBy('users.id', 'users.name', 'users.image', 'users.category_id')  // Group by user fields
                ->get();
            $workersResults = User::where('type', "vendor")
                ->select(
                    'users.id',
                    'users.name',
                    'users.image',
                    'users.category_id'
                )
                ->with(
                    [
                        'category' => function ($query) {
                            $query->select('id', 'title');
                        }
                    ]
                )
                ->leftJoin('ratings', 'ratings.vendor_id', '=', 'users.id')  // Left join to include users with no ratings
                ->selectRaw('COALESCE(AVG(ratings.rating), 0) as average_rating')  // Calculate average rating, default to 0 if null
                ->groupBy('users.id', 'users.name', 'users.image', 'users.category_id')  // Group by user fields
                ->get();
            return successResponse(["category_workers" => $workers, "results" => $workersResults]);
        } catch (\Throwable $e) {
            return errorResponse(__('custom.data could not be retrieved' . $e->getMessage()));
        }
    }

    public function workerDetails($id)
    {
        try {
            $worker = User::where('users.id', $id)
                ->select(
                    'users.id',
                    'users.name',
                    'users.image',
                    'users.city_id',
                    'users.category_id'
                )
                ->with([
                    'category' => function ($query) {
                        $query->select('categories.id', 'categories.title');
                    },
                    'ratings' => function ($query) {
                        $query->select(
                            'ratings.id',
                            'ratings.vendor_id',
                            'ratings.rating',
                            'ratings.review',
                            'ratings.user_id', // Add user_id
                            'ratings.created_at' // Optional: include creation date
                        )->with([
                            'user' => function ($query) {
                                $query->select(
                                    'id',
                                    'name',
                                    'image'
                                    // Add any other user fields you need
                                );
                            }
                        ]);
                    },
                    'city'
                ])
                ->leftJoin('ratings', 'ratings.vendor_id', '=', 'users.id')
                ->selectRaw('COALESCE(AVG(ratings.rating), 0) as average_rating')
                ->groupBy('users.id', 'users.name', 'users.image', 'users.category_id', 'users.city_id')
                ->first();
            $worker["customers"] = count($worker->ratings);
            $worker["hour"] = 0;
            return successResponse(["worker" => $worker]);
        } catch (\Throwable $e) {
            return errorResponse(__('custom.data could not be retrieved' . $e->getMessage()));
        }
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return errorResponse($validator->errors()->first(), 422);
        }

        try {
            // Check if user has already rated this vendor
            $existingRating = Rating::where('user_id', $request->user->id)
                ->where('vendor_id', $request->vendor_id)
                ->first();

            if ($existingRating) {
                return errorResponse(__('The score has already been given.'), 400);
            }

            // Create new rating
            $rating = Rating::create([
                'user_id' => $request->user->id, // Current authenticated user
                'vendor_id' => $request->vendor_id,
                'rating' => $request->rating,
                'review' => $request->review ?? null
            ]);

            return successResponse(
                $rating,
                201,
                __('custom.evaluation_successful')
            );
        } catch (\Throwable $e) {
            // Log the error for internal tracking
            Log::error('Rating submission error: ' . $e->getMessage());
            return errorResponse(__('Custom scoring could not be sent.'), 500);
        }
    }
}
