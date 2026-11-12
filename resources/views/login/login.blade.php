<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | GIZZAM</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

    <div class="forest-background"></div>
    <div class="leaf-layer" id="leafLayer"></div>

    <div class="auth-container">

        <header class="auth-header">
            <div class="brand"><span>●</span> GIZZAM</div>
            <div class="brand">COMPUTER ACCESSORIES STORE</div>
        </header>

        <main class="auth-layout">


            <section class="auth-side register-side {{ request()->routeIs('register') ? 'active' : '' }}">
                <div class="auth-form">

                    <div class="eyebrow">Create an account</div>
                    <h1>Come to GIZZAM</h1>
                    <p class="subtitle">Create your account to get started.</p>

                    @if($errors->any())
                    <div class="alert error">
                        {{ $errors->first() }}
                    </div>
                    @endif

                    <form action="{{ route('register.submit') }}" method="POST" id="registerForm">
                        @csrf

                        <div class="form-group">
                            <label>Full Name</label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="Enter your full name"
                                autocomplete="name"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Email Address</label>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="you@example.com"
                                autocomplete="email"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input
                                type="password"
                                name="password"
                                placeholder="At least 8 characters"
                                minlength="8"
                                autocomplete="new-password"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input
                                type="password"
                                name="password_confirmation"
                                placeholder="Confirm your password"
                                minlength="8"
                                autocomplete="new-password"
                                required>
                        </div>

                        <button type="submit" class="auth-button" id="registerButton">
                            Create Account
                        </button>
                    </form>

                    <div class="auth-footer">
                        Already registered?
                        <a href="{{ route('login') }}">Sign in</a>
                </div>

    </div>
    </section>


    <section class="auth-side login-side {{ request()->routeIs('login') ? 'active' : '' }}" id="login">
        <div class="auth-form">

            <div class="eyebrow">Welcome back</div>
            <h1>Enter your credentials</h1>
            <p class="subtitle">Sign in to continue to your dashboard.</p>

            @if(session('success'))
            <div class="alert success">
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="alert error">
                {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" id="loginForm">
                @csrf

                <div class="form-group">
                    <label>Email Address</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="you@example.com"
                        autocomplete="username"
                        required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input
                        type="password"
                        name="password"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        required>
                </div>

                <button type="submit" class="auth-button">
                    Sign In
                </button>
            </form>

            <div class="auth-footer">
                New to GIZZAM?
                <a href="{{ route('register') }}">Create an account</a>
            </div>

        </div>
    </section>

    </main>

    <p class="copyright">
        &copy; {{ date('Y') }} GIZZAM
    </p>

    </div>

    <script>
        
        const leafLayer = document.getElementById('leafLayer');

        const leafColors = [
            '#667653',
            '#89916b',
            '#9b7950',
            '#59664b',
            '#b09a69'
        ];

        for (let i = 0; i < 28; i++) {
            const leaf = document.createElement('span');
            leaf.className = 'leaf';

            leaf.style.setProperty('--left', Math.random() * 100 + '%');
            leaf.style.setProperty('--size', (8 + Math.random() * 12) + 'px');
            leaf.style.setProperty('--duration', (10 + Math.random() * 13) + 's');
            leaf.style.setProperty('--delay', (-Math.random() * 20) + 's');
            leaf.style.setProperty('--opacity', (0.2 + Math.random() * 0.45));
            leaf.style.setProperty(
                '--leaf-color',
                leafColors[Math.floor(Math.random() * leafColors.length)]
            );

            leafLayer.appendChild(leaf);
        }

        document.getElementById('registerForm').addEventListener('submit', function() {
            if (!this.checkValidity()) return;

            const button = document.getElementById('registerButton');
            button.disabled = true;
            button.textContent = 'Creating Account...';
        });
    </script>

</body>

</html>