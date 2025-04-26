<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SettingWeb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SettingPageController extends Controller
{
    public function termsPage()
    {
        try {
            $terms = SettingWeb::select(DB::raw("terms"))->first();
            return response()->json([
                'data' => $terms->terms,
                'message' => 'Successful',
                'status_code' => 200
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to retrieve data.', 'status_code' => 500], 500);
        }
    }

    public function aboutPage()
    {
        try {
            $about_us = SettingWeb::select(DB::raw("about_us"))->first();
            return response()->json([
                'data' => $about_us->about_us,
                'message' => 'Successful',
                'status_code' => 200
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to retrieve data.', 'status_code' => 500], 500);
        }
    }

    public function privacyPage()
    {
        try {
            $privacy = SettingWeb::select(DB::raw("privacy"))->first();
            return response()->json([
                'data' => $privacy->privacy,
                'message' => 'Successful',
                'status_code' => 200
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to retrieve data.', 'status_code' => 500], 500);
        }
    }
}
