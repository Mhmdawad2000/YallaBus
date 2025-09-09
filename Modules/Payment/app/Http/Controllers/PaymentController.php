<?php

namespace Modules\Payment\Http\Controllers;

use Exception;
use Illuminate\Foundation\Http\Middleware\Concerns\ExcludesPaths;
use Illuminate\Http\Request;
use Modules\User\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\User\Models\UserBalanceLog;

class PaymentController extends Controller
{

    public function index()
    {
        return view('payment::index');
    }

    public function exchangeRate()
    {
        $user = User::find(Auth::id());
        return $user->city->country->currency->exchange_rate;
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:1',
                'stripeToken' => 'required|string|min:1',
            ]);
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

            $amount = $request->amount * 100;
            $source = $request->stripeToken;
            $charge = $stripe->charges->create([
                'amount' => $amount,
                'currency' => 'usd',
                'source' => $source,
                'description' => 'from backend www.yallabus.com'
            ]);
            if ($charge->status == "succeeded") {
                $dollar2cookies = config('paymant.dollar2cookies');
                $user = User::find(Auth::id());
                $old_balance = $user->balance;
                $user->increment('balance', $amount * $dollar2cookies);
                UserBalanceLog::create([
                    'user_id' => $user->id,
                    'old_balance' => $old_balance,
                    'new_balance' => $user->balance,
                    'reason' => 'Charge Balance By Payment',
                ]);
                return $this->errorResponse([], 201, 'تم شحن رصيدك');
            }
            return $this->errorResponse([], 400, 'فشل شحن رصيدك');
        } catch (Exception $e) {
            return $this->errorResponse([], 400, $e->getMessage());
        }
    }
}
