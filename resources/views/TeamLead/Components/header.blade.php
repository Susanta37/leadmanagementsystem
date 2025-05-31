<header class="header">
    <div class="header-content">
        <!-- Left Section -->
        <div class="header-left">
            <button class="mobile-menu-toggle" id="mobileMenuToggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="breadcrumb">
                <span class="breadcrumb-item">{{ $title ?? 'Dashboard' }}</span>
                @if(isset($subtitle))
                    <i class="fas fa-chevron-right"></i>
                    <span class="breadcrumb-item active">{{ $subtitle }}</span>
                @endif
            </div>
        </div>

        <!-- Center Section - Search -->
        {{-- <div class="header-center">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Search leads, campaigns, or tasks...">
                <div class="search-suggestions" id="searchSuggestions">
                    <!-- Dynamic search suggestions will be populated here -->
                </div>
            </div>
        </div> --}}

        <!-- Right Section -->
        <div class="header-right">
            <!-- Quick Actions -->
            <div class="quick-actions">
                <button class="action-btn" title="Add New Lead" data-action="add-lead">
                    <i class="fas fa-plus"></i>
                </button>
                <button class="action-btn" title="Notifications" data-action="notifications">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </button>
                <button class="action-btn" title="Messages" data-action="messages">
                    <i class="fas fa-envelope"></i>
                    <span class="notification-badge">7</span>
                </button>
            </div>

            <!-- User Profile -->
            <div class="user-profile" id="userProfile">
                <div class="user-avatar">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'User' }}&background=f97316&color=fff" alt="User Avatar">
                    <div class="status-indicator"></div>
                </div>
                <div class="user-info">
                    <span class="user-name">{{ Auth::user()->name ?? 'John Doe' }}</span>
                    <span class="user-role">{{ Auth::user()->role ?? 'Team Lead' }}</span>
                </div>
                <i class="fas fa-chevron-down dropdown-arrow"></i>
                
                <!-- User Dropdown -->
                <div class="user-dropdown" id="userDropdown">
                    <div class="dropdown-header">
                        <div class="user-avatar-large">
                            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'User' }}&background=f97316&color=fff" alt="User Avatar">
                        </div>
                        <div class="user-details">
                            <h4>{{ Auth::user()->name ?? 'John Doe' }}</h4>
                            <p>{{ Auth::user()->email ?? 'john@example.com' }}</p>
                        </div>
                    </div>
                    <div class="dropdown-menu">
                        <a href="" class="dropdown-item">
                            <i class="fas fa-user"></i>
                            <span>My Profile</span>
                        </a>
                        <a href="" class="dropdown-item">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                        <a href="" class="dropdown-item">
                            <i class="fas fa-question-circle"></i>
                            <span>Help & Support</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item logout-btn">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Sign Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications Panel -->
    <div class="notifications-panel" id="notificationsPanel">
        <div class="panel-header">
            <h3>Notifications</h3>
            <button class="close-panel" data-panel="notifications">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="panel-content">
            <div class="notification-item unread">
                <div class="notification-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="notification-content">
                    <h4>New Lead Added</h4>
                    <p>John Smith has been added as a hot lead</p>
                    <span class="notification-time">2 minutes ago</span>
                </div>
            </div>
            <div class="notification-item">
                <div class="notification-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="notification-content">
                    <h4>Campaign Performance</h4>
                    <p>Email campaign #12 achieved 25% open rate</p>
                    <span class="notification-time">1 hour ago</span>
                </div>
            </div>
            <div class="notification-item">
                <div class="notification-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="notification-content">
                    <h4>Task Reminder</h4>
                    <p>Follow up with Sarah Johnson due today</p>
                    <span class="notification-time">3 hours ago</span>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <a href="" class="view-all-btn">View All Notifications</a>
        </div>
    </div>
</header>

<style>
.header {
    position: fixed;
    top: 0;
    left: 280px;
    right: 0;
    height: 80px;
    background: #ffffff;
    border-bottom: 1px solid #e5e7eb;
    z-index: 999;
    transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.sidebar.collapsed + .main-content .header {
    left: 80px;
}

.header-content {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 24px;
    gap: 24px;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 16px;
}

.mobile-menu-toggle {
    display: none;
    background: none;
    border: none;
    font-size: 20px;
    color: #6b7280;
    cursor: pointer;
    padding: 8px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.mobile-menu-toggle:hover {
    background: #f3f4f6;
    color: #f97316;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}

.breadcrumb-item {
    color: #6b7280;
    font-weight: 500;
}

.breadcrumb-item.active {
    color: #1f2937;
    font-weight: 600;
}

.breadcrumb i {
    font-size: 12px;
    color: #d1d5db;
}

.header-center {
    flex: 1;
    max-width: 500px;
}

.search-container {
    position: relative;
    width: 100%;
}

.search-input {
    width: 100%;
    padding: 12px 16px 12px 44px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 14px;
    background: #f9fafb;
    transition: all 0.3s ease;
}

.search-input:focus {
    outline: none;
    border-color: #f97316;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
}

.search-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 14px;
}

.search-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    max-height: 300px;
    overflow-y: auto;
    z-index: 1000;
    display: none;
}

.header-right {
    display: flex;
    align-items: center;
    gap: 20px;
}

.quick-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.action-btn {
    position: relative;
    width: 44px;
    height: 44px;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 16px;
    color: #6b7280;
}

.action-btn:hover {
    background: #f97316;
    border-color: #f97316;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
}

.action-btn[data-action="add-lead"]:hover {
    background: #f97316;
}

.notification-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    background: #ef4444;
    color: white;
    font-size: 10px;
    font-weight: 600;
    padding: 2px 6px;
    border-radius: 10px;
    min-width: 16px;
    text-align: center;
    line-height: 1.2;
}

.user-profile {
    position: relative;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 16px;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.user-profile:hover {
    background: #f9fafb;
}

.user-avatar {
    position: relative;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid #f97316;
}

.user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.status-indicator {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 10px;
    height: 10px;
    background: #10b981;
    border: 2px solid white;
    border-radius: 50%;
}

.user-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.user-name {
    font-size: 14px;
    font-weight: 600;
    color: #1f2937;
    line-height: 1.2;
}

.user-role {
    font-size: 12px;
    color: #6b7280;
    font-weight: 500;
}

.dropdown-arrow {
    font-size: 12px;
    color: #9ca3af;
    transition: transform 0.3s ease;
}

.user-profile.active .dropdown-arrow {
    transform: rotate(180deg);
}

.user-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    width: 280px;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    z-index: 1000;
    margin-top: 8px;
}

.user-profile.active .user-dropdown {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-header {
    padding: 20px;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar-large {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid #f97316;
}

.user-avatar-large img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.user-details h4 {
    font-size: 16px;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 4px 0;
}

.user-details p {
    font-size: 14px;
    color: #6b7280;
    margin: 0;
}

.dropdown-menu {
    padding: 8px 0;
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    color: #6b7280;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
}

.dropdown-item:hover {
    background: #fef3f2;
    color: #f97316;
}

.dropdown-item i {
    width: 16px;
    font-size: 14px;
}

.dropdown-divider {
    height: 1px;
    background: #f3f4f6;
    margin: 8px 0;
}

.logout-btn {
    color: #ef4444 !important;
}

.logout-btn:hover {
    background: #fef2f2 !important;
    color: #dc2626 !important;
}

.notifications-panel {
    position: fixed;
    top: 0;
    right: -400px;
    width: 400px;
    height: 100vh;
    background: white;
    border-left: 1px solid #e5e7eb;
    box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
    transition: right 0.3s ease;
    z-index: 1001;
    display: flex;
    flex-direction: column;
}

.notifications-panel.active {
    right: 0;
}

.panel-header {
    padding: 24px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.panel-header h3 {
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
}

.close-panel {
    background: none;
    border: none;
    font-size: 16px;
    color: #6b7280;
    cursor: pointer;
    padding: 8px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.close-panel:hover {
    background: #f3f4f6;
    color: #f97316;
}

.panel-content {
    flex: 1;
    overflow-y: auto;
    padding: 16px 0;
}

.notification-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 16px 24px;
    border-bottom: 1px solid #f3f4f6;
    transition: all 0.3s ease;
    cursor: pointer;
}

.notification-item:hover {
    background: #f9fafb;
}

.notification-item.unread {
    background: #fef3f2;
    border-left: 3px solid #f97316;
}

.notification-icon {
    width: 40px;
    height: 40px;
    background: #f3f4f6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.notification-item.unread .notification-icon {
    background: #f97316;
    color: white;
}

.notification-content {
    flex: 1;
}

.notification-content h4 {
    font-size: 14px;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 4px 0;
}

.notification-content p {
    font-size: 13px;
    color: #6b7280;
    margin: 0 0 8px 0;
    line-height: 1.4;
}

.notification-time {
    font-size: 12px;
    color: #9ca3af;
    font-weight: 500;
}

.panel-footer {
    padding: 16px 24px;
    border-top: 1px solid #e5e7eb;
}

.view-all-btn {
    display: block;
    text-align: center;
    padding: 12px;
    background: #f97316;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
}

.view-all-btn:hover {
    background: #ea580c;
    transform: translateY(-1px);
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .header {
        left: 0;
    }
    
    .mobile-menu-toggle {
        display: block;
    }
    
    .header-center {
        display: none;
    }
    
    .user-info {
        display: none;
    }
    
    .notifications-panel {
        width: 100%;
        right: -100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userProfile = document.getElementById('userProfile');
    const userDropdown = document.getElementById('userDropdown');
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const sidebar = document.getElementById('sidebar');
    const actionBtns = document.querySelectorAll('.action-btn');
    const notificationsPanel = document.getElementById('notificationsPanel');
    const closePanelBtns = document.querySelectorAll('.close-panel');

    // User dropdown toggle
    userProfile.addEventListener('click', function(e) {
        e.stopPropagation();
        this.classList.toggle('active');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function() {
        userProfile.classList.remove('active');
        notificationsPanel.classList.remove('active');
    });

    // Mobile menu toggle
    mobileMenuToggle.addEventListener('click', function() {
        sidebar.classList.toggle('mobile-open');
    });

    // Action buttons
    actionBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const action = this.dataset.action;
            
            if (action === 'notifications') {
                notificationsPanel.classList.toggle('active');
            } else if (action === 'add-lead') {
                // Handle add lead action
                window.location.href = '';
            } else if (action === 'messages') {
                // Handle messages action
                console.log('Messages clicked');
            }
        });
    });

    // Close panels
    closePanelBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const panel = this.dataset.panel;
            if (panel === 'notifications') {
                notificationsPanel.classList.remove('active');
            }
        });
    });

    // Search functionality
    const searchInput = document.querySelector('.search-input');
    const searchSuggestions = document.getElementById('searchSuggestions');

    searchInput.addEventListener('focus', function() {
        // Show search suggestions
        searchSuggestions.style.display = 'block';
    });

    searchInput.addEventListener('blur', function() {
        // Hide search suggestions after a delay
        setTimeout(() => {
            searchSuggestions.style.display = 'none';
        }, 200);
    });
});
</script>