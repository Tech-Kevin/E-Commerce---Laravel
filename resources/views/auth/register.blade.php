<link rel="stylesheet" href="{{ asset('css/register.css') }}">
<div class="register-page">
    <div class="register-container">
        <div class="register-card">
            <div class="register-left">
                <span class="register-badge">Create Account</span>
                <h1>Join our store today</h1>
                <p>
                    Create your account to explore products, save your details, and enjoy a smooth shopping experience.
                </p>

                <ul class="register-features">
                    <li>Fast and simple signup</li>
                    <li>Easy order tracking</li>
                    <li>Better shopping experience</li>
                </ul>
            </div>

            <div class="register-right">
                <form action="{{ route('register') }}" method="post" class="register-form">
                    @csrf

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="Enter your full name">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}" placeholder="Enter your address">
                        @error('address')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="number">Contact Number</label>
                        <input type="tel" name="number" id="number" class="form-control" value="{{ old('number') }}" placeholder="Enter your contact number">
                        @error('number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="Enter your email address">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Create a password">
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="register-btn">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>