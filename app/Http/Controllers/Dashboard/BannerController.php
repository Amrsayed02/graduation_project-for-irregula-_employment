<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use App\Traits\ImageProcessing;

class BannerController extends Controller
{
    use ImageProcessing;
    public function index()
    {
        $banners = Banner::orderBy('arrange', 'asc')->get();
        return view('dashboard.banner.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required|string|max:255',
            'arrange' => 'nullable|integer|min:0',
            'status' => 'boolean',
            'type' => 'required|in:banner,ads',
            'banner_url' => 'required|url'
        ], [
            'image.required' => 'يرجى اختيار صورة',
            'image.image' => 'الملف المرفق يجب أن يكون صورة',
            'name.required' => 'يرجى إدخال الاسم',
            'banner_url.required' => 'يرجى إدخال الرابط',
            'banner_url.url' => 'يرجى إدخال رابط صحيح'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->except('_token');

            if ($request->hasFile('image')) {
                $data['image'] = 'imagesfp/banner/' . $this->saveImage($request->file('image'), 'banner');
            }

            $data['status'] = $request->has('status');
            $data['arrange'] = $request->arrange ?? Banner::max('arrange') + 1;

            Banner::create($data);

            session()->flash('Add', 'تم إضافة البانر بنجاح');
            return redirect()->route('banner.index');
        } catch (\Exception $e) {
            session()->flash('delete', 'حدث خطأ أثناء الإضافة: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء الإضافة: ' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required|string|max:255',
            'arrange' => 'nullable|integer|min:0',
            'status' => 'boolean',
            'type' => 'required|in:banner,ads',
            'banner_url' => 'required|url'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $banner = Banner::findOrFail($request->id);
            $data = $request->except(['_token', '_method']);

            if ($request->hasFile('image')) {
                // Delete old image
                if ($banner->image && file_exists(storage_path($banner->image))) {
                    unlink(storage_path($banner->image));
                }

                $data['image'] = 'imagesfp/banner/' . $this->saveImage($request->file('image'), 'banner');
            }

            $data['status'] = $request->has('status');

            $banner->update($data);

            session()->flash('edit', 'تم تعديل البانر بنجاح');
            return redirect()->route('banner.index');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء التعديل: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $banner = Banner::findOrFail($request->id);

            // Delete image
            if ($banner->image && file_exists(storage_path($banner->image))) {
                unlink(storage_path($banner->image));
            }

            $banner->delete();

            session()->flash('delete', 'تم حذف البانر بنجاح');
            return redirect()->route('banner.index');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء الحذف: ' . $e->getMessage());
        }
    }
    public function updateStatusBanner(Request $request)
    {
        $isToggleOnString = (string) $request->isToggleOn;
        $status = true;
        $categoryId = $request->input('categoryId');
        if ($isToggleOnString == "true") {
            $status = true;
        } else {
            $status = false;
        }



        $banner = Banner::find($categoryId);

        if ($banner) {
            // Update the status field
            $banner->status = $status;
            $banner->save();

            return response()->json(['success' => true, 'message' => 'banner status  updated successfully']);
        }

        return response()->json(['success' => false, 'message' => 'banner not found']);
    }
}
