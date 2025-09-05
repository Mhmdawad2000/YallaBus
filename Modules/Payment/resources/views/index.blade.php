use Modules\User\Models\User;
@extends('stripe::layouts.master')

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
                <div class="d-flex justify-content-between mb-4 small">
                    <strong>Total</strong>
                    <strong id="amountDoular">$200.00</strong>
                </div>
            </div>
            <input type="input" onchange="updateInfo(auth()->user())">
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
    </script>
@endsection
