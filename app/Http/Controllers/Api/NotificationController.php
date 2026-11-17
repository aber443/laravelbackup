<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // ==========================================
    // ABEER - Get Notifications
    // ==========================================

    public function index(Request $request)
    {
        $notifications = Notification::where(
            'user_id',
            $request->user()->user_id
        )
        ->orderBy('created_at', 'desc')
        ->get();

        return response()->json([
            'notifications' => $notifications,
        ]);
    }

    // ==========================================
    // ABEER - Mark Notification As Read
    // ==========================================

    public function markAsRead(Request $request, $notificationId)
    {
        $notification = Notification::where(
            'user_id',
            $request->user()->user_id
        )
        ->where('notification_id', $notificationId)
        ->firstOrFail();

        $notification->update([
            'is_read' => true,
        ]);

        return response()->json([
            'message' => 'Notification marked as read',
            'notification' => $notification,
        ]);
    }

    // ==========================================
    // ABEER - Mark All Notifications As Read
    // ==========================================

    public function markAllAsRead(Request $request)
    {
        Notification::where(
            'user_id',
            $request->user()->user_id
        )
        ->update([
            'is_read' => true,
        ]);

        return response()->json([
            'message' => 'All notifications marked as read',
        ]);
    }

    // ==========================================
    // ABEER - Delete Notification
    // ==========================================

    public function destroy(Request $request, $notificationId)
    {
        $notification = Notification::where(
            'user_id',
            $request->user()->user_id
        )
        ->where('notification_id', $notificationId)
        ->firstOrFail();

        $notification->delete();

        return response()->json([
            'message' => 'Notification deleted successfully',
        ]);
    }

    // ==========================================
    // Get Notification Settings
    // ==========================================

    public function getSettings(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'notifications_enabled' =>
                (bool) $user->notifications_enabled,
        ], 200);
    }

    // ==========================================
    // Update Notification Settings
    // ==========================================

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'notifications_enabled' => [
                'required',
                'boolean',
            ],
        ]);

        $user = $request->user();

        $user->notifications_enabled =
            $data['notifications_enabled'];

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث إعدادات الإشعارات بنجاح.',
            'notifications_enabled' =>
                (bool) $user->notifications_enabled,
        ], 200);
    }
}