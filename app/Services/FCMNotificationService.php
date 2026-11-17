<?php

namespace App\Services;

use App\Models\User;
use App\Models\DeviceToken;
use App\Models\Notification as AppNotification;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Throwable;

class FCMNotificationService
{
    protected Messaging $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    /**
     * إذا كانت الإشعارات مفعّلة، احفظ الإشعار وأرسله عبر Firebase.
     */
    public function sendToUser(
        int $userId,
        string $title,
        string $body,
        array $data = [],
        string $type = 'general',
        ?string $dispatchId = null,
        ?string $recipientScope = null
    ): array {
        $user = User::find($userId);

        if (!$user || !$user->notifications_enabled) {
            return [
                'notification_id' => null,
                'success' => 0,
                'failed' => 0,
                'total' => 0,
                'saved' => false,
                'skipped' => true,
            ];
        }

        $notification = new AppNotification([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $body,
            'is_read' => false,
        ]);

        if ($dispatchId !== null) {
            $notification->dispatch_id = $dispatchId;
            $notification->recipient_scope = $recipientScope;
        }

        $notification->save();

        $data['notification_id'] = $notification->notification_id;
        $data['type'] = $type;

        $messageData = [];
        foreach ($data as $key => $value) {
            $messageData[$key] = (string) $value;
        }

        $devices = DeviceToken::where('user_id', $userId)->get();
        $successCount = 0;
        $failureCount = 0;

        foreach ($devices as $device) {
            try {
                $message = CloudMessage::new()
                    ->withTarget('token', $device->fcm_token)
                    ->withNotification(FirebaseNotification::create($title, $body))
                    ->withData($messageData);

                $this->messaging->send($message);
                $successCount++;
            } catch (Throwable $error) {
                $failureCount++;

                Log::error('Firebase notification sending failed.', [
                    'notification_id' => $notification->notification_id,
                    'user_id' => $userId,
                    'device_token_id' => $device->device_token_id,
                    'error' => $error->getMessage(),
                ]);
            }
        }

        return [
            'notification_id' => $notification->notification_id,
            'success' => $successCount,
            'failed' => $failureCount,
            'total' => $devices->count(),
            'saved' => true,
            'skipped' => false,
        ];
    }
}
