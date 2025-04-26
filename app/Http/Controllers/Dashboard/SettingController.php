<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use App\Traits\ImageProcessing;

class SettingController extends Controller
{

    use ImageProcessing;

    public function index()
    {
        $setting = Setting::where('user_id', Auth::user()->id)->first();
        return view('dashboard.setting.index', ['setting' => $setting]);
    }


    public function update(Request $request)
    {
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'company_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'website_link' => 'nullable|string|max:255',
                'company_phone' => 'nullable|string|max:20',
                'company_address' => 'nullable|string|max:255',
                'twitter' => 'nullable|string|max:255',
                'facebook' => 'nullable|string|max:255',
                'google' => 'nullable|string|max:255',
                'linkedin' => 'nullable|string|max:255',
                'github' => 'nullable|string|max:255',
                'biographical_information' => 'nullable|string',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // If validation fails, redirect back with errors and old input
            if ($validator->fails()) {
                session()->flash('delete', 'يوجد مشكلة في البيانات المدخلة');
                return back()->withErrors($validator)->withInput();
            }

            // Find the setting record for the authenticated user
            $setting = Setting::where('user_id', Auth::user()->id)->firstOrFail();

            // Prepare the data for update
            $data = [
                'company_name' => $request->company_name,
                'email' => $request->email,
                'website_link' => $request->website_link,
                'company_phone' => $request->company_phone,
                'company_address' => $request->company_address,
                'twitter' => $request->twitter,
                'facebook' => $request->facebook,
                'google' => $request->google,
                'linkedin' => $request->linkedin,
                'github' => $request->github,
                'biographical_information' => $request->biographical_information,
            ];

            // Handle logo upload
            if ($request->hasFile('image')) {
                $image = $this->saveImage($request->file('image'), 'setting');
                $data['logo'] = 'imagesfp/setting/' . $image;
            }



            // Update the setting record
            $setting->update($data);

            // Flash success message and redirect back
            session()->flash('Add', 'تم تحديث البيانات بنجاح');
            return back();
        } catch (\Throwable $th) {
            // Flash error message and redirect back in case of an exception
            session()->flash('error', 'حدث خطأ أثناء تحديث البيانات. يُرجى المحاولة مرة أخرى.');
            return back();
        }
    }


    // public function update(Request $request)
    // {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'username' => 'required|string|max:255',
    //             'email' => 'required|email|max:255',
    //             'phone' => 'nullable|string|max:20',
    //             'address' => 'nullable|string|max:255',
    //             'twitter' => 'nullable|string|max:255',
    //             'facebook' => 'nullable|string|max:255',
    //             'google' => 'nullable|string|max:255',
    //             'linkedin' => 'nullable|string|max:255',
    //             'aboutcompany' => 'nullable|string',
    //             'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //         ]);

    //         if ($validator->fails()) {
    //             session()->flash('delete', 'يوجد مشكله ما');
    //             return back()->withErrors($validator)->withInput();
    //         }

    //         $setting = Setting::findOrFail(Auth::user()->id);



    //         $data = [
    //             'company_name' => $request->username,
    //             'email' => $request->email,
    //             // 'logo' => $data['logo'],
    //             'company_phone' => $request->phone,
    //             'company_address' => $request->address,
    //             'twitter' => $request->twitter,
    //             'facebook' => $request->facebook,
    //             'google' => $request->google,
    //             'linkedin' => $request->linkedin,
    //             'biographical_information' => $request->aboutcompany,
    //         ];
    //         if ($request->hasFile('image')) {
    //             $image = $this->saveImage($request->file('image'), 'setting');
    //             $data['logo'] = 'imagesfp/setting/' . $image;
    //         }
    //         $setting->update($data);

    //         session()->flash('Add', 'تم تحديث البيانات بنجاح');
    //         return back();
    //     } catch (\Throwable $th) {
    //         session()->flash('error', 'حدث خطأ أثناء تحديث البيانات. يُرجى المحاولة مرة أخرى.');
    //         return back();
    //     }
    // }
}
