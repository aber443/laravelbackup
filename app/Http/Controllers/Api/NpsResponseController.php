<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NpsResponseController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        abort_unless($user?->role === 'parent', 403);

        $score = DB::table('nps_responses')
            ->where('user_id', $user->user_id)
            ->value('score');

        return response()->json([
            'score' => $score === null ? null : (int) $score,
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        abort_unless($user?->role === 'parent', 403);

        $validated = $request->validate([
            'score' => ['required', 'integer', 'between:0,10'],
        ]);

        $score = (int) $validated['score'];
        $now = now();

        DB::table('nps_responses')->upsert(
            [[
                'user_id' => $user->user_id,
                'score' => $score,
                'created_at' => $now,
                'updated_at' => $now,
            ]],
            ['user_id'],
            ['score', 'updated_at'],
        );

        return response()->json(['score' => $score]);
    }
}