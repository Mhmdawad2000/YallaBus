<?php

namespace Modules\Settings\Services\Complaint;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Modules\Settings\Models\Complaint;
use Modules\Settings\Http\Requests\Complaint\ComplaintRequest;

class ComplaintService implements ComplaintInterface
{
    public function index(Request $request)
    {
        try {
            $complaints = Complaint::with(['user', 'booking', 'resolver'])
                ->filter($request)
                ->paginate($request->input('per_page', 10));

            return [true, $complaints, 200, 'تم جلب الشكاوى بنجاح.'];
        } catch (\Exception $e) {
            Log::error('ComplaintService@index: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء جلب الشكاوى.'];
        }
    }

    public function store(ComplaintRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            Complaint::create($data);

            return [true, [], 201, 'تم إنشاء الشكوى بنجاح.'];
        } catch (\Exception $e) {
            Log::error('ComplaintService@store: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء إنشاء الشكوى.'];
        }
    }

    public function show($id)
    {
        try {
            $complaint = Complaint::with(['user', 'booking', 'resolver'])->find($id);

            if (!$complaint) {
                return [false, [], 404, 'هذه الشكوى غير موجودة.'];
            }

            return [true, $complaint, 200, 'تم جلب بيانات الشكوى بنجاح.'];
        } catch (\Exception $e) {
            Log::error('ComplaintService@show: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء عرض بيانات الشكوى.'];
        }
    }

    // public function update(ComplaintRequest $request, $id)
    // {
    //     try {
    //         $complaint = Complaint::find($id);

    //         if (!$complaint) {
    //             return [false, [], 404, 'هذه الشكوى غير موجودة.'];
    //         }

    //         $data = $request->validated();
    //         $complaint->update($data);

    //         return [true, $complaint, 200, 'تم تحديث بيانات الشكوى بنجاح.'];
    //     } catch (\Exception $e) {
    //         Log::error('ComplaintService@update: ' . $e->getMessage());
    //         return [false, null, 500, 'حدث خطأ أثناء تحديث بيانات الشكوى.'];
    //     }
    // }

    public function destroy($id)
    {
        try {
            $complaint = Complaint::find($id);

            if (!$complaint) {
                return [false, [], 404, 'هذه الشكوى غير موجودة.'];
            }

            $complaint->delete();

            return [true, null, 200, 'تم حذف الشكوى بنجاح.'];
        } catch (\Exception $e) {
            Log::error('ComplaintService@destroy: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء حذف الشكوى.'];
        }
    }
}