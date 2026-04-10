@extends('layouts.delivery')

@section('title', __('delivery.verify_delivery'))
@section('page_title', __('delivery.verify_delivery'))
@section('page_subtitle', __('delivery.order') . ' #' . $order->order_number . ' — ' . __('delivery.confirm_otp_sig'))

@section('content')
    <div class="verify-container">
        {{-- Order Summary --}}
        <div class="dashboard-card">
            <div class="card-header">
                <h3>{{ __('delivery.order_details') }}</h3>
            </div>
            <div class="order-summary">
                <div class="summary-row">
                    <span>{{ __('delivery.order_number') }}</span>
                    <strong>#{{ $order->order_number }}</strong>
                </div>
                <div class="summary-row">
                    <span>{{ __('delivery.customer') }}</span>
                    <strong>{{ $order->full_name }}</strong>
                </div>
                <div class="summary-row">
                    <span>{{ __('delivery.phone') }}</span>
                    <strong>{{ $order->phone }}</strong>
                </div>
                <div class="summary-row">
                    <span>{{ __('delivery.address') }}</span>
                    <strong>{{ $order->address }}, {{ $order->city }} - {{ $order->pincode }}</strong>
                </div>
                <div class="summary-row">
                    <span>{{ __('delivery.amount') }}</span>
                    <strong>₹ {{ number_format($order->grand_total, 2) }}</strong>
                </div>
                <div class="summary-row">
                    <span>{{ __('delivery.payment') }}</span>
                    <strong>{{ strtoupper($order->payment_method) }} — {{ ucfirst($order->payment_status) }}</strong>
                </div>
            </div>
        </div>

        {{-- Step 1: Send OTP --}}
        <div class="dashboard-card" id="step-otp">
            <div class="card-header">
                <h3><span class="step-num">1</span> {{ __('delivery.verify_customer_otp') }}</h3>
            </div>
            <div class="verify-step-content">
                <p class="verify-info">{{ __('delivery.otp_info') }} <strong>{{ substr($order->phone, 0, 3) }}****{{ substr($order->phone, -3) }}</strong></p>

                <button class="btn btn-primary" id="send-otp-btn" onclick="sendOtp()">
                    <i class="fa-solid fa-paper-plane"></i> {{ __('delivery.send_otp') }}
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
                        <i class="fa-solid fa-check"></i> {{ __('delivery.verify_otp') }}
                    </button>
                    <button class="btn btn-secondary" onclick="sendOtp()" style="margin-top: 12px;">
                        <i class="fa-solid fa-rotate"></i> {{ __('delivery.resend_otp') }}
                    </button>
                    <p id="otp-message" class="verify-message" style="display:none;"></p>
                </div>
            </div>
        </div>

        {{-- Step 2: Customer Signature --}}
        <div class="dashboard-card" id="step-signature" style="display:none;">
            <div class="card-header">
                <h3><span class="step-num">2</span> {{ __('delivery.customer_signature') }}</h3>
            </div>
            <div class="verify-step-content">
                <p class="verify-info">{{ __('delivery.sign_instruction') }}</p>

                <div class="signature-pad-wrapper" id="signature-wrapper">
                    <canvas id="signature-pad" height="200"></canvas>
                </div>
                <div class="signature-actions">
                    <button class="btn btn-secondary" onclick="clearSignature()">
                        <i class="fa-solid fa-eraser"></i> {{ __('delivery.clear') }}
                    </button>
                    <button class="btn btn-success" onclick="confirmDelivery()">
                        <i class="fa-solid fa-check-double"></i> {{ __('delivery.confirm_delivery') }}
                    </button>
                </div>
                <p id="signature-message" class="verify-message" style="display:none;"></p>
            </div>
        </div>

        {{-- Success --}}
        <div class="dashboard-card" id="step-success" style="display:none;">
            <div class="delivery-success">
                <i class="fa-solid fa-circle-check"></i>
                <h2>{{ __('delivery.order_delivered_paid') }}</h2>
                <p>{{ __('delivery.order_delivered_msg', ['number' => $order->order_number]) }}</p>
                <a href="{{ route('delivery.orders') }}" class="btn btn-primary">{{ __('delivery.back_to_orders') }}</a>
            </div>
        </div>
    </div>

@push('scripts')
<script>
const orderId = {{ $order->id }};
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Localized JS strings
const lang = {
    sending: @json(__('delivery.sending')),
    sendOtp: @json(__('delivery.send_otp')),
    failedSendOtp: @json(__('delivery.failed_send_otp')),
    enterCompleteOtp: @json(__('delivery.enter_complete_otp')),
    verifying: @json(__('delivery.verifying')),
    verifyOtp: @json(__('delivery.verify_otp')),
    verificationFailed: @json(__('delivery.verification_failed')),
    signatureRequired: @json(__('delivery.signature_required')),
    failedConfirm: @json(__('delivery.failed_confirm')),
};

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
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> ' + lang.sending;

    fetch(`/delivery/orders/${orderId}/send-otp`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> ' + lang.sendOtp;
        document.getElementById('otp-input-section').style.display = 'block';
        showMessage('otp-message', data.message, data.success ? 'success' : 'error');
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> ' + lang.sendOtp;
        showMessage('otp-message', lang.failedSendOtp, 'error');
    });
}

function verifyOtp() {
    const otp = getOtpValue();
    if (otp.length !== 6) {
        showMessage('otp-message', lang.enterCompleteOtp, 'error');
        return;
    }

    const btn = document.getElementById('verify-otp-btn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> ' + lang.verifying;

    fetch(`/delivery/orders/${orderId}/verify-otp`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ otp }),
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-check"></i> ' + lang.verifyOtp;
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
        btn.innerHTML = '<i class="fa-solid fa-check"></i> ' + lang.verifyOtp;
        showMessage('otp-message', lang.verificationFailed, 'error');
    });
}

// Signature Pad
let canvas, ctx, drawing = false;

function initSignaturePad() {
    canvas = document.getElementById('signature-pad');
    ctx = canvas.getContext('2d');

    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);

    canvas.addEventListener('mousedown', startDraw);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDraw);
    canvas.addEventListener('mouseleave', stopDraw);
    canvas.addEventListener('touchstart', e => { e.preventDefault(); startDraw(getTouchPos(e)); }, { passive: false });
    canvas.addEventListener('touchmove', e => { e.preventDefault(); draw(getTouchPos(e)); }, { passive: false });
    canvas.addEventListener('touchend', stopDraw);
}

function resizeCanvas() {
    const wrapper = document.getElementById('signature-wrapper');
    const w = wrapper.clientWidth - 10;
    canvas.width = w;
    canvas.height = Math.min(200, w * 0.45);
    ctx = canvas.getContext('2d');
    ctx.strokeStyle = '#2d241f';
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
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
        showMessage('signature-message', lang.signatureRequired, 'error');
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
    .catch(() => showMessage('signature-message', lang.failedConfirm, 'error'));
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
