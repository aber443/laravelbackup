<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReminderController extends Controller
{
    // =====================================
    // التحقق من صلاحية ولي الأمر
    // =====================================

    private function isAllowed(Request $request): bool
    {
        $user = $request->user();

        return $user !== null
            && $user->role === 'parent'
            && (bool) $user->is_active;
    }

    // =====================================
    // عرض تذكيرات ولي الأمر
    // GET /api/reminders
    // =====================================

    public function index(Request $request)
    {
        if (!$this->isAllowed($request)) {
            return response()->json([
                'success' => false,
                'message' => 'غير مسموح بعرض التذكيرات.',
            ], 403);
        }

        $reminders = Reminder::where(
            'user_id',
            $request->user()->user_id
        )
            ->orderBy('remind_at')
            ->get();

        return response()->json([
            'success' => true,
            'reminders' => $reminders,
        ], 200);
    }

    // =====================================
    // حفظ تذكير جديد
    // POST /api/reminders
    // =====================================

    public function store(Request $request)
    {
        if (!$this->isAllowed($request)) {
            return response()->json([
                'success' => false,
                'message' => 'غير مسموح بإضافة تذكير.',
            ], 403);
        }

        $data = $request->validate([
            'title' => [
                'required',
                'string',
                'min:2',
                'max:80',
            ],
            'remind_at' => [
                'required',
                'date',
            ],
            'is_active' => [
                'required',
                'boolean',
            ],
        ]);

        $remindAt = Carbon::parse(
            $data['remind_at']
        )->utc();

        if ($remindAt->lessThanOrEqualTo(now('UTC'))) {
            return response()->json([
                'success' => false,
                'message' => 'يجب اختيار وقت تذكير مستقبلي.',
            ], 422);
        }

        $reminder = Reminder::create([
            'user_id' => $request->user()->user_id,
            'title' => trim($data['title']),
            'remind_at' => $remindAt->format('Y-m-d H:i:s'),
            'is_active' => $data['is_active'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ التذكير بنجاح.',
            'reminder' => $reminder,
        ], 201);
    }

    // =====================================
    // حذف تذكير
    // DELETE /api/reminders/{reminderId}
    // =====================================

    public function destroy(
        Request $request,
        int $reminderId
    ) {
        if (!$this->isAllowed($request)) {
            return response()->json([
                'success' => false,
                'message' => 'غير مسموح بحذف التذكير.',
            ], 403);
        }

        $reminder = Reminder::where(
            'user_id',
            $request->user()->user_id
        )
            ->where('reminder_id', $reminderId)
            ->first();

        if (!$reminder) {
            return response()->json([
                'success' => false,
                'message' => 'التذكير غير موجود.',
            ], 404);
        }

        $reminder->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف التذكير بنجاح.',
        ], 200);
    }
}








