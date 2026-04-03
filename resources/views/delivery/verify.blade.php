@extends('layouts.delivery')

@section('title', 'Verify Delivery')
@section('page_title', 'Verify Delivery')
@section('page_subtitle', 'Order #' . $order->order_number . ' — Confirm delivery with OTP & signature')

@section('content')
    <div class="verify-container">
        {{-- Order Summary --}}
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Order Details</h3>
            </div>
            <div class="order-summary">
                <div class="summary-row">
                    <span>Order Number</span>
                    <strong>#{{ $order->order_number }}</strong>
                </div>
                <div class="summary-row">
                    <span>Customer</span>
                    <strong>{{ $order->full_name }}</strong>
                </div>
                <div class="summary-row">
                    <span>Phone</span>
                    <strong>{{ $order->phone }}</strong>
                </div>
                <div class="summary-row">
                    <span>Address</span>
                    <strong>{{ $order->address }}, {{ $order->city }} - {{ $order->pincode }}</strong>
                </div>
                <div class="summary-row">
                    <span>Amount</span>
                    <strong>₹ {{ number_format($order->grand_total, 2) }}</strong>
                </div>
                <div class="summary-row">
                    <span>Payment</span>
                    <strong>{{ strtoupper($order->payment_method) }} — {{ ucfirst($order->payment_status) }}</strong>
                </div>
            </div>
        </div>

        {{-- Step 1: Send OTP --}}
        <div class="dashboard-card" id="step-otp">
            <div class="card-header">
                <h3><span class="step-num">1</span> Verify Customer OTP</h3>
            </div>
            <div class="verify-step-content">
                <p class="verify-info">An OTP will be sent to the customer's registered phone number <strong>{{ substr($order->phone, 0, 3) }}****{{ substr($order->phone, -3) }}</strong></p>

                <button class="btn btn-primary" id="send-otp-btn" onclick="sendOtp()">
                    <i class="fa-solid fa-paper-plane"></i> Send OTP
                </button>

                <div id="otp-input-section" style="display:none; margin-top: 20px;">
                    <div class="otp-inputs">
                        <input type="text" maxlength="1" class="otp-box" data-index="0" autofocus>
                        <input type="text" maxlength="1" class="otp-box" data-index="1">
                        <input type="text" maxlength="1" class="otp-box" data-index="2">
                        <input type="text" maxlength="1" class="otp-box" data-index="3">
                        <input type="text" maxlength="1" class="otp-box" data-index="4">
                        <input type="text" maxlength="1" class="otp-box" data-index="5">
                    </div>
                    <button class="btn btn-primary" id="verify-otp-btn" onclick="verifyOtp()" style="margin-top: 12px;">
                        <i class="fa-solid fa-check"></i> Verify OTP
                    </button>
                    <button class="btn btn-secondary" onclick="sendOtp()" style="margin-top: 12px;">
                        <i class="fa-solid fa-rotate"></i> Resend OTP
                    </button>
                    <p id="otp-message" class="verify-message" style="display:none;"></p>
                </div>
            </div>
        </div>

        {{-- Step 2: Customer Signature --}}
        <div class="dashboard-card" id="step-signature" style="display:none;">
            <div class="card-header">
                <h3><span class="step-num">2</span> Customer Signature</h3>
            </div>
            <div class="verify-step-content">
                <p class="verify-info">Ask the customer to sign below to confirm receipt of the order.</p>

                <div class="signature-pad-wrapper">
                    <canvas id="signature-pad" width="500" height="200"></canvas>
                </div>
                <div class="signature-actions">
                    <button class="btn btn-secondary" onclick="clearSignature()">
                        <i class="fa-solid fa-eraser"></i> Clear
                    </button>
                    <button class="btn btn-success" onclick="confirmDelivery()">
                        <i class="fa-solid fa-check-double"></i> Confirm Delivery
                    </button>
                </div>
                <p id="signature-message" class="verify-message" style="display:none;"></p>
            </div>
        </div>

        {{-- Success --}}
        <div class="dashboard-card" id="step-success" style="display:none;">
            <div class="delivery-success">
                <i class="fa-solid fa-circle-check"></i>
                <h2>Order Delivered & Paid!</h2>
                <p>Order #{{ $order->order_number }} has been marked as delivered and payment confirmed.</p>
                <a href="{{ route('delivery.orders') }}" class="btn btn-primary">Back to Orders</a>
            </div>
        </div>
    </div>

@push('scripts')
<script>
const orderId = {{ $order->id }};
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// OTP Box Auto-Focus
document.querySelectorAll('.otp-box').forEach((box, idx, boxes) => {
    box.addEventListener('input', () => {
        if (box.value.length === 1 && idx < boxes.length - 1) boxes[idx + 1].focus();
    });
    box.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !box.value && idx > 0) boxes[idx - 1].focus();
    });
});

function getOtpValue() {
    return Array.from(document.querySelectorAll('.otp-box')).map(b => b.value).join('');
}

function sendOtp() {
    const btn = document.getElementById('send-otp-btn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sending...';

    fetch(`/delivery/orders/${orderId}/send-otp`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Send OTP';
        document.getElementById('otp-input-section').style.display = 'block';
        showMessage('otp-message', data.message, data.success ? 'success' : 'error');
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Send OTP';
        showMessage('otp-message', 'Failed to send OTP.', 'error');
    });
}

function verifyOtp() {
    const otp = getOtpValue();
    if (otp.length !== 6) {
        showMessage('otp-message', 'Please enter the complete 6-digit OTP.', 'error');
        return;
    }

    const btn = document.getElementById('verify-otp-btn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Verifying...';

    fetch(`/delivery/orders/${orderId}/verify-otp`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ otp }),
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-check"></i> Verify OTP';
        showMessage('otp-message', data.message, data.success ? 'success' : 'error');
        if (data.success) {
            document.getElementById('step-signature').style.display = 'block';
            document.getElementById('step-otp').style.opacity = '0.5';
            document.getElementById('step-otp').style.pointerEvents = 'none';
            initSignaturePad();
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-check"></i> Verify OTP';
        showMessage('otp-message', 'Verification failed.', 'error');
    });
}

// Signature Pad
let canvas, ctx, drawing = false;

function initSignaturePad() {
    canvas = document.getElementById('signature-pad');
    ctx = canvas.getContext('2d');
    ctx.strokeStyle = '#2d241f';
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';

    canvas.addEventListener('mousedown', startDraw);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDraw);
    canvas.addEventListener('mouseleave', stopDraw);
    canvas.addEventListener('touchstart', e => { e.preventDefault(); startDraw(getTouchPos(e)); });
    canvas.addEventListener('touchmove', e => { e.preventDefault(); draw(getTouchPos(e)); });
    canvas.addEventListener('touchend', stopDraw);
}

function getTouchPos(e) {
    const rect = canvas.getBoundingClientRect();
    return { offsetX: e.touches[0].clientX - rect.left, offsetY: e.touches[0].clientY - rect.top };
}

function startDraw(e) { drawing = true; ctx.beginPath(); ctx.moveTo(e.offsetX, e.offsetY); }
function draw(e) { if (!drawing) return; ctx.lineTo(e.offsetX, e.offsetY); ctx.stroke(); }
function stopDraw() { drawing = false; }
function clearSignature() { ctx.clearRect(0, 0, canvas.width, canvas.height); }

function isCanvasBlank() {
    const blank = document.createElement('canvas');
    blank.width = canvas.width;
    blank.height = canvas.height;
    return canvas.toDataURL() === blank.toDataURL();
}

function confirmDelivery() {
    if (isCanvasBlank()) {
        showMessage('signature-message', 'Please get the customer signature first.', 'error');
        return;
    }

    const signature = canvas.toDataURL('image/png');

    fetch(`/delivery/orders/${orderId}/confirm`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ signature }),
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('step-signature').style.display = 'none';
            document.getElementById('step-otp').style.display = 'none';
            document.getElementById('step-success').style.display = 'block';
        } else {
            showMessage('signature-message', data.message, 'error');
        }
    })
    .catch(() => showMessage('signature-message', 'Failed to confirm delivery.', 'error'));
}

function showMessage(id, msg, type) {
    const el = document.getElementById(id);
    el.textContent = msg;
    el.className = 'verify-message ' + type;
    el.style.display = 'block';
}
</script>
@endpush
@endsection
