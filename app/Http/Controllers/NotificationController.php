<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Services\FCMNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $adminId = $request->session()->get('user_id');

        $receivedNotifications = Notification::where('user_id', $adminId)
            ->latest('created_at')
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->notification_id,
                    'title' => $notification->title,
                    'body' => $notification->message,
                    'type' => $notification->type,
                    'read' => (bool) $notification->is_read,
                    'date' => $notification->created_at?->format('Y-m-d H:i'),
                ];
            })
            ->values();

        $sentNotifications = Notification::with('user')
            ->where('type', 'admin_announcement')
            ->latest('created_at')
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->notification_id,
                    'title' => $notification->title,
                    'body' => $notification->message,
                    'recipient' => $notification->user?->name ?? 'غير معروف',
                    'date' => $notification->created_at?->format('Y-m-d H:i'),
                ];
            })
            ->values();

        return view('notifications', compact(
            'receivedNotifications',
            'sentNotifications'
        ));
    }

    public function markAsRead(Request $request, $notificationId)
    {
        $notification = Notification::where(
            'user_id',
            $request->session()->get('user_id')
        )
            ->where('notification_id', $notificationId)
            ->firstOrFail();

        $notification->update([
            'is_read' => true,
        ]);

        return redirect()->route('notifications.index')
            ->with('success', 'تم تحديد الإشعار كمقروء.');
    }

    public function markAllAsRead(Request $request)
    {
        Notification::where(
            'user_id',
            $request->session()->get('user_id')
        )->update([
            'is_read' => true,
        ]);

        return redirect()->route('notifications.index')
            ->with('success', 'تم تحديد جميع الإشعارات كمقروءة.');
    }

    public function destroy(Request $request, $notificationId)
    {
        $notification = Notification::where(
            'user_id',
            $request->session()->get('user_id')
        )
            ->where('notification_id', $notificationId)
            ->firstOrFail();

        $notification->delete();

        return redirect()->route('notifications.index')
            ->with('success', 'تم حذف الإشعار بنجاح.');
    }

    public function send(
        Request $request,
        FCMNotificationService $fcmService
    ) {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'message' => 'required|string|max:1000',
            'recipient_type' => 'required|in:all,specific',
            'recipient_phone' => 'required_if:recipient_type,specific|nullable|string|max:30',
        ]);

        $parentsQuery = User::where('role', 'parent');

        if ($validated['recipient_type'] === 'specific') {
            $parent = $parentsQuery
                ->where('phone', $validated['recipient_phone'])
                ->first();

            if (!$parent) {
                return back()
                    ->withErrors([
                        'recipient_phone' => 'رقم الهاتف غير مرتبط بحساب ولي أمر.',
                    ])
                    ->withInput();
            }

            $parents = collect([$parent]);
        } else {
            $parents = $parentsQuery
                ->where('is_active', true)
                ->get();
        }

        if ($parents->isEmpty()) {
            return back()->withErrors([
                'recipient_type' => 'لا يوجد أولياء أمور لاستلام الإشعار.',
            ]);
        }

        $dispatchId = (string) Str::uuid();
        $recipientScope = $validated['recipient_type'];

        $successCount = 0;
        $failedCount = 0;
        $noDeviceCount = 0;

        foreach ($parents as $parent) {
            $result = $fcmService->sendToUser(
                (int) $parent->user_id,
                $validated['title'],
                $validated['message'],
                [],
                'admin_announcement',
                $dispatchId,
                $recipientScope
            );

            $successCount += $result['success'];
            $failedCount += $result['failed'];

            if ($result['total'] === 0) {
                $noDeviceCount++;
            }
        }

        $message = 'تم حفظ الإشعار بنجاح وإرساله إلى '
            . $parents->count()
            . ' من أولياء الأمور.';

        $message .= ' الأجهزة التي استقبلت الإشعار: '
            . $successCount . '.';

        if ($failedCount > 0) {
            $message .= ' فشل الإرسال إلى '
                . $failedCount . ' جهاز.';
        }

        if ($noDeviceCount > 0) {
            $message .= ' عدد أولياء الأمور الذين لا يملكون جهازاً مسجلاً: '
                . $noDeviceCount . '.';
        }

        return redirect()->route('notifications.index')
            ->with('success', $message);
    }
}
