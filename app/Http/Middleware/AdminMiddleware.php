<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->session()->get('user_id');

        if (!$userId) {
            return redirect('/login');
        }

        $user = User::find($userId);

        if (!$user || !$user->isAdmin()) {
            $request->session()->forget('user_id');

            return redirect('/login')
                ->withErrors([
                    'email' => 'هذا الحساب غير مصرح له بالدخول إلى لوحة الإدارة.',
                ]);
        }

        return $next($request);
    }
}