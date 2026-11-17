<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SentNotificationController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate(['q' => ['nullable', 'string', 'max:100']]);
        $search = trim($filters['q'] ?? '');

        $dispatches = DB::table('notifications')
            ->where('type', 'admin_announcement')
            ->selectRaw('MAX(notification_id) AS notification_id')
            ->groupByRaw("CASE WHEN dispatch_id IS NULL OR dispatch_id = '' THEN CONCAT('single:', notification_id) ELSE CONCAT('batch:', dispatch_id) END");

        $notifications = DB::query()->fromSub($dispatches, 'dispatches')
            ->join('notifications as notice', 'notice.notification_id', '=', 'dispatches.notification_id')
            ->leftJoin('users as parent', 'parent.user_id', '=', 'notice.user_id')
            ->select('notice.title', 'notice.message', 'notice.created_at', 'notice.recipient_scope', 'parent.name as recipient_name')
            ->when($search !== '', function ($query) use ($search) {
                $pattern = $this->containsPattern($search);
                $query->where(function ($matches) use ($pattern) {
                    $matches->whereRaw("notice.title LIKE ? ESCAPE '!'", [$pattern])
                        ->orWhereRaw("parent.name LIKE ? ESCAPE '!'", [$pattern])
                        ->orWhereExists(function ($recipients) use ($pattern) {
                            $recipients->selectRaw('1')
                                ->from('notifications as delivered')
                                ->join('users as recipient', 'recipient.user_id', '=', 'delivered.user_id')
                                ->where('delivered.type', 'admin_announcement')
                                ->whereColumn('delivered.dispatch_id', 'notice.dispatch_id')
                                ->where('delivered.dispatch_id', '<>', '')
                                ->whereRaw("recipient.name LIKE ? ESCAPE '!'", [$pattern]);
                        });
                });
            })
            ->orderByDesc('notice.created_at')
            ->orderByDesc('notice.notification_id')
            ->paginate(4)
            ->withQueryString();

        $groups = $notifications->getCollection()->groupBy(
            fn ($notice) => $notice->created_at ? Carbon::parse($notice->created_at)->toDateString() : ''
        );

        return view('notifications', compact('notifications', 'groups', 'search'));
    }

    public function create(Request $request)
    {
        $selectedId = filter_var($request->old('recipient_user_id'), FILTER_VALIDATE_INT);
        $selectedParent = $selectedId
            ? $this->eligibleParents()->where('user_id', $selectedId)->first(['user_id', 'name'])
            : null;

        return view('notifications-create', compact('selectedParent'));
    }

    public function recipients(Request $request)
    {
        $validated = $request->validate(['q' => ['required', 'string', 'min:2', 'max:100']]);
        $parents = $this->eligibleParents()
            ->whereRaw("name LIKE ? ESCAPE '!'", [$this->containsPattern($validated['q'])])
            ->orderBy('name')
            ->orderBy('user_id')
            ->limit(10)
            ->get(['user_id', 'name']);

        return response()->json($parents);
    }

    public function send(Request $request, NotificationController $sender)
    {
        $validated = $request->validate([
            'recipient_type' => ['required', 'in:all,specific'],
            'recipient_user_id' => ['nullable', 'required_if:recipient_type,specific', 'integer', 'min:1'],
        ]);

        $phone = null;
        if ($validated['recipient_type'] === 'specific') {
            $phone = $this->eligibleParents()
                ->where('user_id', $validated['recipient_user_id'])
                ->value('phone');

            if (!$phone || DB::table('users')->where('role', 'parent')->where('phone', $phone)->count() !== 1) {
                throw ValidationException::withMessages([
                    'recipient_user_id' => 'تعذّر تحديد المستلم. اختاري حساب ولي أمر آخر.',
                ]);
            }
        }

        $request->merge(['recipient_phone' => $phone]);

        $response = app()->call([$sender, 'send']);

        if ($response instanceof \Illuminate\Http\RedirectResponse && $request->session()->has('success')) {
            return redirect()->route('notifications.index');
        }

        return $response;
    }

    private function eligibleParents()
    {
        return DB::table('users')
            ->where('role', 'parent')
            ->where('is_active', true)
            ->whereNotNull('phone')
            ->where('phone', '<>', '');
    }

    private function containsPattern(string $value): string
    {
        return '%' . str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $value) . '%';
    }
}
