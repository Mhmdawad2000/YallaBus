<?php

namespace Modules\Company\Services\Bus;

use Illuminate\Http\Request;
use Modules\Trip\Models\Trip;
use Modules\User\Models\User;
use Modules\Company\Models\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Modules\Company\Http\Requests\Bus\BusRequest;

class BusService implements BusInterface
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

            $buses = Bus::filter($request)
                ->where('company_id', $company->id)
                ->paginate($request->input('per_page', 10));

            return [true, $buses, 200, 'تم جلب الحافلات بنجاح.'];
        } catch (\Exception $e) {
            Log::error('BusService@index: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء جلب الحافلات.'];
        }
    }

    public function store(BusRequest $request)
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

            $data = $request->validated();
            $data['company_id'] = $company->id;
            $bus = Bus::create($data);

            return [true, $bus, 201, 'تم إنشاء الحافلة بنجاح.'];
        } catch (\Exception $e) {
            Log::error('BusService@store: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء إنشاء الحافلة.'];
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

            $bus = Bus::where('company_id', $company->id)->find($id);
            if (!$bus) {
                return [false, [], 404, 'الحافلة غير موجودة.'];
            }

            return [true, $bus, 200, 'تم جلب الحافلة بنجاح.'];
        } catch (\Exception $e) {
            Log::error('BusService@show: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء جلب الحافلة.'];
        }
    }

    public function update(BusRequest $request, $id)
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

            $bus = Bus::where('company_id', $company->id)->find($id);
            if (!$bus) {
                return [false, [], 404, 'الحافلة غير موجودة.'];
            }
            $is_has_trip = Trip::where(['status' => 'available', 'bus_id', $bus->id])->exists();
            if ($is_has_trip) {
                return [false, [], 400, 'لا يمكن تعديل حافلة لديها رحلة قائمة'];
            }

            $bus->update($request->validated());

            return [true, $bus, 200, 'تم تعديل الحافلة بنجاح.'];
        } catch (\Exception $e) {
            Log::error('BusService@update: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء تعديل الحافلة.'];
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

            $bus = Bus::where('company_id', $company->id)->where('id', $id)->first();
            // 2|pl8dDIQFSXdWkyKt7r9r7OoPB2jWzxt8W4c1LoRp0e2fd007
            // 3|bj4m2H0rvSWE4OLxBJjlProrbTLObsioNjAgjYwY93d7b4e9
            if (!$bus) {
                return [false, [], 404, 'الحافلة غير موجودة.'];
            }

            $bus->delete();

            return [true, null, 200, 'تم حذف الحافلة بنجاح.'];
        } catch (\Exception $e) {
            Log::error('BusService@destroy: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء حذف الحافلة.'];
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
            Log::error('BusService@getCompany: ' . $e->getMessage());
            return null;
        }
    }

    private function checkRole()
    {
        $userRole = User::find(Auth::id())->role->name ?? null;
        if ($userRole !== 'company-admin') {
            return [false, [], 403, 'غير مصرح لك بالوصول.'];
        }
        return null;
    }
}
