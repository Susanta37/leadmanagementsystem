<aside id="sidebar" class="sidebar">
    <div class="sidebar-content">
        <!-- Logo Section -->
        <div class="sidebar-header">
           
                {{-- <i class="fas fa-chart-line"></i> --}}
                <img src="{{ asset('logo1.png') }}" alt="Logo" class="w-10 h-10 rounded-full">
           
            <div class="logo-text">
                <h3>Employee</h3>
                <span>Dashboard</span>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="sidebar-nav">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="{{ route('employee.dashboard') }}" class="nav-link {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="employee/leads" class="nav-link has-submenu {{ request()->routeIs('employee.leads.*') ? 'active' : '' }}" data-submenu="leads">
                        <i class="fas fa-users"></i>
                        <span>Leads</span>
                        <i class="fas fa-chevron-down submenu-arrow"></i>
                    </a>
                    <ul class="submenu {{ request()->routeIs('employee.leads.*') ? 'active' : '' }}" id="leads-submenu">
                        <li><a href="{{ route('employee.leads.index') }}" class="{{ request()->routeIs('employee.leads.index') ? 'active' : '' }}">All Leads</a></li>
                     
                    </ul>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('employee.tasks.index') }}" class="nav-link {{ request()->routeIs('employee.tasks.index') ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i>
                        <span>Tasks</span>
                        <span class="badge">12</span>
                    </a>
                </li>
                  {{-- <li class="nav-item">
                    <a href="" class="nav-link {{ request()->routeIs('analytics') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Analytics</span>
                    </a>
                </li> --}}

                {{-- <li class="nav-item">
                    <a href="#" class="nav-link has-submenu" data-submenu="campaigns">
                        <i class="fas fa-bullhorn"></i>
                        <span>Campaigns</span>
                        <i class="fas fa-chevron-down submenu-arrow"></i>
                    </a>
                    <ul class="submenu" id="campaigns-submenu">
                        <li><a href="">All Campaigns</a></li>
                        <li><a href="">Create Campaign</a></li>
                        <li><a href="">Analytics</a></li>
                    </ul>
                </li> --}}

              


                <li class="nav-item">
                    <a href="#" class="nav-link has-submenu {{ request()->routeIs('employee.attendance.*') ? 'active' : '' }}" data-submenu="reports">
                        <i class="fas fa-file-alt"></i>
                        <span>Attendance</span>
                        <i class="fas fa-chevron-down submenu-arrow"></i>
                    </a>
                    <ul class="submenu" id="reports-submenu">
                        <li><a href="{{ route('employee.attendance.index') }}">Attendance Report</a></li>
                        <li><a href="">Leave Report</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('employee.team.index') }}" class="nav-link {{ request()->routeIs('employee.team.index') ? 'active' : '' }}">
                        <i class="fas fa-user-friends"></i>
                        <span>Team</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('employee.settings.index')}}" class="nav-link {{ request()->routeIs('employee.setting.*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<style>
.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    height: 100vh;
    width: 280px;
    background: #ffffff;
    border-right: 1px solid #e5e7eb;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
    z-index: 1000;
}

.sidebar-content {
    height: 100%;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.sidebar-header {
    padding: 24px 20px;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    align-items: center;
    gap: 12px;
    position: relative;
}

.logo {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #f97316, #ea580c);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.logo i {
    font-size: 20px;
    color: white;
}

.logo-text h3 {
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
    line-height: 1.2;
}

.logo-text span {
    font-size: 12px;
    color: #6b7280;
    font-weight: 500;
}

.sidebar-nav {
    flex: 1;
    padding: 20px 0;
    overflow-y: auto;
    overflow-x: hidden;
}

.nav-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-item {
    margin-bottom: 4px;
}

.nav-link {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: #6b7280;
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
    border-radius: 0 25px 25px 0;
    margin-right: 12px;
}

.nav-link:hover {
    background: #fef3f2;
    color: #f97316;
    transform: translateX(4px);
}

.nav-link.active {
    background: linear-gradient(135deg, #f97316, #ea580c);
    color: white;
    box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
}

.nav-link i {
    width: 20px;
    font-size: 16px;
    margin-right: 12px;
    flex-shrink: 0;
}

.nav-link span {
    flex: 1;
    font-weight: 500;
}

.badge {
    background: #3b82f6;
    color: white;
    font-size: 11px;
    padding: 2px 6px;
    border-radius: 10px;
    font-weight: 600;
    min-width: 18px;
    text-align: center;
}

.submenu-arrow {
    font-size: 12px;
    transition: transform 0.3s ease;
    margin-left: auto;
}

.nav-link.has-submenu.active .submenu-arrow {
    transform: rotate(180deg);
}

.submenu {
    list-style: none;
    padding: 0;
    margin: 0;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
    background: #f9fafb;
    margin-right: 12px;
    border-radius: 0 15px 15px 0;
}

.submenu.active {
    max-height: 200px;
    padding: 8px 0;
}

.submenu li {
    margin: 0;
}

.submenu a {
    display: block;
    padding: 8px 20px 8px 52px;
    color: #6b7280;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.submenu a:hover {
    color: #f97316;
    background: #fef3f2;
}

@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }
    
    .sidebar.mobile-open {
        transform: translateX(0);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const submenuLinks = document.querySelectorAll('.nav-link.has-submenu');

    submenuLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const submenuId = this.dataset.submenu + '-submenu';
            const submenu = document.getElementById(submenuId);

            document.querySelectorAll('.submenu').forEach(menu => {
                if (menu.id !== submenuId) {
                    menu.classList.remove('active');
                }
            });

            document.querySelectorAll('.nav-link.has-submenu').forEach(navLink => {
                if (navLink !== this) {
                    navLink.classList.remove('active');
                }
            });

            submenu.classList.toggle('active');
            this.classList.toggle('active');
        });
    });
});
</script>
