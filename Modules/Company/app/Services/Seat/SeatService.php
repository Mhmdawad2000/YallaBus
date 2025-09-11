<?php

namespace Modules\Company\Services\Seat;

use Illuminate\Http\Request;
use Modules\Company\Models\Seat;
use Modules\Company\Models\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Modules\User\Models\User;
use Modules\Company\Http\Requests\Seat\SeatRequest;

class SeatService implements SeatInterface
{
    public function index(Request $request)
    {
        try {
            $error = $this->checkRole();
            if ($error) {
                return $error;
            }

            $company = $this->getCompany();
            if (!$company) {
                return [false, [], 404, 'الشركة غير موجودة.'];
            }

            $seats = Seat::filter($request)
                ->whereHas('bus', function ($query) use ($company) {
                    $query->where('company_id', $company->id);
                })
                ->paginate($request->input('per_page', 10));

            return [true, $seats, 200, 'تم جلب المقاعد بنجاح.'];
        } catch (\Exception $e) {
            Log::error('SeatService@index: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء جلب المقاعد.'];
        }
    }

    public function store(SeatRequest $request)
    {
        try {
            $error = $this->checkRole();
            if ($error) {
                return $error;
            }

            $company = $this->getCompany();
            if (!$company) {
                return [false, [], 404, 'الشركة غير موجودة.'];
            }

            $bus = Bus::where('company_id', $company->id)
                ->find($request->bus_id);

            if (!$bus) {
                return [false, [], 404, 'الحافلة غير موجودة أو لا تتبع شركتك.'];
            }

            $data = $request->validated();
            $seat = Seat::create($data);

            return [true, $seat, 201, 'تم إنشاء المقعد بنجاح.'];
        } catch (\Exception $e) {
            Log::error('SeatService@store: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء إنشاء المقعد.'];
        }
    }

    public function show($id)
    {
        try {
            $error = $this->checkRole();
            if ($error) {
                return $error;
            }

            $company = $this->getCompany();
            if (!$company) {
                return [false, [], 404, 'الشركة غير موجودة.'];
            }

            $seat = Seat::whereHas('bus', function ($query) use ($company) {
                $query->where('company_id', $company->id);
            })
                ->find($id);

            if (!$seat) {
                return [false, [], 404, 'المقعد غير موجود.'];
            }

            return [true, $seat, 200, 'تم جلب بيانات المقعد بنجاح.'];
        } catch (\Exception $e) {
            Log::error('SeatService@show: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء جلب بيانات المقعد.'];
        }
    }

    public function update(SeatRequest $request, $id)
    {
        try {
            $error = $this->checkRole();
            if ($error) {
                return $error;
            }

            $company = $this->getCompany();
            if (!$company) {
                return [false, [], 404, 'الشركة غير موجودة.'];
            }

            $seat = Seat::whereHas('bus', function ($query) use ($company) {
                $query->where('company_id', $company->id);
            })
                ->find($id);
            if (!$seat->is_available) {
                return [false, [], 400, 'لا يمكنك تعديل مقعد عليه حجز'];
            }
            if (!$seat) {
                return [false, [], 404, 'المقعد غير موجود.'];
            }

            $seat->update($request->validated());

            return [true, $seat, 200, 'تم تحديث بيانات المقعد بنجاح.'];
        } catch (\Exception $e) {
            Log::error('SeatService@update: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء تحديث بيانات المقعد.'];
        }
    }

    public function destroy($id)
    {
        try {
            $error = $this->checkRole();
            if ($error) {
                return $error;
            }

            $company = $this->getCompany();
            if (!$company) {
                return [false, [], 404, 'الشركة غير موجودة.'];
            }

            $seat = Seat::whereHas('bus', function ($query) use ($company) {
                $query->where('company_id', $company->id);
            })
                ->find($id);

            if (!$seat) {
                return [false, [], 404, 'المقعد غير موجود.'];
            }

            $seat->delete();

            return [true, null, 200, 'تم حذف المقعد بنجاح.'];
        } catch (\Exception $e) {
            Log::error('SeatService@destroy: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء حذف المقعد.'];
        }
    }

    /**********************
     *  Helper Functions  *
     **********************/
    private function getCompany()
    {
        try {
            $user = User::find(Auth::id());
            return $user?->company;
        } catch (\Exception $e) {
            Log::error('SeatService@getCompany: ' . $e->getMessage());
            return null;
        }
    }

    private function checkRole()
    {
        $userRole = User::find(Auth::id())->role->name ?? null;
        if ($userRole !== 'company-admin') {
            return [false, [], 403, 'غير مصرح لك بتنفيذ هذا الإجراء.'];
        }
        return null;
    }
}
