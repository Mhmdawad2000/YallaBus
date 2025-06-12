<?php

namespace Modules\Settings\Services\Country;

use App\Traits\DeleteHelper;
use Illuminate\Http\Request;
use Modules\Settings\Models\Country;


class CountryService implements CountryInterface
{
    use DeleteHelper;

    public function index(Request $request): array
    {
        try {
            $countries = Country::filter($request)->with('currency')->paginate($request->perPage ?? 10);
            return [true, $countries];
        } catch (\Exception $e) {
            return [false, 'فشل في جلب الدول.'];
        }
    }

    public function store(array $data): array
    {
        try {
            $country = Country::create($data);
            return [true, $country->load('currency')];
        } catch (\Exception $e) {
            return [false, 'فشل في إنشاء الدولة.'];
        }
    }

    public function show($id): array
    {
        try {
            $country = Country::with('currency')->findOrFail($id);
            return [true, $country];
        } catch (\Exception $e) {
            return [false, 'الدولة غير موجودة.'];
        }
    }

    public function update($id, array $data): array
    {
        try {
            $country = Country::findOrFail($id);
            $country->update($data);
            return [true, $country->load('currency')];
        } catch (\Exception $e) {
            return [false, 'فشل في تحديث الدولة.'];
        }
    }

    public function delete($id): array
    {
        try {
            $country = Country::findOrFail($id);
            return $this->attemptDelete('Country', 'عنصر الدولة', $country, [
                'cities' => 'cities'
            ]);
        } catch (\Exception $e) {
            return [false, 'فشل في حذف الدولة.'];
        }
    }
}
