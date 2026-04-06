@extends('layouts.store')

@section('title', 'Processing Payment')

@section('content')
    <section class="page-section">
        <div class="store-container" style="text-align: center; padding: 60px 20px;">
            <i class="fa-solid fa-spinner fa-spin" style="font-size: 40px; color: #d4a373; margin-bottom: 20px;"></i>
            <h2>Redirecting to Razorpay...</h2>
            <p style="color: #888; margin-top: 10px;">Please do not close this page.</p>
        </div>
    </section>

    <form id="razorpay-response-form" action="{{ route('razorpay.verify') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
        <input type="hidden" name="razorpay_signature" id="razorpay_signature">
    </form>
@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    var options = {
        "key": "{{ $keyId }}",
        "amount": "{{ $amount }}",
        "currency": "{{ $currency }}",
        "name": "Ekka_lv Store",
        "description": "Order Payment",
        "order_id": "{{ $razorpayOrderId }}",
        "handler": function (response) {
            document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('razorpay_signature').value = response.razorpay_signature;
            document.getElementById('razorpay-response-form').submit();
        },
        "prefill": {
            "name": "{{ $userName }}",
            "email": "{{ $userEmail }}",
            "contact": "{{ $userPhone }}"
        },
        "theme": {
            "color": "#d4a373"
        },
        "modal": {
            "ondismiss": function () {
                window.location.href = "{{ route('razorpay.cancel') }}";
            }
        }
    };

    var rzp = new Razorpay(options);
    rzp.open();
</script>
@endpush
