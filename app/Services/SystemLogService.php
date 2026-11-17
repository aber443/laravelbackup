<?php

namespace App\Services;

use App\Models\SystemLog;
use Throwable;

class SystemLogService
{
    /**
     * تسجيل حدث جديد في النظام.
     */
    public function log(array $data): ?SystemLog
    {
        try {
            $request = request();
            $authenticatedUser = $request->user();
            $sessionUserId = $request->hasSession()
                ? $request->session()->get('user_id')
                : null;

            $userId = $data['user_id']
                ?? ($authenticatedUser?->user_id ?? $sessionUserId);

            $metadata = $data['metadata'] ?? null;
            $metadata = is_array($metadata)
                ? $this->sanitizeMetadata($metadata)
                : null;

            $attributes = [
                'user_id' => $userId,
                'event_type' => $data['event_type'] ?? 'user_activity',
                'severity' => $data['severity'] ?? 'info',
                'action' => $data['action'] ?? 'system_event',
                'description' => $data['description'] ?? null,
                'source' => $data['source'] ?? 'system',
                'method' => $data['method'] ?? $request->method(),
                'url' => $data['url'] ?? $request->path(),
                'status_code' => $data['status_code'] ?? null,
                'duration_ms' => $data['duration_ms'] ?? null,
                'ip_address' => $data['ip_address'] ?? $request->ip(),
                'user_agent' => $data['user_agent'] ?? $request->userAgent(),
                'metadata' => $metadata,
                'occurred_at' => $data['occurred_at'] ?? now(),
            ];

            $log = new SystemLog();
            $columns = $log->getConnection()
                ->getSchemaBuilder()
                ->getColumnListing($log->getTable());

            return $log->newQuery()->create(
                array_intersect_key($attributes, array_flip($columns))
            );
        } catch (Throwable $exception) {
            // يستمر الطلب إذا فشل تسجيل الحدث.
            report($exception);

            return null;
        }
    }

    /**
     * إزالة المعلومات الحساسة من البيانات الإضافية.
     */
    private function sanitizeMetadata(array $metadata): array
    {
        $blockedKeys = [
            'password',
            'token',
            'authorization',
            'secret',
            'cookie',
            'otp',
            'verificationcode',
            'apikey',
        ];

        $cleanMetadata = [];

        foreach ($metadata as $key => $value) {
            $normalizedKey = strtolower(
                str_replace(['_', '-', ' '], '', (string) $key)
            );

            foreach ($blockedKeys as $blockedKey) {
                if (str_contains($normalizedKey, $blockedKey)) {
                    continue 2;
                }
            }

            if (is_array($value)) {
                $cleanMetadata[$key] = $this->sanitizeMetadata($value);
            } elseif (is_scalar($value) || is_null($value)) {
                $cleanMetadata[$key] = $value;
            }
        }

        return $cleanMetadata;
    }
}