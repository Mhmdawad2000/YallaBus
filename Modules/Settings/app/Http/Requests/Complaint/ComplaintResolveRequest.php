<?php

namespace Modules\Settings\Http\Requests\Complaint;

use Illuminate\Foundation\Http\FormRequest;

class ComplaintResolveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ضيف صلاحيات حسب نظامك (مثلاً admin فقط)
    }

    public function rules(): array
    {
        return [
            'status'     => 'required|in:resolved,closed',
            'resolution' => 'nullable|string|max:1000',
        ];
    }

    /**
     * رسائل التحقق المخصصة
     */
    public function messages(): array
    {
        return [
            'status.required'   => 'حقل حالة الشكوى مطلوب.',
            'status.in'         => 'يجب أن تكون حالة الشكوى إما محلولة أو مغلقة.',
            'resolution.string' => 'يجب أن يكون الحل نصًا صحيحًا.',
            'resolution.max'    => 'يجب ألا يزيد الحل عن 1000 حرف.',
        ];
    }
}
