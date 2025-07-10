<?php

namespace Modules\Settings\Services\Review;

use Illuminate\Http\Request;
use Modules\Settings\Models\Review;
use Illuminate\Support\Facades\Log;
use Modules\Settings\Http\Requests\Review\ReviewRequest;

class ReviewService implements ReviewInterface
{
    public function index(Request $request)
    {
        try {
            $reviews = Review::filter($request)->with(['user', 'trip'])
                ->paginate($request->input('per_page', 10));

            return [true, $reviews, 200, 'تم جلب التقييمات بنجاح.'];
        } catch (\Exception $e) {
            Log::error('ReviewService@index: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء جلب التقييمات.'];
        }
    }

    public function store(ReviewRequest $request)
    {
        try {
            $data = $request->validated();
            $review = Review::create($data);

            return [true, $review, 201, 'تم إنشاء التقييم بنجاح.'];
        } catch (\Exception $e) {
            Log::error('ReviewService@store: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء إنشاء التقييم.'];
        }
    }

    public function show($id)
    {
        try {
            $review = Review::with(['user', 'trip'])->find($id);

            if (!$review) {
                return [false, [], 404, 'هذا التقييم غير موجود.'];
            }

            return [true, $review, 200, 'تم جلب بيانات التقييم بنجاح.'];
        } catch (\Exception $e) {
            Log::error('ReviewService@show: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء عرض بيانات التقييم.'];
        }
    }

    // public function update(ReviewRequest $request, $id)
    // {
    //     try {
    //         $review = Review::find($id);

    //         if (!$review) {
    //             return [false, [], 404, 'هذا التقييم غير موجود.'];
    //         }

    //         $data = $request->validated();
    //         $review->update($data);

    //         return [true, $review, 200, 'تم تحديث بيانات التقييم بنجاح.'];
    //     } catch (\Exception $e) {
    //         Log::error('ReviewService@update: ' . $e->getMessage());
    //         return [false, null, 500, 'حدث خطأ أثناء تحديث بيانات التقييم.'];
    //     }
    // }

    // public function destroy($id)
    // {
    //     try {
    //         $review = Review::find($id);

    //         if (!$review) {
    //             return [false, [], 404, 'هذا التقييم غير موجود.'];
    //         }

    //         $review->delete();

    //         return [true, null, 200, 'تم حذف التقييم بنجاح.'];
    //     } catch (\Exception $e) {
    //         Log::error('ReviewService@destroy: ' . $e->getMessage());
    //         return [false, null, 500, 'حدث خطأ أثناء حذف التقييم.'];
    //     }
    // }
}