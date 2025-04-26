<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSkill;
use App\Rules\ValidPhoneNumber;
use App\Traits\ImageProcessing;
use Ichtrojan\Otp\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class UserController extends Controller
{
    use ImageProcessing;
    public function updateUserInfo(Request $request)
    {
        try {
            $user = $request->user;

            $validator = Validator::make($request->all(), [
                'name'        => 'required|string|max:255',
                'email'       => 'required|email|unique:users,email,' . $user->id,
                'phone'       => 'required|unique:users,phone,' . $user->id,
                'password'    => 'nullable|min:6',
                'city_id'     => 'required|exists:cities,id',
                'category_id' => 'nullable|exists:categories,id',
                'address'     => 'nullable|string',
                // Resim dosyası için kural ekleyin:
                'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($validator->fails()) {
                return errorResponse($validator->errors()->first());
            }

            $data = $request->only([
                'name',
                'email',
                'phone',
                'address',
                'city_id',
                'category_id'
            ]);

            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
            }

            // İstekte bir resim sağlanıp sağlanmadığını kontrol edin
            if ($request->hasFile('image')) {
                // Yeni fotoğrafı kaydetmek için saveImage yöntemini yeniden kullanın
                $image = $this->saveImage($request->file('image'), 'setting');
                $data['image'] = 'imagesfp/setting/' . $image;
            }

            $user->update($data);

            return successResponse([
                'message' => 'Profil başarıyla güncellendi',
                'user'    => $user
            ]);
        } catch (\Exception $e) {
            return errorResponse('Profil güncellenirken hata oluştu: ' . $e->getMessage());
        }
    }



    public function changePassword(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'old_password' => 'required|string',
                'new_password' => 'required|string|min:8|different:old_password',
                'new_password_confirmation' => 'required|string|same:new_password',
            ]);
            $user = $request->user;
            if (!Hash::check($validatedData['old_password'], $user->password)) {
                return response()->json(['message' => __('custom.old_password_incorrect'), 'status_code' => 422], 422);
            }

            $user->update(['password' => Hash::make($validatedData['new_password'])]);

            return response()->json(['message' => 'Successful', 'status_code' => 200], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => __('custom.server_issue'), 'status_code' => 404], 404);
        }
    }

    public function getUserInfo(Request $request)
    {
        try {
            $token = $request->bearerToken();
            $accessToken = PersonalAccessToken::findToken($token);
            if (!$accessToken) {
                return response()->json(['message' => __('custom.unauthorized'), 401], 401);
            }

            $user = $accessToken->tokenable;
            return successResponse(["user" => $user]);
        } catch (\Throwable $th) {
            return response()->json(['message' => __('custom.server_issue'), 'status_code' => 404], 404);
        }
    }
    public function getUserProfile(int $id)
    {
        try {
            $user = User::find($id);
            $userId = $user->id;
            $userData = User::with([
                'evaluations' => function ($query) use ($id) {
                    $query->where('owner_id', $id);
                },
                'skills',
                "profession",
                'services'
            ])->find($user->id);

            return successResponse(["user" => $userData]);
        } catch (\Throwable $th) {
            return errorResponse("Bir şeyler yanlış gitti", 500);
        }
    }

    public function getOtpForUser(Request $request)
    {
        echo $request->email . "+";
        $email = $request->email;
        $otp = DB::table('otps')->where('identifier', "+" . $email)->orderBy('id', 'desc')->get();
        return response()->json(['otp' => $otp], 200);
    }
}
