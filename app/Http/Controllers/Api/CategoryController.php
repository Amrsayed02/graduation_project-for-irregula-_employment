<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Category::query();

            // If a search query is provided, filter by title
            if ($request->has('search')) {
                $searchTerm = $request->input('search');
                $query->where('title', 'like', "%{$searchTerm}%");
            }

            // You can add additional filtering or sorting if needed
            $categories = $query->get();

            return successResponse(["categories" => $categories]);
        } catch (\Throwable $e) {
            return errorResponse(__('Failed to retrieve data') . $e->getMessage());
        }
    }
}
