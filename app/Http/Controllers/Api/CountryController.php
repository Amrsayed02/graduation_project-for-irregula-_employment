<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        try {
            $countries = Country::with('cities')->get();
            return successResponse(['countries' => $countries]);
        } catch (\Throwable $e) {
            return errorResponse(__('Failed to retrieve data'));
        }
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cities($id)
    {
        try {
            $cities = City::get();
            return successResponse(['cities' => $cities]);
        } catch (\Throwable $e) {
            return errorResponse(__('Failed to retrieve data '));
        }
    }
}
