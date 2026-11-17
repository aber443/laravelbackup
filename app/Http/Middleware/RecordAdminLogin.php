<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RecordAdminLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        $session = $request->session();
        $previousUserId = $session->get('user_id');
        $previousSessionId = $session->getId();

        $response = $next($request);
        $userId = $session->get('user_id');

        if (!$userId || $response->getStatusCode() >= 400
            || ((string) $previousUserId === (string) $userId && $previousSessionId === $session->getId())
        ) {
            return $response;
        }

        try {
            $user = User::find($userId);

            if ($user?->isAdmin()) {
                $user->newModelQuery()->whereKey($user->getKey())->toBase()->update([
                    'admin_last_login_at' => now(),
                ]);
            }
        } catch (Throwable $exception) {
            report($exception);
        }

        return $response;
    }
}