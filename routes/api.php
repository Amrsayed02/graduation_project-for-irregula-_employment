<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\EmailVerificationController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ContactUsController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\MassgeController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\SettingPageController;
use Illuminate\Support\Facades\Route;


Route::group(
    ['middleware' => ['ChangeLanguage']],
    function () {
        Route::post('verification-notification', [EmailVerificationController::class, 'verificationNotification']);
        Route::post('verify-code', [EmailVerificationController::class, 'verifyCode']);
        Route::post('reset-password', [ResetPasswordController::class, 'resetPassword'])->middleware('sanctum');
        Route::post('send-notification', [NotificationController::class, 'sendNotificationToUser']);

        Route::controller(AuthController::class)->group(function () {
            Route::post('/login', 'login');
            Route::get('/login/invitation', 'useInvitationCode');
            Route::get('getOtpForUser',  'getOtpForUser');
            Route::post('/social/register', 'socialRegister');
            Route::post('/register', 'register');
            Route::post('/logout', 'logout')->middleware('sanctum');
            Route::delete('delete-account', 'deleteAccount')->middleware('sanctum');
        });
        Route::controller(UserController::class)->group(function () {
            Route::post('/user-update', 'updateUserInfo')->middleware('sanctum');
            Route::get('/get-user-data', 'getUserInfo')->middleware('sanctum');
        });
        Route::controller(HomeController::class)->group(function () {
            Route::post('/rating/store', 'store')->middleware('sanctum');
            Route::get('/home', 'home');
            Route::get('/worker-detals/{id}', 'workerDetails');
            Route::get('/worker-by-category/{id}', 'getWorkerByCategoryId');
        });
        Route::controller(SettingPageController::class)->group(function () {
            Route::get('terms', 'termsPage');
            Route::get('about', 'aboutPage');
            Route::get('privacy', 'privacyPage');
            Route::post('sendOtp', 'sendOtp')->name('sendOtp');
        });

        Route::controller(ContactUsController::class)->group(function () {
            Route::post('/contact-us', 'store');
        });
        Route::controller(CategoryController::class)->group(function () {
            Route::get('/categories', 'index');
        });
        Route::controller(CountryController::class)->group(function () {
            Route::get('/countries', 'index');
            Route::get('/cities/{id}', 'cities');
        });
        Route::middleware('sanctum')->group(function () {
            Route::post('/reservations/store', [ReservationController::class, 'store'])->middleware('sanctum');
            Route::post('reservations/{id}/sign', [ReservationController::class, 'signReservation'])->middleware('sanctum');

            Route::post('/reservations/check-availability', [ReservationController::class, 'checkAvailability'])->middleware('sanctum');
            Route::post('/reservations/update', [ReservationController::class, 'updateReservation'])->middleware('sanctum');
            Route::get('/reservations/user/worker', [ReservationController::class, 'getUserWorkerReservations'])->middleware('sanctum');
            Route::get('/reservations', [ReservationController::class, 'index'])->middleware('sanctum');
            Route::get('/reservations/{id}', [ReservationController::class, 'show'])->middleware('sanctum');
            Route::patch('/reservations/{id}/status', [ReservationController::class, 'updateStatus'])->middleware('sanctum');
        });
        Route::controller(MassgeController::class)->group(function () {
            Route::get('/chats', 'index');
            Route::get('/chats/show', 'show');
            Route::get('/chats/create', 'createChat');
            Route::post('/chats/send', 'sendMessage');
            Route::post('/chats/mark-as-read', 'markAsRead');
            Route::get('/chats/unread-count',  'unreadChatsCount');
        });
        Route::controller(NotificationController::class)->group(function () {
            Route::get('/test-notification', 'sendNotficationTest')->middleware('sanctum');
            Route::get('/notifications', 'getUserNotifications')->middleware('sanctum');
            Route::get('/notifications/unread', 'getUnReadNotifications')->middleware('sanctum');
            Route::post('/notifications/{notification}/read', 'markAsRead')->middleware('sanctum');
            Route::post('/notifications/mark-all-read', 'markAllAsRead')->middleware('sanctum');
            Route::delete('/notifications/{notificationId}/delete',  'deleteNotification')->middleware('sanctum');
            Route::delete('/notifications/delete-all', 'deleteAllNotifications')->middleware('sanctum');
        });
    },
);
