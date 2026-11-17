<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DeviceTokenController extends Controller
{
    /**
     * تسجيل أو تحديث رمز جهاز ولي الأمر
     * POST /api/device-tokens
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if (
            !$user ||
            $user->role !== 'parent' ||
            !$user->is_active
        ) {
            return response()->json([
                'success' => false,
                'message' => 'غير مسموح بتسجيل هذا الجهاز.',
            ], 403);
        }

        $data = $request->validate([
            'fcm_token' => [
                'required',
                'string',
                'max:500',
            ],
            'platform' => [
                'required',
                Rule::in(['android', 'ios']),
            ],
        ]);

        DeviceToken::updateOrCreate(
            [
                'fcm_token' => $data['fcm_token'],
            ],
            [
                'user_id' => $user->user_id,
                'platform' => $data['platform'],
                'last_used_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الجهاز بنجاح.',
        ], 200);
    }
}