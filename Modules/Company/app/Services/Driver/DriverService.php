<?php

namespace Modules\Company\Services\Driver;

use Illuminate\Http\Request;
use Modules\User\Models\User;
use Modules\Company\Models\Driver;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Company\Http\Requests\Driver\DriverRequest;

class DriverService implements DriverInterface
{
    public function index(string $role, Request $request)
    {
        try {
            $error = $this->checkRole($role);
            if ($error) {
                return $error;
            }

            $company = $this->getCompany();
            if (!$company) {
                return [false, [], 404, 'لم يتم العثور على الشركة.'];
            }

            $drivers = Driver::filter($request)
                ->where('company_id', $company->id)
                ->paginate($request->input('per_page', 10));

            return [true, $drivers, 200, 'تم جلب السائقين بنجاح.'];
        } catch (\Exception $e) {
            Log::error('DriverService@index: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء جلب السائقين.'];
        }
    }

    public function store(string $role, DriverRequest $request)
    {
        try {
            $error = $this->checkRole($role);
            if ($error) {
                return $error;
            }

            $company = $this->getCompany();
            if (!$company) {
                return [false, [], 404, 'لم يتم العثور على الشركة.'];
            }

            $data = $request->validated();

            if ($request->hasFile('photo')) {
                $data['photo'] = $request->file('photo')->store('uploads/drivers', 'public');
            }

            $data['company_id'] = $company->id;
            $driver = Driver::create($data);

            return [true, $driver, 201, 'تم إنشاء السائق بنجاح.'];
        } catch (\Exception $e) {
            Log::error('DriverService@store: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء إنشاء السائق.'];
        }
    }


    public function show(string $role, $id)
    {
        try {
            $error = $this->checkRole($role);
            if ($error) {
                return $error;
            }

            $company = $this->getCompany();
            if (!$company) {
                return [false, [], 404, 'لم يتم العثور على الشركة.'];
            }

            $driver = Driver::where('company_id', $company->id)->find($id);
            if (!$driver) {
                return [false, [], 404, 'هذا السائق غير موجود.'];
            }

            return [true, $driver, 200, 'تم جلب بيانات السائق بنجاح.'];
        } catch (\Exception $e) {
            Log::error('DriverService@show: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء عرض بيانات السائق.'];
        }
    }

    public function update(string $role, DriverRequest $request, $id)
    {
        try {
            $error = $this->checkRole($role);
            if ($error) {
                return $error;
            }

            $company = $this->getCompany();
            if (!$company) {
                return [false, [], 404, 'لم يتم العثور على الشركة.'];
            }

            $driver = Driver::where('company_id', $company->id)->find($id);
            if (!$driver) {
                return [false, [], 404, 'هذا السائق غير موجود.'];
            }

            $data = $request->validated();

            // حذف الصورة القديمة إن وجدت وصورة جديدة مرفوعة
            if ($request->hasFile('photo')) {
                if ($driver->photo && Storage::disk('public')->exists($driver->photo)) {
                    Storage::disk('public')->delete($driver->photo);
                }

                $data['photo'] = $request->file('photo')->store('uploads/drivers', 'public');
            }

            $driver->update($data);

            return [true, $driver, 200, 'تم تحديث بيانات السائق بنجاح.'];
        } catch (\Exception $e) {
            Log::error('DriverService@update: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء تحديث بيانات السائق.'];
        }
    }


    public function destroy(string $role, $id)
    {
        try {
            $error = $this->checkRole($role);
            if ($error) {
                return $error;
            }

            $company = $this->getCompany();
            if (!$company) {
                return [false, [], 404, 'لم يتم العثور على الشركة.'];
            }

            $driver = Driver::where('company_id', $company->id)->find($id);
            if (!$driver) {
                return [false, [], 404, 'هذا السائق غير موجود.'];
            }

            if ($driver->photo && Storage::disk('public')->exists($driver->photo)) {
                Storage::disk('public')->delete($driver->photo);
            }

            $driver->delete();

            return [true, null, 200, 'تم حذف السائق بنجاح.'];
        } catch (\Exception $e) {
            Log::error('DriverService@destroy: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء حذف السائق.'];
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
            Log::error('DriverService@getCompany: ' . $e->getMessage());
            return null;
        }
    }
    private function checkRole(string $role)
    {
        $userRole = User::find(Auth::id())->role->name ?? null;
        if ($role !== 'company-admin' || $userRole !== 'company-admin') {
            return [false, [], 403, 'غير مصرح لك'];
        }


        return null;
    }
}
