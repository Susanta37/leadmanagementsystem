<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Lead Management System</title>
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
            color: #1f2937;
            line-height: 1.6;
        }

        .main-content {
            margin-left: 280px;
            margin-top: 80px;
            min-height: calc(100vh - 80px);
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar.collapsed + .main-content {
            margin-left: 80px;
        }

        .dashboard-container {
            padding: 32px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .dashboard-header {
            margin-bottom: 32px;
            animation: fadeInDown 0.6s ease-out;
        }

        .dashboard-title {
            font-size: 32px;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .dashboard-subtitle {
            font-size: 16px;
            color: #6b7280;
            font-weight: 500;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid #f3f4f6;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
            animation-fill-mode: both;
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #f97316, #ea580c);
        }

        .stat-card.blue::before {
            background: linear-gradient(90deg, #3b82f6, #1d4ed8);
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            background: linear-gradient(135deg, #f97316, #ea580c);
        }

        .stat-card.blue .stat-icon {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 20px;
        }

        .stat-trend.up {
            background: #dcfce7;
            color: #16a34a;
        }

        .stat-trend.down {
            background: #fee2e2;
            color: #dc2626;
        }

        .stat-value {
            font-size: 36px;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 4px;
            line-height: 1;
        }

        .stat-label {
            font-size: 14px;
            color: #6b7280;
            font-weight: 600;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 32px;
            margin-bottom: 32px;
        }

        .chart-card {
            background: white;
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid #f3f4f6;
            animation: slideInLeft 0.8s ease-out;
        }

        .activity-card {
            background: white;
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid #f3f4f6;
            animation: slideInRight 0.8s ease-out;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
        }

        .card-action {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            color: #6b7280;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .card-action:hover {
            background: #f97316;
            border-color: #f97316;
            color: white;
        }

        .chart-placeholder {
            height: 300px;
            background: linear-gradient(135deg, #f9fafb, #f3f4f6);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 16px;
            font-weight: 500;
        }

        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px;
            background: #f9fafb;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .activity-item:hover {
            background: #f3f4f6;
            transform: translateX(4px);
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: white;
            background: linear-gradient(135deg, #f97316, #ea580c);
            flex-shrink: 0;
        }

        .activity-icon.blue {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .activity-description {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .activity-time {
            font-size: 12px;
            color: #9ca3af;
            font-weight: 500;
        }

        .quick-actions-section {
            margin-bottom: 32px;
        }

        .section-title {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 20px;
            animation: fadeInUp 0.6s ease-out;
        }

        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .quick-action-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid #f3f4f6;
            transition: all 0.3s ease;
            cursor: pointer;
            animation: slideUp 0.6s ease-out;
            animation-fill-mode: both;
        }

        .quick-action-card:nth-child(1) { animation-delay: 0.1s; }
        .quick-action-card:nth-child(2) { animation-delay: 0.2s; }
        .quick-action-card:nth-child(3) { animation-delay: 0.3s; }
        .quick-action-card:nth-child(4) { animation-delay: 0.4s; }

        .quick-action-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .quick-action-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            background: linear-gradient(135deg, #f97316, #ea580c);
            margin: 0 auto 16px;
        }

        .quick-action-card:nth-child(even) .quick-action-icon {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .quick-action-title {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .quick-action-description {
            font-size: 14px;
            color: #6b7280;
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
            
            .dashboard-container {
                padding: 20px;
            }
            
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .quick-actions-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }
        }
    </style>
</head>
<body>
    @include('TeamLead.Components.sidebar')
    
    <div class="main-content">
        @include('TeamLead.Components.header', ['title' => 'Profile Settings', 'subtitle' => 'Manage your account information'])
        
        <div class="dashboard-container">
            <!-- Profile Settings Content -->
            <div class="profile-settings-wrapper">
                <!-- Profile Information Section -->
                <div class="profile-section" data-aos="fade-up" data-aos-delay="100">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="section-title">
                            <h2>{{ __('Profile Information') }}</h2>
                            <p>{{ __("Update your account's profile information and email address.") }}</p>
                        </div>
                    </div>

                    <!-- Hidden verification form -->
                    <form id="send-verification" method="post" action="{{ route('verification.send') }}" style="display: none;">
                        @csrf
                    </form>

                    <!-- Profile Update Form -->
                    <form method="post" action="{{ route('profile.update') }}" id="profileForm" class="profile-form">
                        @csrf
                        @method('patch')

                        <div class="form-grid">
                            <!-- Name Field -->
                            <div class="form-group">
                                <label for="name" class="form-label">{{ __('Full Name') }}</label>
                                <div class="input-wrapper">
                                    <input 
                                        type="text" 
                                        id="name" 
                                        name="name" 
                                        class="form-control" 
                                        value="{{ old('name', $user->name) }}" 
                                        required 
                                        autofocus 
                                        autocomplete="name"
                                        placeholder="Enter your full name"
                                    >
                                    <i class="fas fa-user input-icon"></i>
                                </div>
                                @if ($errors->get('name'))
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ $errors->get('name')[0] }}
                                    </div>
                                @endif
                            </div>

                            <!-- Email Field -->
                            <div class="form-group">
                                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                <div class="input-wrapper">
                                    <input 
                                        type="email" 
                                        id="email" 
                                        name="email" 
                                        class="form-control" 
                                        value="{{ old('email', $user->email) }}" 
                                        required 
                                        autocomplete="username"
                                        placeholder="Enter your email address"
                                    >
                                    <i class="fas fa-envelope input-icon"></i>
                                </div>
                                @if ($errors->get('email'))
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ $errors->get('email')[0] }}
                                    </div>
                                @endif

                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                    <div class="verification-notice">
                                        <div class="verification-content">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <div>
                                                <p class="verification-text">{{ __('Your email address is unverified.') }}</p>
                                                <button type="button" class="verification-link" onclick="document.getElementById('send-verification').submit();">
                                                    {{ __('Click here to re-send the verification email.') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    @if (session('status') === 'verification-link-sent')
                                        <div class="success-message">
                                            <i class="fas fa-check-circle"></i>
                                            {{ __('A new verification link has been sent to your email address.') }}
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" id="saveProfileBtn">
                                <i class="fas fa-save"></i>
                                <span class="btn-text">{{ __('Save Changes') }}</span>
                            </button>

                            @if (session('status') === 'profile-updated')
                                <div class="success-message success-animation" id="profileSavedMessage">
                                    <i class="fas fa-check-circle"></i>
                                    {{ __('Profile updated successfully!') }}
                                </div>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Password Update Section -->
                <div class="profile-section" data-aos="fade-up" data-aos-delay="200">
                    <div class="section-header">
                        <div class="section-icon password">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="section-title">
                            <h2>{{ __('Update Password') }}</h2>
                            <p>{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
                        </div>
                    </div>

                    <!-- Password Update Form -->
                    <form method="post" action="{{ route('password.update') }}" id="passwordForm" class="profile-form">
                        @csrf
                        @method('put')

                        <div class="form-grid">
                            <!-- Current Password -->
                            <div class="form-group">
                                <label for="update_password_current_password" class="form-label">{{ __('Current Password') }}</label>
                                <div class="input-wrapper">
                                    <input 
                                        type="password" 
                                        id="update_password_current_password" 
                                        name="current_password" 
                                        class="form-control" 
                                        autocomplete="current-password"
                                        placeholder="Enter your current password"
                                    >
                                    <i class="fas fa-lock input-icon"></i>
                                    <i class="fas fa-eye password-toggle" onclick="togglePassword('update_password_current_password')"></i>
                                </div>
                                @if ($errors->updatePassword->get('current_password'))
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ $errors->updatePassword->get('current_password')[0] }}
                                    </div>
                                @endif
                            </div>

                            <!-- New Password -->
                            <div class="form-group">
                                <label for="update_password_password" class="form-label">{{ __('New Password') }}</label>
                                <div class="input-wrapper">
                                    <input 
                                        type="password" 
                                        id="update_password_password" 
                                        name="password" 
                                        class="form-control" 
                                        autocomplete="new-password"
                                        placeholder="Enter your new password"
                                    >
                                    <i class="fas fa-key input-icon"></i>
                                    <i class="fas fa-eye password-toggle" onclick="togglePassword('update_password_password')"></i>
                                </div>
                                <div class="password-strength" id="passwordStrength">
                                    <div class="strength-bar">
                                        <div class="strength-fill" id="strengthFill"></div>
                                    </div>
                                    <div class="strength-text" id="strengthText">Password strength</div>
                                </div>
                                @if ($errors->updatePassword->get('password'))
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ $errors->updatePassword->get('password')[0] }}
                                    </div>
                                @endif
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-group">
                                <label for="update_password_password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                                <div class="input-wrapper">
                                    <input 
                                        type="password" 
                                        id="update_password_password_confirmation" 
                                        name="password_confirmation" 
                                        class="form-control" 
                                        autocomplete="new-password"
                                        placeholder="Confirm your new password"
                                    >
                                    <i class="fas fa-shield-alt input-icon"></i>
                                    <i class="fas fa-eye password-toggle" onclick="togglePassword('update_password_password_confirmation')"></i>
                                </div>
                                @if ($errors->updatePassword->get('password_confirmation'))
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ $errors->updatePassword->get('password_confirmation')[0] }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" id="savePasswordBtn">
                                <i class="fas fa-shield-alt"></i>
                                <span class="btn-text">{{ __('Update Password') }}</span>
                            </button>

                            @if (session('status') === 'password-updated')
                                <div class="success-message success-animation" id="passwordSavedMessage">
                                    <i class="fas fa-check-circle"></i>
                                    {{ __('Password updated successfully!') }}
                                </div>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Delete Account Section -->
                <div class="profile-section danger" data-aos="fade-up" data-aos-delay="300">
                    <div class="section-header">
                        <div class="section-icon danger">
                            <i class="fas fa-trash-alt"></i>
                        </div>
                        <div class="section-title">
                            <h2>{{ __('Delete Account') }}</h2>
                            <p>{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}</p>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-danger" onclick="openDeleteModal()">
                            <i class="fas fa-trash-alt"></i>
                            <span class="btn-text">{{ __('Delete Account') }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Delete Account Modal -->
            <div class="modal-overlay" id="deleteModal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">{{ __('Are you sure you want to delete your account?') }}</h2>
                        <button class="modal-close" onclick="closeDeleteModal()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="modal-body">
                        <p class="modal-description">
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                        </p>

                        <form method="post" action="{{ route('profile.destroy') }}" id="deleteForm">
                            @csrf
                            @method('delete')

                            <div class="form-group">
                                <label for="delete_password" class="form-label">{{ __('Password') }}</label>
                                <div class="input-wrapper">
                                    <input 
                                        type="password" 
                                        id="delete_password" 
                                        name="password" 
                                        class="form-control" 
                                        placeholder="{{ __('Enter your password') }}"
                                        required
                                    >
                                    <i class="fas fa-lock input-icon"></i>
                                    <i class="fas fa-eye password-toggle" onclick="togglePassword('delete_password')"></i>
                                </div>
                                @if ($errors->userDeletion->get('password'))
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ $errors->userDeletion->get('password')[0] }}
                                    </div>
                                @endif
                            </div>

                            <div class="modal-actions">
                                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
                                    <i class="fas fa-times"></i>
                                    <span class="btn-text">{{ __('Cancel') }}</span>
                                </button>
                                <button type="submit" class="btn btn-danger" id="confirmDeleteBtn">
                                    <i class="fas fa-trash-alt"></i>
                                    <span class="btn-text">{{ __('Delete Account') }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --primary-color: #3b82f6;
            --secondary-color: #f97316;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --error-color: #ef4444;
            --danger-color: #dc2626;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --background-light: #f8fafc;
            --background-white: #ffffff;
            --border-color: #e5e7eb;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .dashboard-container {
            padding: 24px;
            background: var(--background-light);
            min-height: calc(100vh - 80px);
        }

        .profile-settings-wrapper {
            max-width: 800px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 32px;
        }

        /* Profile Sections */
        .profile-section {
            background: var(--background-white);
            border-radius: 20px;
            padding: 32px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .profile-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }

        .profile-section.danger::before {
            background: linear-gradient(90deg, var(--danger-color), #b91c1c);
        }

        .profile-section:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }

        .section-header {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 32px;
        }

        .section-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--primary-color), #2563eb);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            box-shadow: var(--shadow-md);
            flex-shrink: 0;
        }

        .section-icon.password {
            background: linear-gradient(135deg, var(--secondary-color), #ea580c);
        }

        .section-icon.danger {
            background: linear-gradient(135deg, var(--danger-color), #b91c1c);
        }

        .section-title h2 {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
            letter-spacing: -0.02em;
        }

        .section-title p {
            font-size: 16px;
            color: var(--text-secondary);
            line-height: 1.6;
            margin: 0;
        }

        /* Form Styles */
        .profile-form {
            width: 100%;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
            margin-bottom: 32px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-label {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .input-wrapper {
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-control {
            width: 100%;
            padding: 16px 20px 16px 52px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.8);
            color: var(--text-primary);
            box-shadow: var(--shadow-sm);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08), var(--shadow-md);
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.95);
        }

        .form-control::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus + .input-icon {
            color: var(--primary-color);
            transform: translateY(-50%) scale(1.1);
        }

        .password-toggle {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            cursor: pointer;
            font-size: 16px;
            padding: 6px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .password-toggle:hover {
            color: var(--primary-color);
            background: rgba(59, 130, 246, 0.08);
        }

        /* Password Strength Indicator */
        .password-strength {
            margin-top: 8px;
        }

        .strength-bar {
            height: 4px;
            background: rgba(107, 114, 128, 0.2);
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 4px;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            border-radius: 2px;
            transition: all 0.3s ease;
            background: var(--error-color);
        }

        .strength-fill.weak {
            width: 25%;
            background: var(--error-color);
        }

        .strength-fill.fair {
            width: 50%;
            background: var(--warning-color);
        }

        .strength-fill.good {
            width: 75%;
            background: var(--primary-color);
        }

        .strength-fill.strong {
            width: 100%;
            background: var(--success-color);
        }

        .strength-text {
            font-size: 12px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        /* Messages */
        .error-message, .success-message {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            margin-top: 8px;
            animation: slideIn 0.3s ease-out;
        }

        .error-message {
            background: rgba(239, 68, 68, 0.1);
            color: var(--error-color);
            border-left: 4px solid var(--error-color);
        }

        .success-message {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }

        .success-animation {
            animation: successPulse 0.6s ease-out;
        }

        /* Email Verification */
        .verification-notice {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.2);
            border-radius: 12px;
            padding: 16px;
            margin-top: 12px;
        }

        .verification-content {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .verification-content i {
            color: var(--warning-color);
            font-size: 16px;
            margin-top: 2px;
        }

        .verification-text {
            color: var(--warning-color);
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .verification-link {
            color: var(--primary-color);
            text-decoration: underline;
            font-weight: 600;
            cursor: pointer;
            background: none;
            border: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .verification-link:hover {
            color: #2563eb;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 24px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #2563eb);
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: rgba(107, 114, 128, 0.1);
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: rgba(107, 114, 128, 0.2);
            color: var(--text-primary);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color), #b91c1c);
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .form-actions {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: var(--background-white);
            border-radius: 20px;
            max-width: 500px;
            width: 90%;
            box-shadow: var(--shadow-xl);
            transform: scale(0.9);
            transition: transform 0.3s ease;
            overflow: hidden;
        }

        .modal-overlay.active .modal-content {
            transform: scale(1);
        }

        .modal-header {
            padding: 24px 24px 0;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
            flex: 1;
            line-height: 1.4;
        }

        .modal-close {
            width: 32px;
            height: 32px;
            border: none;
            background: rgba(107, 114, 128, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s ease;
            flex-shrink: 0;
            margin-left: 16px;
        }

        .modal-close:hover {
            background: rgba(107, 114, 128, 0.2);
            color: var(--text-primary);
        }

        .modal-body {
            padding: 24px;
        }

        .modal-description {
            color: var(--text-secondary);
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
        }

        /* Loading State */
        .loading {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes successPulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
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

        /* AOS Animation */
        [data-aos] {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        [data-aos].aos-animate {
            opacity: 1;
            transform: translateY(0);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 16px;
            }

            .profile-settings-wrapper {
                gap: 24px;
            }

            .profile-section {
                padding: 24px 20px;
                border-radius: 16px;
            }

            .section-header {
                flex-direction: column;
                gap: 16px;
                text-align: center;
            }

            .section-icon {
                align-self: center;
            }

            .form-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .btn {
                justify-content: center;
            }

            .modal-content {
                margin: 20px;
                width: calc(100% - 40px);
            }

            .modal-actions {
                flex-direction: column;
                gap: 8px;
            }
        }

        @media (max-width: 480px) {
            .profile-section {
                padding: 20px 16px;
                border-radius: 12px;
            }

            .section-title h2 {
                font-size: 20px;
            }

            .form-control {
                padding: 14px 16px 14px 48px;
            }

            .modal-header {
                padding: 20px 20px 0;
            }

            .modal-body {
                padding: 20px;
            }
        }
    </style>

    <script>
        // AOS Animation Library (simplified)
        function initAOS() {
            const elements = document.querySelectorAll('[data-aos]');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const delay = entry.target.getAttribute('data-aos-delay') || 0;
                        setTimeout(() => {
                            entry.target.classList.add('aos-animate');
                        }, delay);
                    }
                });
            }, { threshold: 0.1 });
            
            elements.forEach(el => observer.observe(el));
        }

        // Password toggle functionality
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const toggle = field.parentElement.querySelector('.password-toggle');
            
            if (field.type === 'password') {
                field.type = 'text';
                toggle.classList.remove('fa-eye');
                toggle.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                toggle.classList.remove('fa-eye-slash');
                toggle.classList.add('fa-eye');
            }
        }

        // Modal functions
        function openDeleteModal() {
            document.getElementById('deleteModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            const checks = {
                length: password.length >= 8,
                lowercase: /[a-z]/.test(password),
                uppercase: /[A-Z]/.test(password),
                numbers: /\d/.test(password),
                symbols: /[^A-Za-z0-9]/.test(password)
            };

            strength = Object.values(checks).filter(Boolean).length;

            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');

            if (password.length === 0) {
                strengthFill.className = 'strength-fill';
                strengthFill.style.width = '0%';
                strengthText.textContent = 'Password strength';
                return;
            }

            switch (strength) {
                case 0:
                case 1:
                    strengthFill.className = 'strength-fill weak';
                    strengthText.textContent = 'Weak password';
                    break;
                case 2:
                    strengthFill.className = 'strength-fill fair';
                    strengthText.textContent = 'Fair password';
                    break;
                case 3:
                case 4:
                    strengthFill.className = 'strength-fill good';
                    strengthText.textContent = 'Good password';
                    break;
                case 5:
                    strengthFill.className = 'strength-fill strong';
                    strengthText.textContent = 'Strong password';
                    break;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS
            initAOS();

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

                    // Password strength checking
                    if (this.id === 'update_password_password') {
                        checkPasswordStrength(this.value);
                    }
                });
            });

            // Form submission handling
            const profileForm = document.getElementById('profileForm');
            const passwordForm = document.getElementById('passwordForm');
            const deleteForm = document.getElementById('deleteForm');

            if (profileForm) {
                profileForm.addEventListener('submit', function(e) {
                    const saveBtn = document.getElementById('saveProfileBtn');
                    const btnText = saveBtn.querySelector('.btn-text');
                    saveBtn.disabled = true;
                    btnText.innerHTML = `
                        <span class="loading">
                            <div class="spinner"></div>
                            Saving...
                        </span>
                    `;
                });
            }

            if (passwordForm) {
                passwordForm.addEventListener('submit', function(e) {
                    const saveBtn = document.getElementById('savePasswordBtn');
                    const btnText = saveBtn.querySelector('.btn-text');
                    saveBtn.disabled = true;
                    btnText.innerHTML = `
                        <span class="loading">
                            <div class="spinner"></div>
                            Updating...
                        </span>
                    `;
                });
            }

            if (deleteForm) {
                deleteForm.addEventListener('submit', function(e) {
                    const deleteBtn = document.getElementById('confirmDeleteBtn');
                    const btnText = deleteBtn.querySelector('.btn-text');
                    deleteBtn.disabled = true;
                    btnText.innerHTML = `
                        <span class="loading">
                            <div class="spinner"></div>
                            Deleting...
                        </span>
                    `;
                });
            }

            // Enhanced ripple effect for buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
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
            });

            // Close modal on overlay click
            const deleteModal = document.getElementById('deleteModal');
            if (deleteModal) {
                deleteModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeDeleteModal();
                    }
                });
            }

            // Auto-hide success messages
            const successMessages = document.querySelectorAll('.success-message');
            successMessages.forEach(message => {
                setTimeout(() => {
                    message.style.opacity = '0';
                    message.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        message.remove();
                    }, 300);
                }, 5000);
            });

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Escape key to close modal
                if (e.key === 'Escape') {
                    closeDeleteModal();
                }
            });

            // Show delete modal if there are deletion errors
            @if ($errors->userDeletion->isNotEmpty())
                openDeleteModal();
            @endif

            // Auto-format email input
            const emailInput = document.getElementById('email');
            if (emailInput) {
                emailInput.addEventListener('input', function() {
                    this.value = this.value.toLowerCase().replace(/\s/g, '');
                });
            }
        });
    </script>
</body>

</html>