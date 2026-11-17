<?php
namespace App\Services;

use App\Models\DeviceToken;
use App\Models\SystemHealthCheck;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Kreait\Firebase\Contract\Messaging;
use InvalidArgumentException;
use Throwable;

class SystemHealthCheckService
{
    /**
     * تنفيذ جميع فحوصات النظام.
     */
    public function runAll(): array
    {
        $checkKeys = [
            'database_connection',
            'api_health',
            'scheduler_status',
            'firebase_fcm',
            'required_tables',
            'notification_tables',
            'reports_table',
        ];

        return array_map(
            fn (string $key) => $this->runOne($key),
            $checkKeys
        );
    }

    /**
     * تنفيذ فحص واحد حسب المفتاح.
     */
    public function runOne(string $checkKey): array
    {
        return match ($checkKey) {
            'database_connection' => $this->checkDatabase(),

            'api_health' => $this->checkApi(),

            'scheduler_status' => $this->checkScheduler(),

            'firebase_fcm' => $this->checkFirebase(),

            'required_tables' => $this->checkDataIntegrity(),

            'notification_tables' => $this->checkNotifications(),

            'reports_table' => $this->checkReports(),

            default => throw new InvalidArgumentException(
                'مفتاح الفحص غير معروف.'
            ),
        };
    }

    /**
     * 1. فحص اتصال قاعدة البيانات.
     */
    private function checkDatabase(): array
    {
        return $this->executeCheck(
            'service_connectivity',
            'database_connection',
            function () {
                DB::select('SELECT 1 AS connection_test');

                return [
                    'status' => 'passed',
                    'message' => 'اتصال قاعدة البيانات يعمل بنجاح.',
                    'details' => [
                        'database' => DB::connection()->getDatabaseName(),
                        'connection' => 'connected',
                    ],
                ];
            }
        );
    }

    /**
     * 2. التحقق من تسجيل مسار API داخلياً.
     */
    private function checkApi(): array
    {
        return $this->executeCheck(
            'service_connectivity',
            'api_health',
            function () {
                $route = Route::getRoutes()->getByName('api.health');

                $registered = $route !== null
                    && in_array('GET', $route->methods(), true)
                    && $route->uri() === 'api/health';

                return [
                    'status' => $registered ? 'passed' : 'failed',

                    'message' => $registered
                        ? 'مسار فحص API مسجل في Laravel.'
                        : 'مسار فحص API غير مسجل بالشكل المتوقع.',

                    'details' => [
                        'check_type' => 'route_registration',
                        'route_name' => 'api.health',
                        'expected_method' => 'GET',
                        'expected_uri' => 'api/health',
                        'route_registered' => $registered,
                    ],
                ];
            }
        );
    }

    /**
     * 3. فحص آخر تنفيذ فعلي لـ Scheduler.
     */
    private function checkScheduler(): array
    {
        return $this->executeCheck(
            'service_connectivity',
            'scheduler_status',
            function () {
                $lastExecution = SystemHealthCheck::query()
                    ->where('check_key', 'scheduler_service')
                    ->orderByDesc('checked_at')
                    ->orderByDesc('id')
                    ->first();

                if (!$lastExecution) {
                    return [
                        'status' => 'warning',
                        'message' => 'لم يتم تسجيل تنفيذ Scheduler بعد.',
                        'details' => [],
                    ];
                }

                $lastCheckedAt = Carbon::parse(
                    $lastExecution->checked_at
                );

                $isRecent = $lastCheckedAt->greaterThanOrEqualTo(
                    now()->subMinutes(2)
                );

                $lastDetails = $lastExecution->details ?? [];

                if (!$isRecent) {
                    return [
                        'status' => 'warning',
                        'message' => 'لم يتم تنفيذ Scheduler خلال آخر دقيقتين.',
                        'details' => [
                            'last_execution_at' => $lastCheckedAt->toDateTimeString(),
                            'last_execution_status' => $lastExecution->status,
                            'last_execution_details' => $lastDetails,
                        ],
                    ];
                }

                if ($lastExecution->status !== 'passed') {
                    return [
                        'status' => 'failed',
                        'message' => 'آخر تنفيذ لـ Scheduler انتهى بأخطاء.',
                        'details' => [
                            'last_execution_at' => $lastCheckedAt->toDateTimeString(),
                            'last_execution_details' => $lastDetails,
                        ],
                    ];
                }

                return [
                    'status' => 'passed',
                    'message' => 'Scheduler يعمل وينفذ مهامه بنجاح.',
                    'details' => [
                        'last_execution_at' => $lastCheckedAt->toDateTimeString(),
                        'last_execution_status' => $lastExecution->status,
                        'last_execution_details' => $lastDetails,
                    ],
                ];
            }
        );
    }

    /**
     * 4. فحص Firebase FCM دون إرسال إشعار.
     */
    private function checkFirebase(): array
    {
        return $this->executeCheck(
            'service_connectivity',
            'firebase_fcm',
            function () {
                $project = config('firebase.default');

                $configurationKey = "firebase.projects.{$project}.credentials";

                $configuredPath = config($configurationKey);

                $credentialsPath = filled($configuredPath)
                    ? (string) $configuredPath
                    : null;

                if (
                    $credentialsPath !== null
                    && !is_file($credentialsPath)
                ) {
                    $credentialsPath = base_path($credentialsPath);
                }

                if (
                    $credentialsPath === null
                    || !is_file($credentialsPath)
                ) {
                    return [
                        'status' => 'failed',
                        'message' => 'ملف اعتماد Firebase غير موجود.',
                        'details' => [
                            'credentials_configured' => filled($configuredPath),
                        ],
                    ];
                }

                config([
                    $configurationKey => realpath($credentialsPath)
                        ?: $credentialsPath,
                ]);

                $tokens = DeviceToken::query()
                    ->whereNotNull('fcm_token')
                    ->where('fcm_token', '!=', '')
                    ->limit(500)
                    ->pluck('fcm_token')
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();

                if (empty($tokens)) {
                    return [
                        'status' => 'warning',
                        'message' => 'Firebase مهيأ، ولكن لا توجد أجهزة مسجلة لفحصها.',
                        'details' => [
                            'project' => $project,
                            'registered_devices' => 0,
                        ],
                    ];
                }

                $result = app(Messaging::class)
                    ->validateRegistrationTokens($tokens);

                $valid = count($result['valid'] ?? []);
                $unknown = count($result['unknown'] ?? []);
                $invalid = count($result['invalid'] ?? []);

                $status = (
                    $unknown > 0
                    || $invalid > 0
                    || $valid === 0
                ) ? 'warning' : 'passed';

                return [
                    'status' => $status,

                    'message' => $status === 'passed'
                        ? 'Firebase FCM يستجيب وجميع رموز الأجهزة صالحة.'
                        : 'Firebase يستجيب، لكن بعض رموز الأجهزة تحتاج مراجعة.',

                    'details' => [
                        'project' => $project,
                        'registered_devices' => count($tokens),
                        'valid_devices' => $valid,
                        'unknown_devices' => $unknown,
                        'invalid_devices' => $invalid,
                    ],
                ];
            }
        );
    }

    /**
     * 5. فحص سلامة البيانات.
     */
    private function checkDataIntegrity(): array
    {
        return $this->executeCheck(
            'data_integrity',
            'required_tables',
            function () {
                $requiredTables = [
                    'children',
                    'meals',
                    'glucose_readings',
                    'activities',
                    'medications',
                    'medical_exams',
                ];

                $missingTables = [];

                foreach ($requiredTables as $table) {
                    if (!Schema::hasTable($table)) {
                        $missingTables[] = $table;
                    }
                }

                if (!empty($missingTables)) {
                    return [
                        'status' => 'failed',
                        'message' => 'بعض جداول البيانات الأساسية غير موجودة.',
                        'details' => [
                            'missing_tables' => $missingTables,
                        ],
                    ];
                }

                return [
                    'status' => 'passed',
                    'message' => 'جميع جداول البيانات الصحية الأساسية موجودة.',
                    'details' => [
                        'checked_tables' => $requiredTables,
                        'count' => count($requiredTables),
                    ],
                ];
            }
        );
    }

    /**
     * 6. فحص جداول الإشعارات والأجهزة.
     */
    private function checkNotifications(): array
    {
        return $this->executeCheck(
            'notifications',
            'notification_tables',
            function () {
                $requiredTables = [
                    'notifications',
                    'device_tokens',
                ];

                $missingTables = [];

                foreach ($requiredTables as $table) {
                    if (!Schema::hasTable($table)) {
                        $missingTables[] = $table;
                    }
                }

                if (!empty($missingTables)) {
                    return [
                        'status' => 'failed',
                        'message' => 'بعض جداول الإشعارات غير موجودة.',
                        'details' => [
                            'missing_tables' => $missingTables,
                        ],
                    ];
                }

                return [
                    'status' => 'passed',
                    'message' => 'جداول الإشعارات وأجهزة المستخدمين متاحة.',
                    'details' => [
                        'notifications_count' => DB::table('notifications')->count(),
                        'registered_devices_count' => DB::table('device_tokens')->count(),
                    ],
                ];
            }
        );
    }

    /**
     * 7. فحص سلامة التقارير.
     */
    private function checkReports(): array
    {
        return $this->executeCheck(
            'reports',
            'reports_table',
            function () {
                if (!Schema::hasTable('reports')) {
                    return [
                        'status' => 'failed',
                        'message' => 'جدول التقارير غير موجود.',
                        'details' => [
                            'table' => 'reports',
                        ],
                    ];
                }

                $requiredColumns = [
                    'report_id',
                    'child_id',
                    'report_type',
                    'report_data',
                    'generated_at',
                ];

                $missingColumns = [];

                foreach ($requiredColumns as $column) {
                    if (!Schema::hasColumn('reports', $column)) {
                        $missingColumns[] = $column;
                    }
                }

                if (!empty($missingColumns)) {
                    return [
                        'status' => 'failed',
                        'message' => 'بعض أعمدة التقارير الأساسية غير موجودة.',
                        'details' => [
                            'missing_columns' => $missingColumns,
                        ],
                    ];
                }

                return [
                    'status' => 'passed',
                    'message' => 'جدول التقارير وأعمدته الأساسية متاحة.',
                    'details' => [
                        'reports_count' => DB::table('reports')->count(),
                        'checked_columns' => $requiredColumns,
                    ],
                ];
            }
        );
    }

    /**
     * تنفيذ الفحص وحفظ نتيجته.
     */
    private function executeCheck(
        string $category,
        string $checkKey,
        callable $callback
    ): array {
        try {
            $result = $callback();
        } catch (Throwable $exception) {
            report($exception);

            $result = [
                'status' => 'failed',
                'message' => 'حدث خطأ أثناء تنفيذ الفحص.',
                'details' => [
                    'error_type' => get_class($exception),
                ],
            ];
        }

        $check = SystemHealthCheck::create([
            'category' => $category,
            'check_key' => $checkKey,
            'status' => $result['status'],
            'message' => $result['message'],
            'details' => $result['details'] ?? [],
            'checked_at' => now(),
        ]);

        return [
            'id' => $check->id,
            'category' => $check->category,
            'check_key' => $check->check_key,
            'status' => $check->status,
            'message' => $check->message,
            'details' => $check->details,
            'checked_at' => $check->checked_at,
        ];
    }
}
