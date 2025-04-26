<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:عرض المدن', ['only' => ['index']]);
    //     $this->middleware('permission:اضافة مدينة', ['only' => ['store']]);
    //     $this->middleware('permission:تعديل مدينة', ['only' => ['update']]);
    //     $this->middleware('permission:حذف مدينة', ['only' => ['destroy']]);
    // }

    // Display all cities
    public function index()
    {
        $cities = City::where('country_id', 1)->orderBy('name')->get();
        return view('dashboard.cities.index', compact('cities'));
    }

    // Store a new city
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:cities|max:100',
        ], [
            'name.required' => 'يرجى إدخال اسم المدينة',
            'name.unique' => 'اسم المدينة مسجل مسبقاً',
            'name.max' => 'يجب ألا يتجاوز اسم المدينة 100 حرف',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            City::create([
                'name' => $request->name,
                'country_id' => 1, // Always set country_id to 1
            ]);

            session()->flash('Add', 'تم اضافة المدينة بنجاح');
            return redirect()->route('cities.index');
        } catch (\Exception $e) {
            session()->flash('delete', 'حدث خطأ أثناء حفظ المدينة');
            return redirect()->back();
        }
    }

    // Update an existing city
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100|unique:cities,name,' . $id,
        ], [
            'name.required' => 'يرجى إدخال اسم المدينة',
            'name.unique' => 'اسم المدينة مسجل مسبقاً',
            'name.max' => 'يجب ألا يتجاوز اسم المدينة 100 حرف',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $city = City::findOrFail($id);
            $city->name = $request->name;
            $city->save();

            session()->flash('Add', 'تم تحديث المدينة بنجاح');
            return redirect()->route('cities.index');
        } catch (\Exception $e) {
            session()->flash('delete', 'حدث خطأ أثناء تحديث المدينة');
            return redirect()->back();
        }
    }

    // Delete a city
    public function destroy(Request $request)
    {
        try {
            $city = City::findOrFail($request->id);
            $city->delete();

            session()->flash('delete', 'تم حذف المدينة بنجاح');
            return redirect()->route('cities.index');
        } catch (\Exception $e) {
            session()->flash('delete', 'حدث خطأ أثناء حذف المدينة');
            return redirect()->back();
        }
    }
}
