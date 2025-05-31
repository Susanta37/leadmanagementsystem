<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KrediPal - Login</title>
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
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--background-light);
            min-height: 100vh;
            display: flex;
            overflow: hidden;
        }

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

        .particle:nth-child(1) { width: 6px; height: 6px; background: rgba(8, 103, 255, 0.08); left: 10%; animation-duration: 18s; animation-delay: 0s; }
        .particle:nth-child(2) { width: 10px; height: 10px; background: rgba(255, 132, 45, 0.08); left: 20%; animation-duration: 22s; animation-delay: 2s; }
        .particle:nth-child(3) { width: 4px; height: 4px; background: rgba(139, 92, 246, 0.08); left: 30%; animation-duration: 15s; animation-delay: 4s; }
        .particle:nth-child(4) { width: 8px; height: 8px; background: rgba(59, 130, 246, 0.08); left: 40%; animation-duration: 25s; animation-delay: 1s; }
        .particle:nth-child(5) { width: 12px; height: 12px; background: rgba(249, 115, 22, 0.08); left: 50%; animation-duration: 20s; animation-delay: 3s; }
        .particle:nth-child(6) { width: 5px; height: 5px; background: rgba(139, 92, 246, 0.08); left: 60%; animation-duration: 17s; animation-delay: 5s; }
        .particle:nth-child(7) { width: 9px; height: 9px; background: rgba(59, 130, 246, 0.08); left: 70%; animation-duration: 24s; animation-delay: 2s; }
        .particle:nth-child(8) { width: 7px; height: 7px; background: rgba(249, 115, 22, 0.08); left: 80%; animation-duration: 16s; animation-delay: 4s; }
        .particle:nth-child(9) { width: 11px; height: 11px; background: rgba(139, 92, 246, 0.08); left: 90%; animation-duration: 21s; animation-delay: 1s; }
        .particle:nth-child(10) { width: 6px; height: 6px; background: rgba(59, 130, 246, 0.08); left: 15%; animation-duration: 19s; animation-delay: 6s; }
        .particle:nth-child(11) { width: 8px; height: 8px; background: rgba(249, 115, 22, 0.08); left: 25%; animation-duration: 23s; animation-delay: 3s; }
        .particle:nth-child(12) { width: 4px; height: 4px; background: rgba(139, 92, 246, 0.08); left: 35%; animation-duration: 14s; animation-delay: 5s; }
        .particle:nth-child(13) { width: 10px; height: 10px; background: rgba(59, 130, 246, 0.08); left: 45%; animation-duration: 26s; animation-delay: 2s; }
        .particle:nth-child(14) { width: 5px; height: 5px; background: rgba(249, 115, 22, 0.08); left: 55%; animation-duration: 18s; animation-delay: 4s; }
        .particle:nth-child(15) { width: 9px; height: 9px; background: rgba(139, 92, 246, 0.08); left: 65%; animation-duration: 22s; animation-delay: 1s; }
        .particle:nth-child(16) { width: 7px; height: 7px; background: rgba(59, 130, 246, 0.08); left: 75%; animation-duration: 17s; animation-delay: 3s; }
        .particle:nth-child(17) { width: 12px; height: 12px; background: rgba(249, 115, 22, 0.08); left: 85%; animation-duration: 25s; animation-delay: 5s; }
        .particle:nth-child(18) { width: 6px; height: 6px; background: rgba(139, 92, 246, 0.08); left: 95%; animation-duration: 20s; animation-delay: 2s; }
        .particle:nth-child(19) { width: 8px; height: 8px; background: rgba(59, 130, 246, 0.08); left: 5%; animation-duration: 24s; animation-delay: 4s; }
        .particle:nth-child(20) { width: 11px; height: 11px; background: rgba(249, 115, 22, 0.08); left: 12%; animation-duration: 21s; animation-delay: 1s; }

        .login-wrapper {
            display: flex;
            width: 100%;
            height: 100vh;
            position: relative;
            z-index: 1;
        }

        .login-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: var(--background-light);
            position: relative;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            animation: slideInLeft 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .logo-section {
            margin-bottom: 48px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px;
        }

        .logo-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 18px;
            box-shadow: var(--shadow-medium);
        }

        .logo-text {
            font-size: 26px;
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

        .login-header h1 {
            font-size: 36px;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 12px;
            letter-spacing: -0.02em;
        }

        .login-header p {
            color: var(--text-secondary);
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 40px;
            line-height: 1.5;
        }

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
            background: var(--background-light);
            color: var(--text-primary);
            box-shadow: var(--shadow-light);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08), var(--shadow-medium);
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
            color: var(--primary-color);
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
            padding: 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .password-toggle:hover {
            color: var(--primary-color);
            background: rgba(59, 130, 246, 0.08);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 36px;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary-color);
            cursor: pointer;
        }

        .checkbox-wrapper label {
            color: var(--text-secondary);
            font-size: 15px;
            font-weight: 500;
            margin: 0;
            cursor: pointer;
        }

        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
            padding: 6px 12px;
            border-radius: 8px;
        }

        .forgot-password:hover {
            color: #2563eb;
            background: rgba(59, 130, 246, 0.08);
        }

        .login-btn {
            width: 100%;
            padding: 20px;
            background: linear-gradient(135deg, var(--secondary-color), #f7931e);
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

        .login-btn:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-heavy);
        }

        .login-btn:active {
            transform: translateY(-1px);
        }

        .testimonial-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: var(--background-light);
            overflow: hidden;
        }

        .testimonial-particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .t-particle {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.06), rgba(139, 92, 246, 0.06));
            animation: float-t-particle ease-in-out infinite;
        }

        .t-particle:nth-child(1) { width: 20px; height: 20px; top: 10%; left: 10%; animation-duration: 25s; }
        .t-particle:nth-child(2) { width: 30px; height: 30px; top: 20%; left: 20%; animation-duration: 30s; }
        .t-particle:nth-child(3) { width: 15px; height: 15px; top: 30%; left: 30%; animation-duration: 20s; }
        .t-particle:nth-child(4) { width: 35px; height: 35px; top: 40%; left: 40%; animation-duration: 35s; }
        .t-particle:nth-child(5) { width: 18px; height: 18px; top: 50%; left: 50%; animation-duration: 22s; }
        .t-particle:nth-child(6) { width: 25px; height: 25px; top: 60%; left: 60%; animation-duration: 28s; }
        .t-particle:nth-child(7) { width: 22px; height: 22px; top: 70%; left: 70%; animation-duration: 26s; }
        .t-particle:nth-child(8) { width: 28px; height: 28px; top: 80%; left: 80%; animation-duration: 32s; }
        .t-particle:nth-child(9) { width: 16px; height: 16px; top: 15%; left: 75%; animation-duration: 24s; }
        .t-particle:nth-child(10) { width: 32px; height: 32px; top: 25%; left: 85%; animation-duration: 34s; }

        .testimonial-container {
            position: relative;
            width: 100%;
            max-width: 500px;
            height: 600px;
            z-index: 2;
        }

        .testimonial-card {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--glass-bg);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            border: 1px solid var(--glass-border);
            border-radius: 32px;
            padding: 50px 40px;
            text-align: center;
            opacity: 0;
            transform: translateX(100px);
            transition: all 1s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 
                var(--shadow-heavy),
                0 0 0 1px var(--glass-border),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .testimonial-card.active {
            opacity: 1;
            transform: translateX(0);
        }

        .testimonial-card.prev {
            opacity: 0;
            transform: translateX(-100px);
        }

        .profile-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 32px;
            border: 4px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            box-shadow: var(--shadow-medium);
            background: #f3f4f6;
            transition: all 0.3s ease;
        }

        .profile-photo:hover {
            transform: scale(1.05);
        }

        .profile-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .quote-icon {
            font-size: 56px;
            color: var(--primary-color);
            margin-bottom: 32px;
            opacity: 0.8;
        }

        .testimonial-text {
            font-size: 20px;
            line-height: 1.7;
            color: var(--text-primary);
            margin-bottom: 32px;
            font-weight: 500;
        }

        .testimonial-author {
            margin-bottom: 8px;
        }

        .author-name {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 8px;
        }

        .author-title {
            font-size: 15px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .testimonial-dots {
            position: absolute;
            bottom: -80px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 16px;
        }

        .dot {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0.6;
            background: #f3f4f6;
        }

        .dot.active {
            opacity: 1;
            border-color: var(--primary-color);
            transform: scale(1.15);
            box-shadow: var(--shadow-medium);
        }

        .dot:hover {
            transform: scale(1.1);
        }

        .dot img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .blue-shape {
            position: absolute;
            bottom: -80px;
            right: -120px;
            width: 350px;
            height: 350px;
            background: linear-gradient(135deg, var(--primary-color), #1d4ed8);
            border-radius: 50% 50% 0 50%;
            opacity: 0.6;
            animation: shapeFloat 8s ease-in-out infinite;
            z-index: 1;
        }

        .error-message, .success-message {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            border-left: 4px solid;
            font-size: 15px;
            font-weight: 500;
            animation: slideIn 0.3s ease-out;
            box-shadow: var(--shadow-light);
        }

        .error-message {
            background: rgba(239, 68, 68, 0.08);
            color: #dc2626;
            border-left-color: #dc2626;
        }

        .success-message {
            background: rgba(16, 185, 129, 0.08);
            color: #059669;
            border-left-color: #059669;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-60px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
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

        @keyframes float-t-particle {
            0% {
                transform: translate(0, 0) rotate(0deg);
            }
            25% {
                transform: translate(30px, 30px) rotate(90deg);
            }
            50% {
                transform: translate(0, 60px) rotate(180deg);
            }
            75% {
                transform: translate(-30px, 30px) rotate(270deg);
            }
            100% {
                transform: translate(0, 0) rotate(360deg);
            }
        }

        @keyframes shapeFloat {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-30px) rotate(8deg);
            }
        }

        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        @media (max-width: 1024px) {
            .login-wrapper {
                flex-direction: column;
            }
            
            .login-section,
            .testimonial-section {
                flex: none;
                min-height: 50vh;
            }
            
            .testimonial-container {
                max-width: 400px;
                height: 500px;
            }
        }

        @media (max-width: 768px) {
            .login-section {
                padding: 24px;
            }
            
            .testimonial-section {
                display: none;
            }
            
            .login-section {
                min-height: 100vh;
            }
            
            .login-container {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
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
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="login-wrapper">
        <div class="login-section">
            <div class="login-container">
                <div class="logo-section">
                  

                      <img src="{{ asset('logo.png') }}" alt="KrediPal Logo" style="width: 200px; height: auto; margin-bottom: 20px;">
                    
                    <hr style="border: 1px solid #e5e7eb; margin-bottom: 20px;">
                    <div class="login-header">
                        <h1>Login 👋</h1>
                        <p>Welcome back! Please enter your details</p>
                    </div>
                </div>

                @if (session('status'))
                    <div class="success-message">
                        <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="error-message">
                        <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email</label>
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
                                placeholder="Enter your email"
                            >
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                    </div>

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
                                placeholder="••••••••"
                            >
                            <i class="fas fa-lock input-icon"></i>
                            <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                        </div>
                    </div>

                    <div class="form-options">
                        <div class="checkbox-wrapper">
                            <input type="checkbox" id="remember_me" name="remember">
                            <label for="remember_me">Remember for 30 days</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-password">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="login-btn">
                        Login
                    </button>
                </form>
            </div>
        </div>

        <div class="testimonial-section">
            <div class="testimonial-particles">
                <div class="t-particle"></div>
                <div class="t-particle"></div>
                <div class="t-particle"></div>
                <div class="t-particle"></div>
                <div class="t-particle"></div>
                <div class="t-particle"></div>
                <div class="t-particle"></div>
                <div class="t-particle"></div>
                <div class="t-particle"></div>
                <div class="t-particle"></div>
            </div>
            
            <div class="testimonial-container">
                <div class="testimonial-card" data-index="0">
                    <div class="profile-photo">
                        <img src="https://cdn.i.haymarketmedia.asia/?n=campaign-india%2Fcontent%2F20241010074955_Untitled+design+(2).jpg" alt="Ratan Tata">
                    </div>
                    <div class="quote-icon">❝</div>
                    <div class="testimonial-text">
                        You can decide how you are working as a team first and then let iTLogistics work around you. 😊
                    </div>
                    <div class="testimonial-author">
                        <div class="author-name">Ratan Tata</div>
                        <div class="author-title">Chairman Emeritus of Tata Sons</div>
                    </div>
                </div>

                <div class="testimonial-card" data-index="1">
                    <div class="profile-photo">
                        <img src="https://imageio.forbes.com/specials-images/imageserve/5c7d7829a7ea434b351ba0b6/0x0.jpg" alt="Mukesh Ambani">
                    </div>
                    <div class="quote-icon">❝</div>
                    <div class="testimonial-text">
                        Innovation distinguishes between a leader and a follower. This platform embodies that spirit perfectly. 🚀
                    </div>
                    <div class="testimonial-author">
                        <div class="author-name">Mukesh Ambani</div>
                        <div class="author-title">Chairman of Reliance Industries</div>
                    </div>
                </div>

                <div class="testimonial-card" data-index="2">
                    <div class="profile-photo">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/1/11/Narayana_Murthy_CIF_%28cropped%29.JPG" alt="N.R. Narayana Murthy">
                    </div>
                    <div class="quote-icon">❝</div>
                    <div class="testimonial-text">
                        The best way to predict the future is to create it. This tool helps businesses do exactly that. 💪
                    </div>
                    <div class="testimonial-author">
                        <div class="author-name">N.R. Narayana Murthy</div>
                        <div class="author-title">Co-founder of Infosys</div>
                    </div>
                </div>

                <div class="testimonial-card" data-index="3">
                    <div class="profile-photo">
                        <img src="https://cdn.britannica.com/74/221774-050-68B15E6F/Indian-businessman-Azim-Premji-2013.jpg" alt="Azim Premji">
                    </div>
                    <div class="quote-icon">❝</div>
                    <div class="testimonial-text">
                        Success is not just about what you accomplish, but what you inspire others to do. This platform inspires excellence. ✨
                    </div>
                    <div class="testimonial-author">
                        <div class="author-name">Azim Premji</div>
                        <div class="author-title">Founder Chairman of Wipro</div>
                    </div>
                </div>

                <div class="testimonial-dots">
                    <div class="dot" data-index="0">
                        <img src="https://cdn.i.haymarketmedia.asia/?n=campaign-india%2Fcontent%2F20241010074955_Untitled+design+(2).jpg" alt="Ratan">
                    </div>
                    <div class="dot" data-index="1">
                        <img src="https://imageio.forbes.com/specials-images/imageserve/5c7d7829a7ea434b351ba0b6/0x0.jpg" alt="Mukesh">
                    </div>
                    <div class="dot" data-index="2">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/1/11/Narayana_Murthy_CIF_%28cropped%29.JPG" alt="Narayana">
                    </div>
                    <div class="dot" data-index="3">
                        <img src="https://cdn.britannica.com/74/221774-050-68B15E6F/Indian-businessman-Azim-Premji-2013.jpg" alt="Azim">
                    </div>
                </div>
            </div>

            <div class="blue-shape"></div>
        </div>
    </div>

    <script>
        // Password toggle functionality
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // Testimonial slider functionality
        let currentTestimonial = 0;
        const testimonials = document.querySelectorAll('.testimonial-card');
        const dots = document.querySelectorAll('.dot');
        const totalTestimonials = testimonials.length;

        function showTestimonial(index) {
            testimonials.forEach((testimonial, i) => {
                testimonial.classList.remove('active', 'prev');
                if (i === index) {
                    testimonial.classList.add('active');
                } else if (i < index) {
                    testimonial.classList.add('prev');
                }
            });

            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === index);
            });

            currentTestimonial = index;
        }

        function nextTestimonial() {
            const next = (currentTestimonial + 1) % totalTestimonials;
            showTestimonial(next);
        }

        // Initialize with random testimonial on page load
        document.addEventListener('DOMContentLoaded', () => {
            const randomIndex = Math.floor(Math.random() * totalTestimonials);
            showTestimonial(randomIndex);
        });

        // Auto-slide testimonials
        setInterval(nextTestimonial, 5000);

        // Dot click handlers
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                showTestimonial(index);
            });
        });

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

            input.addEventListener('input', function() {
                if (this.value.length > 0) {
                    this.style.borderColor = '#10b981';
                    this.style.boxShadow = '0 0 0 4px rgba(16, 185, 129, 0.08)';
                } else {
                    this.style.borderColor = '#e5e7eb';
                    this.style.boxShadow = 'none';
                }
            });
        });

        // Form submission handling
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.querySelector('.login-btn');

        loginForm.addEventListener('submit', function(e) {
            loginBtn.style.opacity = '0.8';
            loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
        });

        // Enhanced ripple effect
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
            ripple.style.background = 'rgba(255, 255, 255, 0.3)';
            ripple.style.transform = 'scale(0)';
            ripple.style.animation = 'ripple 0.6s linear';
            ripple.style.pointerEvents = 'none';
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });

        // Smooth scroll prevention for better UX
        document.addEventListener('wheel', function(e) {
            if (e.deltaY > 0) {
                e.preventDefault();
            }
        }, { passive: false });
    </script>
</body>
</html>