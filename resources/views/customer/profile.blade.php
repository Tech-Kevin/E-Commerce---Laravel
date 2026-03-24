@extends('layouts.store')

@section('title', 'My Account')

@section('content')
    <section class="page-section">
        <div class="store-container">
            <div class="section-heading">
                <div>
                    <h2>My Account</h2>
                    <p>Manage your profile and saved information</p>
                </div>
            </div>

            <div class="profile-card">
                <div class="profile-top">
                    <div class="profile-avatar">K</div>
                    <div>
                        <h3>Kevin Patel</h3>
                        <p>kevin@example.com</p>
                    </div>
                </div>

                <div class="checkout-grid">
                    <div class="form-group">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" value="Kevin Patel">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="kevin@example.com">
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" value="Ahmedabad, Gujarat">
                    </div>
                </div>

                <div class="profile-actions">
                    <button class="primary-btn">Save Changes</button>
                </div>
            </div>
        </div>
    </section>
@endsection