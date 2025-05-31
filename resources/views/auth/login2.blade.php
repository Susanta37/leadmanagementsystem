<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lead Management System - Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Modern Background Elements */
        .background-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .bg-shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
            animation: float 8s ease-in-out infinite;
        }

        .bg-shape-1 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            top: -150px;
            right: -150px;
            animation-delay: 0s;
        }

        .bg-shape-2 {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, #f97316, #ea580c);
            bottom: -100px;
            left: -100px;
            animation-delay: 2s;
        }

        .bg-shape-3 {
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, #3b82f6, #f97316);
            top: 20%;
            left: 10%;
            animation-delay: 4s;
        }

        .geometric-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(45deg, rgba(59, 130, 246, 0.03) 25%, transparent 25%),
                linear-gradient(-45deg, rgba(59, 130, 246, 0.03) 25%, transparent 25%),
                linear-gradient(45deg, transparent 75%, rgba(249, 115, 22, 0.03) 75%),
                linear-gradient(-45deg, transparent 75%, rgba(249, 115, 22, 0.03) 75%);
            background-size: 60px 60px;
            background-position: 0 0, 0 30px, 30px -30px, -30px 0px;
            animation: patternMove 20s linear infinite;
        }

        .wave-pattern {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 200px;
            background: linear-gradient(180deg, transparent 0%, rgba(59, 130, 246, 0.05) 100%);
            clip-path: polygon(0 50%, 100% 80%, 100% 100%, 0% 100%);
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.08),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            padding: 48px;
            width: 100%;
            max-width: 480px;
            animation: slideUp 0.8s ease-out;
            position: relative;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3b82f6, #f97316);
            animation: shimmer 3s infinite;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 40px;
            animation: fadeInDown 1s ease-out;
        }

        .logo {
            width: 88px;
            height: 88px;
            background: linear-gradient(135deg, #3b82f6, #f97316);
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            animation: logoFloat 3s ease-in-out infinite;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.2);
        }

        .logo i {
            font-size: 36px;
            color: white;
        }

        .logo-section h1 {
            color: #1f2937;
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .logo-section p {
            color: #6b7280;
            font-size: 16px;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 28px;
            animation: slideInLeft 0.6s ease-out;
            animation-fill-mode: both;
        }

        .form-group:nth-child(1) { animation-delay: 0.2s; }
        .form-group:nth-child(2) { animation-delay: 0.4s; }
        .form-group:nth-child(3) { animation-delay: 0.6s; }
        .form-group:nth-child(4) { animation-delay: 0.8s; }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: #374151;
            font-weight: 600;
            font-size: 15px;
        }

        .input-wrapper {
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 16px 20px 16px 56px;
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            font-size: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #ffffff;
            color: #1f2937;
            font-weight: 500;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            transform: translateY(-2px);
        }

        .form-control::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }

        .input-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .form-control:focus + .input-icon {
            color: #3b82f6;
            transform: translateY(-50%) scale(1.1);
        }

        .password-toggle {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s ease;
            padding: 4px;
            border-radius: 8px;
        }

        .password-toggle:hover {
            color: #f97316;
            background: rgba(249, 115, 22, 0.1);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-right: 12px;
            accent-color: #3b82f6;
            cursor: pointer;
        }

        .checkbox-wrapper label {
            color: #6b7280;
            font-size: 15px;
            margin: 0;
            cursor: pointer;
            font-weight: 500;
        }

        .forgot-password {
            color: #f97316;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
            padding: 4px 8px;
            border-radius: 8px;
        }

        .forgot-password:hover {
            color: #ea580c;
            background: rgba(249, 115, 22, 0.1);
        }

        .login-btn {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            border: none;
            border-radius: 16px;
            font-size: 17px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
        }

        .login-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(59, 130, 246, 0.4);
            background: linear-gradient(135deg, #2563eb, #1e40af);
        }

        .login-btn:active {
            transform: translateY(-1px);
        }

        .login-btn.loading {
            pointer-events: none;
            background: linear-gradient(135deg, #f97316, #ea580c);
        }

        .btn-text {
            transition: opacity 0.3s ease;
        }

        .btn-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .login-btn.loading .btn-text {
            opacity: 0;
        }

        .login-btn.loading .btn-spinner {
            opacity: 1;
        }

        .error-message {
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
            color: #dc2626;
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            border-left: 4px solid #dc2626;
            animation: shake 0.5s ease-in-out;
            font-weight: 500;
        }

        .success-message {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            color: #16a34a;
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            border-left: 4px solid #16a34a;
            animation: slideInDown 0.5s ease-out;
            font-weight: 500;
        }

        .spinner {
            width: 24px;
            height: 24px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        /* Animations */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(60px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-40px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes logoFloat {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-8px) rotate(2deg);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -200px 0;
            }
            100% {
                background-position: 200px 0;
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            33% {
                transform: translateY(-30px) rotate(120deg);
            }
            66% {
                transform: translateY(15px) rotate(240deg);
            }
        }

        @keyframes patternMove {
            0% {
                background-position: 0 0, 0 30px, 30px -30px, -30px 0px;
            }
            100% {
                background-position: 60px 60px, 60px 90px, 90px 30px, 30px 60px;
            }
        }

        @keyframes shake {
            0%, 100% {
                transform: translateX(0);
            }
            25% {
                transform: translateX(-8px);
            }
            75% {
                transform: translateX(8px);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                padding: 32px 24px;
                margin: 16px;
                border-radius: 20px;
            }
            
            .logo-section h1 {
                font-size: 28px;
            }

            .logo {
                width: 76px;
                height: 76px;
            }

            .logo i {
                font-size: 32px;
            }
        }

        /* Additional modern touches */
        .input-wrapper:hover .form-control {
            border-color: #d1d5db;
        }

        .form-control:valid {
            border-color: #10b981;
        }

        .form-control:valid + .input-icon {
            color: #10b981;
        }
    </style>
</head>
<body>
    <!-- Modern Background Elements -->
    <div class="background-elements">
        <div class="geometric-pattern"></div>
        <div class="wave-pattern"></div>
        <div class="bg-shape bg-shape-1"></div>
        <div class="bg-shape bg-shape-2"></div>
        <div class="bg-shape bg-shape-3"></div>
    </div>

    <div class="login-container">
        <!-- Logo Section -->
        <div class="logo-section">
            <div class="logo">
                <i class="fas fa-chart-line"></i>
            </div>
            <h1>Lead Management</h1>
            <p>Welcome back! Please sign in to continue</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="success-message">
                <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
                {{ session('status') }}
            </div>
        @endif

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="error-message">
                <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            <!-- Email Field -->
            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-wrapper">
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-control" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                        autocomplete="username"
                        placeholder="Enter your email address"
                    >
                    <i class="fas fa-envelope input-icon"></i>
                </div>
            </div>

            <!-- Password Field -->
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control" 
                        required 
                        autocomplete="current-password"
                        placeholder="Enter your password"
                    >
                    <i class="fas fa-lock input-icon"></i>
                    <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                </div>
            </div>

            <!-- Form Options -->
            <div class="form-options">
                <div class="checkbox-wrapper">
                    <input type="checkbox" id="remember_me" name="remember">
                    <label for="remember_me">Remember me</label>
                </div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-password">
                        Forgot Password?
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <button type="submit" class="login-btn" id="loginBtn">
                <span class="btn-text">
                    <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i>
                    Sign In
                </span>
                <div class="btn-spinner">
                    <div class="spinner"></div>
                </div>
            </button>
        </form>
    </div>

    <script>
        // Password toggle functionality
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle icon
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // Form submission with loading state
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');

        loginForm.addEventListener('submit', function(e) {
            // Add loading state
            loginBtn.classList.add('loading');
        });

        // Enhanced input interactions
        const inputs = document.querySelectorAll('.form-control');
        
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
                this.parentElement.style.transition = 'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });

            // Add typing animation
            input.addEventListener('input', function() {
                if (this.value.length > 0) {
                    this.style.borderColor = '#10b981';
                } else {
                    this.style.borderColor = '#e5e7eb';
                }
            });
        });

        // Add ripple effect to button
        loginBtn.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.background = 'rgba(255, 255, 255, 0.4)';
            ripple.style.transform = 'scale(0)';
            ripple.style.animation = 'ripple 0.6s linear';
            ripple.style.pointerEvents = 'none';
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });

        // Add ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);

        // Smooth scroll and intersection observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateX(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.form-group').forEach(group => {
            observer.observe(group);
        });
    </script>
</body>
</html>