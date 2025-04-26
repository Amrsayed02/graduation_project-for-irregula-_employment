<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendFCMNotificationJob;
use App\Models\User;
use App\Notifications\UserMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

use Illuminate\Support\Facades\Notification as FacadesNotification;

class NotificationController extends Controller
{
    public function sendNotificationToUser(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required',
                'title' => 'required',
                'user_id' => 'required|exists:users,id',
            ]);
            $messageFromAdmin = $request->message;
            $titleFromAdmin = $request->title;
            $userId = $request->user_id;

            $user = User::find($userId);
            if (!$user) {
                session()->flash('Error', 'User not found');
                return errorResponse('User not found', 404);
            }

            Notification::send([$user],  new UserMessage($messageFromAdmin, $titleFromAdmin, $messageFromAdmin, $titleFromAdmin, "user", ""));

            if ($user->fcm) {
                SendFCMNotificationJob::dispatch($user->fcm, $titleFromAdmin, $messageFromAdmin);
            }

            return successmMssageResponse('Notification sent successfully', 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage(), 500);
        }
    }

    public function getUserNotifications(Request $request)
    {
        $countNotifications = $request->user->unreadnotifications->count();
        $notifications = $request->user->notifications;
        return response()->json([
            'status_code' => 200,
            'message' => "Successful",
            'notifications' => $notifications,
            'countUnreadNotifications' => $countNotifications,
        ], 200);
    }

    public function getUnReadNotifications(Request $request)
    {
        $countNotifications = $request->user->unreadnotifications->count();
        return response()->json([
            'status_code' => 200,
            'message' => 'Successful',
            'countNotifications' => $countNotifications,
        ], 200);
    }

    public function markAllAsRead(Request $request)
    {
        $request->user->unreadNotifications->markAsRead();

        return response()->json([
            'message' => 'All notifications are marked as read',
            'status_code' => 200,
        ], 200);
    }

    public function markAsRead($notificationId, Request $request)
    {
        $notification = $request->user->notifications->where('id', $notificationId)->first();

        if (!$notification) {
            return response()->json([
                'message' => 'No notifications found',
                'status_code' => 404,
            ], 404);
        }

        $notification->markAsRead();

        return response()->json([
            'status_code' => 200,
            'message' => 'Successful',
        ], 200);
    }

    public function deleteNotification(Request $request, $notificationId)
    {
        $user = $request->user;

        $notification = $user->notifications()->where('id', $notificationId)->first();

        if (!$notification) {
            return response()->json([
                'message' => 'No notifications found',
                'status_code' => 404,
            ], 404);
        }

        $notification->delete();

        return response()->json([
            'message' => 'The notification was successfully deleted',
            'status_code' => 200,
        ], 200);
    }

    public function deleteAllNotifications(Request $request)
    {
        $request->user->notifications()->delete();

        return response()->json([
            'message' => 'All notifications have been successfully deleted',
            'status_code' => 200,
        ], 200);
    }

    public function sendNotficationTest(Request $request)
    {
        try {
            $user = $request->user;
            FacadesNotification::send($user,  new UserMessage("You can access the features by paying for the package. Package price: 365 TL", "Bildirim Başlığı", "messageFromAdmin", "titleFromAdmin", "admin", "1"));
            // foreach ($users as $user) {
            if ($user->fcm) {
                SendFCMNotificationJob::dispatch(
                    $user->fcm,
                    "Package Payment",
                    "You can access the features by paying for the package. Package price: 365 TL",
                    [
                        "title" => "Notification Header",
                        "message" => "Notification sent successfully",
                        "title_en" => "Notification Header",
                        "message_en" => "Notification sent successfully",
                        "key" => "admin",
                        "keyId" => "1",
                    ]
                );
                // }
            }
            return response()->json([
                'message' => 'All notifications have been sent successfully',
                'status_code' => 200,
            ], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage(), 500);
        }
    }
}
