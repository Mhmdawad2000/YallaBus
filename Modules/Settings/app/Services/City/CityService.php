<?php

namespace Modules\Settings\Services\City;

use DB;
use App\Traits\DeleteHelper;
use Illuminate\Http\Request;
use Modules\Settings\Models\City;

class CityService implements CityInterface
{
    use DeleteHelper;
    public function index(Request $request): array
    {
        try {
            $cities = City::filter($request)->with('country')->get();
            return [true, $cities];
        } catch (\Exception $e) {
            return [false, 'فشل في جلب المدن.'];
        }
    }

    public function store(array $data): array
    {
        try {
            $city = City::create($data);
            return [true, $city->load('country')];
        } catch (\Exception $e) {
            return [false, 'فشل في إنشاء المدينة.'];
        }
    }

    public function show($id): array
    {
        try {
            $city = City::with('country')->findOrFail($id);
            return [true, $city];
        } catch (\Exception $e) {
            return [false, 'المدينة غير موجودة.'];
        }
    }

    public function update($id, array $data): array
    {
        try {
            $city = City::findOrFail($id);
            $city->update($data);
            return [true, $city->load('country')];
        } catch (\Exception $e) {
            return [false, 'فشل في تحديث المدينة.'];
        }
    }

    public function delete($id): array
    {
        try {
            $city = City::findOrFail($id);
            return $this->attemptDelete('Country', 'عنصر المدينة', $city, [
                'users' => 'users'
            ]);
        } catch (\Exception $e) {
            return [false, 'فشل في حذف المدينة.'];
        }
    }
}
