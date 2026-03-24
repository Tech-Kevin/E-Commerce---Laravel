<link rel="stylesheet" href="{{ asset('css/login.css') }}">
<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-left">
                <span class="auth-badge">Welcome Back</span>
                <h1>Login to continue shopping</h1>
                <p>
                    Access your account to manage orders, save your wishlist, and enjoy a faster checkout experience.
                </p>

                <ul class="auth-features">
                    <li>Track your recent orders</li>
                    <li>Manage your account easily</li>
                    <li>Secure and smooth login</li>
                </ul>
            </div>

            <div class="auth-right">
                <form action="{{ route('login') }}" method="post" class="auth-form">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}"
                            placeholder="Enter your email address">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="Enter your password">
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="auth-btn">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>