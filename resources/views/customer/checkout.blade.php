@extends('layouts.store')

@section('title', 'Checkout')

@section('content')
    <section class="page-section">
        <div class="store-container">
            <div class="section-heading">
                <div>
                    <h2>Checkout</h2>
                    <p>Complete your order securely</p>
                </div>
            </div>

            <div class="checkout-layout">
                <div class="checkout-form-card">
                    <h3>Billing Details</h3>

                    <div class="checkout-grid">
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control">
                        </div>

                        <div class="form-group full-width">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control">
                        </div>

                        <div class="form-group">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Pincode</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="cart-summary-card">
                    <h3>Payment Summary</h3>
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>₹2,499</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span>₹99</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>₹2,598</span>
                    </div>
                    <button class="primary-btn full-btn">Place Order</button>
                </div>
            </div>
        </div>
    </section>
@endsection