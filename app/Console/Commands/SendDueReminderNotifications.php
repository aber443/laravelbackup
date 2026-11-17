<?php
namespace App\Console\Commands;

use App\Models\Reminder;
use App\Models\SystemHealthCheck;
use App\Services\FCMNotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class SendDueReminderNotifications extends Command
{
    protected $signature = 'app:send-due-reminder-notifications';

    protected $description = 'Send notifications for due reminders';

    public function handle(
        FCMNotificationService $fcmService
    ): int {

        $now = now('UTC');

        $reminderIds = Reminder::where('is_active', true)
            ->where('remind_at', '<=', $now)
            ->whereNull('last_sent_at')
            ->pluck('reminder_id');

        $sentCount = 0;
        $skippedCount = 0;
        $noDeviceCount = 0;
        $failedCount = 0;

        foreach ($reminderIds as $reminderId) {

            try {

                DB::transaction(function () use (
                    $reminderId,
                    $fcmService,
                    &$sentCount,
                    &$skippedCount,
                    &$noDeviceCount
                ) {

                    $reminder = Reminder::where(
                        'reminder_id',
                        $reminderId
                    )
                        ->lockForUpdate()
                        ->first();

                    if (
                        !$reminder ||
                        !$reminder->is_active ||
                        $reminder->last_sent_at !== null
                    ) {
                        return;
                    }

                    $remindAtUtc = Carbon::parse(
                        $reminder->getRawOriginal('remind_at'),
                        'UTC'
                    );

                    if ($remindAtUtc->isFuture()) {
                        return;
                    }

                    $result = $fcmService->sendToUser(
                        (int) $reminder->user_id,
                        $reminder->title,
                        'حان موعد التذكير: ' . $reminder->title,
                        [
                            'reminder_id' => $reminder->reminder_id,
                        ],
                        'reminder'
                    );

                    if (($result['skipped'] ?? false) === true) {

                        $reminder->last_sent_at = now('UTC');
                        $reminder->save();

                        $skippedCount++;

                        $this->warn(
                            "Reminder {$reminderId}: notifications disabled."
                        );

                        return;
                    }

                    if (!($result['saved'] ?? false)) {
                        throw new RuntimeException(
                            'Notification was not saved.'
                        );
                    }

                    $total = (int) ($result['total'] ?? 0);
                    $success = (int) ($result['success'] ?? 0);
                    $failed = (int) ($result['failed'] ?? 0);

                    if ($total === 0) {

                        $reminder->last_sent_at = now('UTC');
                        $reminder->save();

                        $noDeviceCount++;

                        $this->warn(
                            "Reminder {$reminderId}: saved in app, no registered devices."
                        );

                        return;
                    }

                    if ($success === 0) {
                        throw new RuntimeException(
                            'Firebase failed to send notification to all devices.'
                        );
                    }

                    $reminder->last_sent_at = now('UTC');
                    $reminder->save();

                    $sentCount++;

                    $this->info(
                        "Reminder {$reminderId}: " .
                        "Firebase success = {$success}, " .
                        "failed = {$failed}"
                    );

                    if ($failed > 0) {
                        $this->warn(
                            "Reminder {$reminderId}: some devices failed."
                        );
                    }
                });

            } catch (Throwable $error) {

                $failedCount++;

                $this->error(
                    "Reminder {$reminderId} failed: " .
                    $error->getMessage()
                );
            }
        }

        $this->info(
            "Successfully processed: {$sentCount}"
        );

        $this->info(
            "Skipped: {$skippedCount}"
        );

        $this->info(
            "No registered devices: {$noDeviceCount}"
        );

        $this->info(
            "Failed: {$failedCount}"
        );

        // حفظ آخر نتيجة فعلية لتنفيذ Scheduler.
        SystemHealthCheck::updateOrCreate(
            [
                'check_key' => 'scheduler_service',
            ],
            [
                'category' => 'service_connectivity',
                'status' => $failedCount > 0 ? 'failed' : 'passed',
                'message' => $failedCount > 0
                    ? 'تم تنفيذ المجدول مع وجود أخطاء في معالجة بعض التذكيرات.'
                    : 'تم تنفيذ المجدول بنجاح.',
                'details' => [
                    'sent_count' => $sentCount,
                    'skipped_count' => $skippedCount,
                    'no_device_count' => $noDeviceCount,
                    'failed_count' => $failedCount,
                ],
                'checked_at' => now(),
            ]
        );

        return $failedCount > 0
            ? self::FAILURE
            : self::SUCCESS;
    }
}
