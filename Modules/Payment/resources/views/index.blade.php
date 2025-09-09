@extends('payment::layouts.master')

@section('content')
    <div class="container col-md-4 mt-5">
        <div class="card mt-5">
            <div class="card-header">
                <h4>Make Payment</h4>
            </div>

            {{-- Flash Message --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="p-3 bg-light bg-opacity-10">
                <h6 class="card-title mb-3">Order Summary</h6>
                <hr>
                <div class="form-group mb-4">
                    <label for="amountInput" class="form-label">Enter Quantity</label>
                    <input id="amountInput" type="number" class="form-control" placeholder="Enter amount"
                        onchange="updateInfo()">

                </div>

                {{-- <div class="d-flex justify-content-between mb-2 small">
                    <strong></strong>
                    <strong id="amountDoular">$0.00</strong>
                </div> --}}
                <div class="d-flex justify-content-between mb-4 small">
                    <strong>Cash Value</strong>
                    <strong id="amountCash">0.00 Cash</strong>
                    <strong id="amountDoular">$0.00</strong>
                </div>
            </div>

            {{-- Stripe Payment Form --}}
            <form action="{{ route('stripe.post') }}" method="POST" id="payment-form">
                @csrf

                <div class="form-group mb-3">
                    <div id="card-element" class="form-control"></div>
                    <div id="card-errors" class="text-danger mt-2" role="alert"></div>
                </div>

                <input type="hidden" name="stripeToken" id="stripe-token">
                <input type="hidden" name="amount" id="amount">

                <button class="btn btn-primary w-100 mt-2" type="button" onclick="createToken()">Submit</button>
            </form>

        </div>
    </div>

    {{-- Stripe JS --}}
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe("{{ env('STRIPE_KEY') }}");
        const elements = stripe.elements();
        const card = elements.create("card");
        card.mount("#card-element");

        function createToken() {
            stripe.createToken(card).then(function(result) {
                if (result.token) {
                    document.getElementById('stripe-token').value = result.token.id;
                    document.getElementById('payment-form').submit();
                }
            });
        }

        // Update amounts dynamically
        window.updateInfo = async function() {
            const inputVal = parseFloat(document.getElementById('amountInput').value) || 0;
                // حساب القيم بناء على البيانات من السيرفر
                const dollarVal = inputVal / 11000;
                const cashVal = dollarVal * 20;

                // تحديث القيم على الصفحة
                document.getElementById('amountDoular').innerText = `$${dollarVal.toFixed(5)}`;
                document.getElementById('amountCash').innerText = `${cashVal.toFixed(5)} Cash`;
                document.getElementById('amount').value = dollarVal.toFixed(5);
        }
    </script>
@endsection
