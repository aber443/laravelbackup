<?php

namespace App\Http\Middleware;

use App\Services\SystemLogService;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class LogApiRequests
{
    private const SECTION_PATH = '~^api/(?:children/[0-9]+/)?(glucose-readings|meals|activities|medications|reports|reminders)(?:/|$)~';

    protected SystemLogService $systemLogs;

    public function __construct(SystemLogService $systemLogs)
    {
        $this->systemLogs = $systemLogs;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        try {
            $response = $next($request);
            $statusCode = $response->getStatusCode();

            if ($this->shouldRecord($request, $statusCode)) {
                $duration = (int) round((microtime(true) - $startTime) * 1000);
                $this->recordRequest($request, $statusCode, $duration);
            }

            return $response;
        } catch (Throwable $exception) {
            $statusCode = $this->getExceptionStatusCode($exception);

            if ($this->shouldRecord($request, $statusCode)) {
                $duration = (int) round((microtime(true) - $startTime) * 1000);
                $this->recordRequest($request, $statusCode, $duration);
            }

            throw $exception;
        }
    }

    private function shouldRecord(Request $request, int $statusCode): bool
    {
        $path = trim($request->path(), '/');

        // AuthController يسجّل محاولات الدخول والتسجيل مباشرة.
        if (in_array($path, ['api/login', 'api/register'], true)) {
            return false;
        }

        if ($statusCode >= 400) {
            return true;
        }

        $method = strtoupper($request->method());

        if ($method === 'GET') {
            return $statusCode >= 200
                && $statusCode < 300
                && $this->sectionFor($path) !== null;
        }

        return in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true);
    }

    private function sectionFor(string $path): ?string
    {
        return preg_match(self::SECTION_PATH, $path, $matches) ? $matches[1] : null;
    }

    private function getExceptionStatusCode(Throwable $exception): int
    {
        if ($exception instanceof AuthenticationException) {
            return 401;
        }

        if ($exception instanceof AuthorizationException) {
            return 403;
        }

        if ($exception instanceof ValidationException) {
            return 422;
        }

        if ($exception instanceof ModelNotFoundException) {
            return 404;
        }

        if ($exception instanceof HttpExceptionInterface) {
            return $exception->getStatusCode();
        }

        return 500;
    }

    private function recordRequest(Request $request, int $statusCode, int $duration): void
    {
        if ($statusCode >= 500) {
            $severity = 'error';
            $action = 'api_request_server_error';
            $description = 'حدث خطأ في الخادم أثناء معالجة طلب API.';
        } elseif ($statusCode >= 400) {
            $severity = 'warning';
            $action = 'api_request_failed';
            $description = 'فشل تنفيذ طلب API.';
        } elseif ($statusCode >= 300) {
            $severity = 'info';
            $action = 'api_request_redirect';
            $description = 'تم تحويل طلب API.';
        } else {
            $severity = 'info';
            $action = 'api_request_success';
            $description = 'تم تنفيذ عملية API بنجاح.';
        }

        $section = $this->sectionFor(trim($request->path(), '/'));

        if ($section !== null) {
            $description .= ' [section:' . $section . ']';
        }

        $entry = [
            'user_id' => $request->user()?->user_id,
            'event_type' => 'api_request',
            'severity' => $severity,
            'action' => $action,
            'description' => $description,
        ];

        // استخدم حقول السجل الأساسية لطلبات القراءة الناجحة المضافة للرسم.
        if (strtoupper($request->method()) !== 'GET' || $statusCode >= 400) {
            $entry += [
                'source' => 'api',
                'method' => $request->method(),
                'url' => $request->path(),
                'status_code' => $statusCode,
                'duration_ms' => $duration,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ];
        }

        $this->systemLogs->log($entry);
    }
}