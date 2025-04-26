<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Notifications\AdminMessage;
use Illuminate\Support\Facades\Notification;

use App\Models\ContactUs;
use App\Models\User;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'email' => 'nullable|string|email',
        ]);

        ContactUs::create($validatedData);
        $user = User::where('type', 'admin')->first();
        $messageFromAdmin = "
        A contact request has been sent from the user.
        Username : $request->name
        Telephone number : $request->phone
        Email: $request->email
        Message : $request->message
        ";
        $titleFromAdmin = $request->subject;
        Notification::send([$user],  new AdminMessage($messageFromAdmin, "A contact request has been sent from the user", $messageFromAdmin, "A contact request has been sent from the user", "conatct_us", ""));
        return response()->json([
            'status_code' => 200,
            'message' => 'Successful',
        ], 200);
    }
}
