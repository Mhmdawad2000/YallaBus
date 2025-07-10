<?php

namespace Modules\Settings\Http\Requests\Complaint;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $complaintId = $this->route('id');

        return [
            // 'user_id' => ['required', 'exists:users,id'],
            'booking_id' => ['nullable', 'exists:bookings,id'],
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'status' => ['required', 'string', 'in:pending,resolved,rejected'],
            // 'resolution' => ['nullable', 'string', 'required_if:status,resolved'],
            // 'resolved_by' => ['nullable', 'exists:users,id', 'required_if:status,resolved'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'معرف المستخدم مطلوب.',
            'user_id.exists' => 'المستخدم المحدد غير موجود.',

            'booking_id.exists' => 'الحجز المحدد غير موجود.',

            'subject.required' => 'عنوان الشكوى مطلوب.',
            'subject.string' => 'عنوان الشكوى يجب أن يكون نصاً.',
            'subject.max' => 'عنوان الشكوى لا يجب أن يتجاوز 255 حرفاً.',

            'description.required' => 'وصف الشكوى مطلوب.',
            'description.string' => 'وصف الشكوى يجب أن يكون نصاً.',

            'status.required' => 'حالة الشكوى مطلوبة.',
            'status.string' => 'حالة الشكوى يجب أن تكون نصاً.',
            'status.in' => 'حالة الشكوى يجب أن تكون واحدة من: pending, resolved, rejected.',

            'resolution.string' => 'حل الشكوى يجب أن يكون نصاً.',
            'resolution.required_if' => 'حل الشكوى مطلوب عند تحديد الحالة كـ resolved.',

            'resolved_by.exists' => 'المسؤول المحدد غير موجود.',
            'resolved_by.required_if' => 'المسؤول مطلوب عند تحديد الحالة كـ resolved.',
        ];
    }
}