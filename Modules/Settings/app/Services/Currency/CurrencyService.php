<?php

namespace Modules\Settings\Services\Currency;

use App\Traits\DeleteHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Settings\Models\Currency;


class CurrencyService implements CurrencyInterface
{
    use DeleteHelper;
    public function index(Request $request)
    {
        try {
            $currencies = Currency::filter($request)->get();
            return [true, $currencies];
        } catch (\Exception $e) {
            return [false, $e->getMessage()];
        }
    }

    public function show($id)
    {
        try {
            $currency = Currency::findOrFail($id);
            return [true, $currency];
        } catch (\Exception $e) {
            return [false, $e->getMessage()];
        }
    }

    public function store(array $data)
    {
        try {
            $currency = Currency::create($data);
            return [true, $currency];
        } catch (\Exception $e) {
            return [false, $e->getMessage()];
        }
    }

    public function update($id, array $data)
    {
        try {
            $currency = Currency::findOrFail($id);
            $currency->update($data);
            return [true, $currency];
        } catch (\Exception $e) {
            return [false, $e->getMessage()];
        }
    }

    public function delete($id)
    {
        try {
            $currency = Currency::findOrFail($id);
            if ($currency->is_default) {
                return [2, 'العملة الافتراضية لا يمكن حذفها'];
            }
            return $this->attemptDelete('Currency', 'عنصر العملة', $currency, [
                'countries' => 'countries'
            ]);
        } catch (\Exception $e) {
            return [false, $e->getMessage()];
        }
    }

    public function toggleDefault($id): array
    {
        try {
            DB::beginTransaction();
            Currency::where('is_default', true)->update(['is_default' => false]);
            $currency = Currency::findOrFail($id);
            $currency->is_default = true;
            $currency->save();
            DB::commit();
            return [true, $currency];
        } catch (\Exception $e) {
            DB::rollBack();
            return [false, 'حدث خطأ أثناء تعيين العملة الافتراضية.'];
        }
    }

    public function default(): array
    {
        try {
            $currency = Currency::where('is_default', true)->first();
            return [true, $currency];
        } catch (\Exception $e) {
            DB::rollBack();
            return [false, 'حدث خطأ أثناء جلب العملة الافتراضية.'];
        }
    }

}
