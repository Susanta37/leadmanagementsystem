<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KrediPal - Forgot Password</title>
     <link rel="icon" type="image/png" href="{{ asset('logo1.png') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #3b82f6;
            --secondary-color: #f97316;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
            --background-light: #ffffff;
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.08);
            --shadow-light: 0 8px 32px rgba(0, 0, 0, 0.04);
            --shadow-medium: 0 12px 40px rgba(0, 0, 0, 0.08);
            --shadow-heavy: 0 20px 60px rgba(0, 0, 0, 0.12);
            --success-color: #10b981;
            --error-color: #ef4444;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Floating Particles */
        .particles-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .particle {
            position: absolute;
            border-radius: 50%;
            animation: float-particle linear infinite;
        }

        .particle:nth-child(1) { width: 8px; height: 8px; background: rgba(59, 130, 246, 0.1); left: 10%; animation-duration: 20s; animation-delay: 0s; }
        .particle:nth-child(2) { width: 12px; height: 12px; background: rgba(249, 115, 22, 0.1); left: 20%; animation-duration: 25s; animation-delay: 2s; }
        .particle:nth-child(3) { width: 6px; height: 6px; background: rgba(139, 92, 246, 0.1); left: 30%; animation-duration: 18s; animation-delay: 4s; }
        .particle:nth-child(4) { width: 10px; height: 10px; background: rgba(59, 130, 246, 0.1); left: 40%; animation-duration: 22s; animation-delay: 1s; }
        .particle:nth-child(5) { width: 14px; height: 14px; background: rgba(249, 115, 22, 0.1); left: 50%; animation-duration: 24s; animation-delay: 3s; }
        .particle:nth-child(6) { width: 9px; height: 9px; background: rgba(139, 92, 246, 0.1); left: 60%; animation-duration: 19s; animation-delay: 5s; }
        .particle:nth-child(7) { width: 11px; height: 11px; background: rgba(59, 130, 246, 0.1); left: 70%; animation-duration: 26s; animation-delay: 2s; }
        .particle:nth-child(8) { width: 7px; height: 7px; background: rgba(249, 115, 22, 0.1); left: 80%; animation-duration: 17s; animation-delay: 4s; }
        .particle:nth-child(9) { width: 13px; height: 13px; background: rgba(139, 92, 246, 0.1); left: 90%; animation-duration: 21s; animation-delay: 1s; }
        .particle:nth-child(10) { width: 8px; height: 8px; background: rgba(59, 130, 246, 0.1); left: 15%; animation-duration: 23s; animation-delay: 6s; }
        .particle:nth-child(11) { width: 10px; height: 10px; background: rgba(249, 115, 22, 0.1); left: 25%; animation-duration: 20s; animation-delay: 3s; }
        .particle:nth-child(12) { width: 5px; height: 5px; background: rgba(139, 92, 246, 0.1); left: 35%; animation-duration: 16s; animation-delay: 5s; }
        .particle:nth-child(13) { width: 12px; height: 12px; background: rgba(59, 130, 246, 0.1); left: 45%; animation-duration: 28s; animation-delay: 2s; }
        .particle:nth-child(14) { width: 6px; height: 6px; background: rgba(249, 115, 22, 0.1); left: 55%; animation-duration: 15s; animation-delay: 4s; }
        .particle:nth-child(15) { width: 11px; height: 11px; background: rgba(139, 92, 246, 0.1); left: 65%; animation-duration: 27s; animation-delay: 1s; }

        /* Background Shapes */
        .bg-shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
            animation: float-shape ease-in-out infinite;
        }

        .bg-shape-1 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            top: -150px;
            right: -150px;
            animation-duration: 8s;
        }

        .bg-shape-2 {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, var(--secondary-color), #8b5cf6);
            bottom: -100px;
            left: -100px;
            animation-duration: 10s;
            animation-delay: 2s;
        }

        .bg-shape-3 {
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, #8b5cf6, var(--primary-color));
            top: 50%;
            right: -75px;
            animation-duration: 12s;
            animation-delay: 4s;
        }

        /* Main Container */
        .forgot-password-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 480px;
            margin: 0 auto;
            padding: 20px;
        }

        .forgot-password-card {
            background: var(--glass-bg);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            border: 1px solid var(--glass-border);
            border-radius: 32px;
            padding: 48px 40px;
            box-shadow: 
                var(--shadow-heavy),
                0 0 0 1px var(--glass-border),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            animation: slideUp 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .forgot-password-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        }

        /* Header Section */
        .header-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 32px;
        }

        .logo-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 20px;
            box-shadow: var(--shadow-medium);
            animation: pulse 2s ease-in-out infinite;
        }

        .logo-text {
            font-size: 28px;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.02em;
        }

        .logo-text .kredi {
            color: var(--primary-color);
        }

        .logo-text .pal {
            color: var(--secondary-color);
        }

        .forgot-header h1 {
            font-size: 32px;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 12px;
            letter-spacing: -0.02em;
        }

        .forgot-header p {
            color: var(--text-secondary);
            font-size: 16px;
            font-weight: 500;
            line-height: 1.6;
            max-width: 360px;
            margin: 0 auto;
        }

        /* Icon Section */
        .icon-section {
            text-align: center;
            margin-bottom: 32px;
        }

        .forgot-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(249, 115, 22, 0.1));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: bounce 2s ease-in-out infinite;
        }

        .forgot-icon i {
            font-size: 32px;
            color: var(--primary-color);
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 28px;
        }

        .form-group label {
            display: block;
            margin-bottom: 12px;
            color: var(--text-primary);
            font-weight: 600;
            font-size: 15px;
        }

        .input-wrapper {
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-control {
            width: 100%;
            padding: 18px 24px 18px 54px;
            border: 2px solid var(--border-color);
            border-radius: 16px;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.8);
            color: var(--text-primary);
            box-shadow: var(--shadow-light);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08), var(--shadow-medium);
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.95);
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
            color: var(--primary-color);
            transform: translateY(-50%) scale(1.1);
        }

        /* Button Styles */
        .btn-container {
            margin-bottom: 32px;
        }

        .reset-btn {
            width: 100%;
            padding: 20px;
            background: linear-gradient(135deg, var(--primary-color), #2563eb);
            color: white;
            border: none;
            border-radius: 16px;
            font-size: 17px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-medium);
        }

        .reset-btn:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-heavy);
        }

        .reset-btn:active {
            transform: translateY(-1px);
        }

        .reset-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Back to Login */
        .back-to-login {
            text-align: center;
            padding-top: 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .back-link {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 8px;
        }

        .back-link:hover {
            color: var(--primary-color);
            background: rgba(59, 130, 246, 0.08);
            transform: translateX(-4px);
        }

        /* Success/Error Messages */
        .message {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            border-left: 4px solid;
            font-size: 15px;
            font-weight: 500;
            animation: slideIn 0.3s ease-out;
            box-shadow: var(--shadow-light);
        }

        .success-message {
            background: rgba(16, 185, 129, 0.08);
            color: var(--success-color);
            border-left-color: var(--success-color);
        }

        .error-message {
            background: rgba(239, 68, 68, 0.08);
            color: var(--error-color);
            border-left-color: var(--error-color);
        }

        /* Loading State */
        .loading {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
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

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float-particle {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
        }

        @keyframes float-shape {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .forgot-password-container {
                padding: 16px;
            }

            .forgot-password-card {
                padding: 32px 24px;
                border-radius: 24px;
            }

            .forgot-header h1 {
                font-size: 28px;
            }

            .logo-text {
                font-size: 24px;
            }

            .logo-icon {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }
        }

        @media (max-width: 480px) {
            .forgot-password-card {
                padding: 24px 20px;
                border-radius: 20px;
            }

            .forgot-header h1 {
                font-size: 24px;
            }

            .form-control {
                padding: 16px 20px 16px 48px;
            }

            .reset-btn {
                padding: 18px;
            }
        }
    </style>
</head>
<body>
    <!-- Floating Particles -->
    <div class="particles-container">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <!-- Background Shapes -->
    <div class="bg-shape bg-shape-1"></div>
    <div class="bg-shape bg-shape-2"></div>
    <div class="bg-shape bg-shape-3"></div>

    <div class="forgot-password-container">
        <div class="forgot-password-card">
            <!-- Header -->
            <div class="header-section">
                <div class="logo">
                    <div class="logo-icon">K</div>
                    <div class="logo-text">
                        <span class="kredi">kredi</span><span class="pal">pal</span>
                    </div>
                </div>
                
                <div class="icon-section">
                    <div class="forgot-icon">
                        <i class="fas fa-key"></i>
                    </div>
                </div>

                <div class="forgot-header">
                    <h1>Forgot Password? 🔐</h1>
                    <p>{{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}</p>
                </div>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="success-message message">
                    <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
                    {{ session('status') }}
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="error-message message">
                    <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <!-- Reset Form -->
            <form method="POST" action="{{ route('password.email') }}" id="resetForm">
                @csrf

                <!-- Email Field -->
                <div class="form-group">
                    <label for="email">{{ __('Email') }}</label>
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

                <!-- Submit Button -->
                <div class="btn-container">
                    <button type="submit" class="reset-btn" id="resetBtn">
                        <span class="btn-text">{{ __('Email Password Reset Link') }}</span>
                    </button>
                </div>
            </form>

            <!-- Back to Login -->
            <div class="back-to-login">
                <a href="{{ route('login') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                    Back to Login
                </a>
            </div>
        </div>
    </div>

    <script>
        // Form handling
        const resetForm = document.getElementById('resetForm');
        const resetBtn = document.getElementById('resetBtn');
        const emailInput = document.getElementById('email');

        // Enhanced form interactions
        const inputs = document.querySelectorAll('.form-control');
        
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
                this.parentElement.style.transition = 'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });

            // Real-time validation feedback
            input.addEventListener('input', function() {
                if (this.type === 'email') {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (emailRegex.test(this.value)) {
                        this.style.borderColor = '#10b981';
                        this.style.boxShadow = '0 0 0 4px rgba(16, 185, 129, 0.08)';
                    } else if (this.value.length > 0) {
                        this.style.borderColor = '#ef4444';
                        this.style.boxShadow = '0 0 0 4px rgba(239, 68, 68, 0.08)';
                    } else {
                        this.style.borderColor = '#e5e7eb';
                        this.style.boxShadow = 'none';
                    }
                }
            });
        });

        // Form submission handling
        resetForm.addEventListener('submit', function(e) {
            const email = emailInput.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailRegex.test(email)) {
                e.preventDefault();
                showError('Please enter a valid email address');
                return;
            }

            // Show loading state
            resetBtn.disabled = true;
            resetBtn.innerHTML = `
                <span class="loading">
                    <div class="spinner"></div>
                    Sending Reset Link...
                </span>
            `;
        });

        // Enhanced ripple effect
        resetBtn.addEventListener('click', function(e) {
            if (!this.disabled) {
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
                ripple.style.background = 'rgba(255, 255, 255, 0.3)';
                ripple.style.transform = 'scale(0)';
                ripple.style.animation = 'ripple 0.6s linear';
                ripple.style.pointerEvents = 'none';
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            }
        });

        // Error display function
        function showError(message) {
            // Remove existing error messages
            const existingError = document.querySelector('.error-message');
            if (existingError) {
                existingError.remove();
            }

            // Create new error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message message';
            errorDiv.innerHTML = `
                <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
                ${message}
            `;

            // Insert before form
            resetForm.parentNode.insertBefore(errorDiv, resetForm);

            // Auto remove after 5 seconds
            setTimeout(() => {
                errorDiv.remove();
            }, 5000);
        }

        // Auto-hide messages after 10 seconds
        const messages = document.querySelectorAll('.message');
        messages.forEach(message => {
            setTimeout(() => {
                message.style.opacity = '0';
                message.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    message.remove();
                }, 300);
            }, 10000);
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Enter key to submit form
            if (e.key === 'Enter' && document.activeElement === emailInput) {
                resetForm.dispatchEvent(new Event('submit'));
            }
            
            // Escape key to go back to login
            if (e.key === 'Escape') {
                window.location.href = "{{ route('login') }}";
            }
        });

        // Email input auto-formatting
        emailInput.addEventListener('input', function() {
            // Remove spaces
            this.value = this.value.replace(/\s/g, '');
            
            // Convert to lowercase
            this.value = this.value.toLowerCase();
        });

        // Focus management
        window.addEventListener('load', function() {
            emailInput.focus();
        });

        // Prevent form resubmission on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }

        // Add smooth scrolling for mobile
        if (window.innerWidth <= 768) {
            document.body.style.overflow = 'auto';
        }
    </script>
</body>
</html>
