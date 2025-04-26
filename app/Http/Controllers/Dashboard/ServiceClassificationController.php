<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ServiceClassificationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['user', 'vendor'])->get();
        $users = User::where('type', 'user')->get();
        $vendors = User::where('type', 'vendor')->get();
        return view('dashboard.service_classifications.index', compact('reservations', 'users', 'vendors'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'user_id' => 'required|exists:users,id',
            'vendor_id' => 'required|exists:users,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'user_signup' => 'boolean',
            'vendor_signup' => 'boolean'
        ], [
            'date.required' => 'يرجى تحديد التاريخ',
            'date.after_or_equal' => 'يجب أن يكون التاريخ اليوم أو بعده',
            'time.required' => 'يرجى تحديد الوقت',
            'user_id.required' => 'يرجى اختيار المستخدم',
            'vendor_id.required' => 'يرجى اختيار مقدم الخدمة',
            'price.required' => 'يرجى تحديد السعر',
            'price.numeric' => 'السعر يجب أن يكون رقماً',
            'status.required' => 'يرجى تحديد الحالة'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'فشلت عملية الإضافة');
        }

        try {
            Reservation::create($request->all());
            session()->flash('Add', 'تم إضافة الحجز بنجاح');
            return redirect()->route('service_classifications.index');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء الإضافة: ' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'time' => 'required',
            'user_id' => 'required|exists:users,id',
            'vendor_id' => 'required|exists:users,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'user_signup' => 'boolean',
            'vendor_signup' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $reservation = Reservation::findOrFail($request->id);
            $reservation->update($request->all());

            session()->flash('edit', 'تم تعديل الحجز بنجاح');
            return redirect()->route('service_classifications.index');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء التعديل: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            Reservation::findOrFail($request->id)->delete();
            session()->flash('delete', 'تم حذف الحجز بنجاح');
            return redirect()->route('service_classifications.index');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء الحذف: ' . $e->getMessage());
        }
    }
}
