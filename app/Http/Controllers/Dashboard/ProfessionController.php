<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\ImageProcessing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfessionController extends Controller
{
    use ImageProcessing;
    public function index()
    {
        $categories = Category::orderBy('arange', 'asc')->get();
        return view('dashboard.profession.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'color' => 'nullable|string|max:50',
            'arange' => 'nullable|integer|min:1',
            'is_home' => 'boolean',
            'is_active' => 'boolean'
        ], [
            'title.required' => 'يرجى إدخال العنوان',
            'title.max' => 'العنوان طويل جداً',
            'image.image' => 'الملف المرفق يجب أن يكون صورة',
            'image.mimes' => 'صيغة الصورة غير مدعومة',
            'arange.integer' => 'الترتيب يجب أن يكون رقماً صحيحاً',
            'arange.min' => 'الترتيب يجب أن يكون 1 على الأقل'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'فشلت عملية الإضافة');
        }

        try {
            $data = $request->except('_token');

            if ($request->hasFile('image')) {
                $data['image'] = 'imagesfp/category/' . $this->saveImage($request->file('image'), 'category');
            } // Convert color format
            if (!empty($request->color)) {
                $data['color'] = $this->convertHexToCustomFormat($request->color);
            }

            // Set default values
            $data['is_home'] = $request->has('is_home');
            $data['is_active'] = $request->has('is_active');
            $data['arange'] = $request->arange ?? Category::max('arange') + 1;

            Category::create($data);

            session()->flash('Add', 'تم إضافة الفئة بنجاح');
            return redirect()->route('professions.index');
        } catch (\Exception $e) {
            session()->flash('delete', 'حدث خطأ أثناء الإضافة: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء الإضافة: ' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'color' => 'nullable|string|max:50',
            'arange' => 'nullable|integer|min:1',
            'is_home' => 'boolean',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $category = Category::findOrFail($request->id);
            $data = $request->except(['_token', '_method']);

            if ($request->hasFile('image')) {

                if ($category->image && file_exists(storage_path($category->image))) {
                    $this->deleteImage($category->image);
                }

                $data['image'] = 'imagesfp/category/' . $this->saveImage($request->file('image'), 'category');
            }
            // Convert color format
            if (!empty($request->color)) {
                $data['color'] = $this->convertHexToCustomFormat($request->color);
            }
            // Update boolean fields
            $data['is_home'] = $request->has('is_home');
            $data['is_active'] = $request->has('is_active');

            $category->update($data);

            session()->flash('edit', 'تم تعديل الفئة بنجاح');
            return redirect()->route('professions.index');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء التعديل: ' . $e->getMessage());
        }
    }
    private function convertHexToCustomFormat($hexColor)
    {
        // Remove # if present
        $hex = str_replace('#', '', $hexColor);

        // Ensure 6 characters
        if (strlen($hex) == 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        // Convert to uppercase and add 0xff prefix
        return '0xff' . strtoupper($hex);
    }

    private function convertCustomFormatToHex($customColor)
    {
        // Check if it's in custom format
        if (strpos($customColor, '0xff') === 0) {
            return '#' . substr($customColor, 4);
        }
        return $customColor; // Return as is if not in custom format
    }

    public function destroy(Request $request)
    {
        try {
            $category = Category::findOrFail($request->id);

            // Delete image if exists
            if ($category->image && file_exists(public_path($category->image))) {
                unlink(public_path($category->image));
            }

            $category->delete();

            session()->flash('delete', 'تم حذف الفئة بنجاح');
            return redirect()->route('professions.index');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء الحذف: ' . $e->getMessage());
        }
    }
}
