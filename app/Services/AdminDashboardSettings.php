<?php

namespace App\Services;

use App\Models\SystemLog;
use App\Models\User;

class AdminDashboardSettings
{
    public const ALERTS = [
        'technical_errors' => 'الأخطاء التقنية',
        'security_alerts' => 'التنبيهات الأمنية',
        'new_accounts' => 'تسجيل مستخدم جديد',
        'user_messages' => 'رسائل المستخدمين',
    ];

    public function preferences(User $user): array
    {
        $stored = json_decode($user->getRawOriginal('admin_alert_preferences') ?? '{}', true);

        return array_replace(
            array_fill_keys(array_keys(self::ALERTS), true),
            array_intersect_key((array) $stored, self::ALERTS)
        );
    }

    public function savePreferences(User $user, array $preferences): void
    {
        $user->newModelQuery()->whereKey($user->getKey())->toBase()->update([
            'admin_alert_preferences' => json_encode($preferences, JSON_THROW_ON_ERROR),
        ]);
    }

    public function alertsFor(User $user): array
    {
        $enabled = array_filter($this->preferences($user));
        $since = now()->subDays(7);
        $logs = [
            'technical_errors' => 'error',
            'security_alerts' => 'security',
        ];
        $enabledLogs = array_intersect_key($logs, $enabled);

        $eventCounts = $enabledLogs
            ? SystemLog::query()
                ->whereIn('event_type', array_values($enabledLogs))
                ->where('occurred_at', '>=', $since)
                ->selectRaw('event_type, COUNT(*) AS total')
                ->groupBy('event_type')
                ->pluck('total', 'event_type')
            : collect();

        $alerts = [];
        foreach ($enabledLogs as $key => $eventType) {
            $alerts[$key] = (int) $eventCounts->get($eventType, 0);
        }

        if (isset($enabled['new_accounts'])) {
            $alerts['new_accounts'] = User::query()
                ->where('role', 'parent')
                ->where('created_at', '>=', $since)
                ->count();
        }

        if (isset($enabled['user_messages'])) {
            $alerts['user_messages'] = $user->getConnection()
                ->table('contact_messages')
                ->where('created_at', '>=', $since)
                ->count();
        }

        return $alerts;
    }
}