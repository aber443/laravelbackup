<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        abort_if($user === null, 401);
        abort_unless($user->role === 'parent', 403);

        $validated = $request->validate([
            'type' => ['required', 'in:inquiry,problem,suggestion,rating'],
            'message' => [
                'required_unless:type,rating',
                'nullable',
                'string',
                'max:2000',
            ],
            'rating' => [
                'exclude_unless:type,rating',
                'required',
                'integer',
                'between:1,5',
            ],
        ]);

        // حفظ تقييم التطبيق أو تحديث التقييم السابق
        if ($validated['type'] === 'rating') {

            $contactMessage = ContactMessage::query()
                ->where('user_id', $user->user_id)
                ->where('type', 'rating')
                ->orderByDesc('id')
                ->first();

            if ($contactMessage) {

                $contactMessage->rating = $validated['rating'];
                $contactMessage->save();

                return response()->json([
                    'message' => 'تم تحديث تقييمك بنجاح.',
                    'data' => $this->formatMessage($contactMessage),
                ], 200);
            }

            $contactMessage = new ContactMessage([
                'type' => 'rating',
                'rating' => $validated['rating'],
            ]);

            $contactMessage->user()->associate($user);
            $contactMessage->status = 'new';
            $contactMessage->save();

            return response()->json([
                'message' => 'تم حفظ تقييمك بنجاح.',
                'data' => $this->formatMessage($contactMessage),
            ], 201);
        }

        // الاستفسارات والاقتراحات وبلاغات المشاكل
        $contactMessage = new ContactMessage($validated);
        $contactMessage->user()->associate($user);
        $contactMessage->status = 'new';
        $contactMessage->save();

        return response()->json([
            'message' => 'تم إرسال رسالتك بنجاح.',
            'data' => $this->formatMessage($contactMessage),
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        abort_if($user === null, 401);
        abort_unless($user->role === 'parent', 403);

        $messages = ContactMessage::query()
            ->where('user_id', $user->user_id)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->through(
                fn (ContactMessage $message): array =>
                    $this->formatMessage($message)
            );

        return response()->json($messages);
    }

    private function formatMessage(ContactMessage $message): array
    {
        return [
            'id' => $message->id,
            'type' => $message->type,
            'message' => $message->message,
            'rating' => $message->rating,
            'status' => $message->status,
            'reply' => $message->reply,
            'created_at' => $message->created_at?->toIso8601String(),
            'replied_at' => $message->replied_at?->toIso8601String(),
        ];
    }
}