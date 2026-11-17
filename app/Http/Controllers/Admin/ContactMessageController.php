<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(Request $request): View
    {
        // أنواع الرسائل المسموح بعرضها في صفحة رسائل المستخدمين
        $typeLabels = [
            'inquiry' => 'استفسار',
            'problem' => 'الإبلاغ عن مشكلة',
            'suggestion' => 'اقتراح',
        ];

        $statusLabels = [
            'new' => 'جديدة',
            'reviewed' => 'تمت المراجعة',
            'replied' => 'تم الرد',
        ];

        $validated = $request->validate([
            'type' => ['nullable', Rule::in(array_keys($typeLabels))],
        ]);

        $selectedType = $validated['type'] ?? null;

        // عرض الرسائل فقط، واستبعاد تقييمات التطبيق
        $messages = ContactMessage::query()
            ->with('user:user_id,name')
            ->whereIn('type', array_keys($typeLabels))
            ->when(
                $selectedType,
                fn ($query, $type) => $query->where('type', $type)
            )
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.contact-messages.index', compact(
            'messages',
            'selectedType',
            'typeLabels',
            'statusLabels'
        ));
    }

    public function review(ContactMessage $contactMessage): RedirectResponse
    {
        // منع مراجعة التقييمات من صفحة الرسائل
        if ($contactMessage->type === 'rating') {
            return back()->with(
                'contact_error',
                'تقييمات التطبيق تُعرض في صفحة قياس رضا المستخدمين.'
            );
        }

        $updated = ContactMessage::query()
            ->whereKey($contactMessage->getKey())
            ->where('status', 'new')
            ->update([
                'status' => 'reviewed',
            ]);

        if ($updated === 0) {
            return back()->with(
                'contact_error',
                'هذه الرسالة عولجت من قبل.'
            );
        }

        return back()->with(
            'contact_success',
            'تمت مراجعة الرسالة.'
        );
    }

    public function reply(
        Request $request,
        ContactMessage $contactMessage
    ): RedirectResponse {

        // الرد متاح للاستفسارات والمشاكل والاقتراحات فقط
        if (!in_array(
            $contactMessage->type,
            ['inquiry', 'problem', 'suggestion'],
            true
        )) {
            return back()->with(
                'contact_error',
                'لا يمكن الرد على تقييمات التطبيق.'
            );
        }

        $validated = $request->validate([
            'reply' => ['required', 'string', 'max:2000'],
        ], [
            'reply.required' => 'اكتبي الرد قبل الحفظ.',
            'reply.max' => 'الرد يجب ألا يتجاوز ٢٠٠٠ حرف.',
        ]);

        $updated = ContactMessage::query()
            ->whereKey($contactMessage->getKey())
            ->whereIn('status', ['new', 'reviewed'])
            ->whereNull('reply')
            ->update([
                'reply' => $validated['reply'],
                'status' => 'replied',
                'replied_at' => now(),
            ]);

        if ($updated === 0) {
            return back()->with(
                'contact_error',
                'سبق الرد على هذه الرسالة.'
            );
        }

        return back()->with(
            'contact_success',
            'تم حفظ الرد.'
        );
    }
}