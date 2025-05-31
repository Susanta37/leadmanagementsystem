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
    @include('Employee.Components.sidebar')
    
    <div class="main-content">
        @include('Employee.Components.header', ['title' => 'Teams', 'subtitle' => 'Meet your team members and leaders'])
        
        <div class="dashboard-container">
            <!-- Page Header with Stats -->
            <div class="page-header">
                <div class="header-content">
                    <h1 class="page-title">Our Teams</h1>
                    <p class="page-subtitle">Discover team structures and connect with colleagues</p>
                </div>
                <div class="team-stats">
                    <div class="stat-item">
                        <div class="stat-value" id="totalTeams">0</div>
                        <div class="stat-label">Total Teams</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="totalMembers">0</div>
                        <div class="stat-label">Team Members</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="totalLeaders">0</div>
                        <div class="stat-label">Team Leaders</div>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="filters-section">
                <div class="filters-header">
                    <h3>Filter Teams</h3>
                    <button class="filter-toggle" onclick="toggleFilters()">
                        <i class="fas fa-filter"></i>
                        <span>Show Filters</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="filters-content" id="filtersContent">
                    <div class="filters-grid">
                        <div class="filter-group">
                            <label for="departmentFilter">Department</label>
                            <select id="departmentFilter" class="filter-select" onchange="filterTeams()">
                                <option value="">All Departments</option>
                                <option value="Engineering">Engineering</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Sales">Sales</option>
                                <option value="HR">Human Resources</option>
                                <option value="Finance">Finance</option>
                                <option value="Operations">Operations</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="teamSizeFilter">Team Size</label>
                            <select id="teamSizeFilter" class="filter-select" onchange="filterTeams()">
                                <option value="">All Sizes</option>
                                <option value="small">Small (1-5 members)</option>
                                <option value="medium">Medium (6-10 members)</option>
                                <option value="large">Large (11+ members)</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="locationFilter">Location</label>
                            <select id="locationFilter" class="filter-select" onchange="filterTeams()">
                                <option value="">All Locations</option>
                                <option value="Mumbai">Mumbai</option>
                                <option value="Delhi">Delhi</option>
                                <option value="Bangalore">Bangalore</option>
                                <option value="Pune">Pune</option>
                                <option value="Remote">Remote</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="searchFilter">Search</label>
                            <div class="search-input-wrapper">
                                <input type="text" id="searchFilter" class="filter-input" placeholder="Search teams, leaders, members..." oninput="filterTeams()">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                        <div class="filter-actions">
                            <button class="btn-reset" onclick="resetFilters()">
                                <i class="fas fa-undo"></i>
                                Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Teams Section -->
            <div class="teams-section">
                <div class="section-header">
                    <h3>Team Structure</h3>
                    <div class="view-controls">
                        <div class="view-toggle">
                            <button class="view-btn active" data-view="grid" onclick="switchView('grid')">
                                <i class="fas fa-th-large"></i>
                                Grid
                            </button>
                            <button class="view-btn" data-view="list" onclick="switchView('list')">
                                <i class="fas fa-list"></i>
                                List
                            </button>
                        </div>
                        <div class="results-info">
                            Showing <span id="resultsCount">0</span> teams
                        </div>
                    </div>
                </div>
                
                <!-- Grid View -->
                <div id="gridView" class="teams-grid">
                    <!-- Team cards will be populated here -->
                </div>

                <!-- List View -->
                <div id="listView" class="teams-list" style="display: none;">
                    <!-- List items will be populated here -->
                </div>

                <!-- Empty State -->
                <div id="emptyState" class="empty-state" style="display: none;">
                    <div class="empty-state-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>No teams found</h3>
                    <p>Try adjusting your filters to find teams</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Detail Modal -->
    <div class="modal-overlay" id="teamDetailModal">
        <div class="modal-container">
            <div class="modal-header">
                <h2 class="modal-title">Team Details</h2>
                <button class="modal-close" onclick="closeModal('teamDetailModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-content">
                <div class="team-detail-grid">
                    <div class="team-detail-left">
                        <div class="team-leader-section">
                            <h4>Team Leader</h4>
                            <div class="leader-card">
                                <div class="leader-avatar">
                                    <img id="modalLeaderPhoto" src="/placeholder.svg" alt="Team Leader">
                                </div>
                                <div class="leader-info">
                                    <h3 id="modalLeaderName">Rajesh Kumar</h3>
                                    <p id="modalLeaderRole">Senior Engineering Manager</p>
                                    <div class="leader-contact">
                                        <div class="contact-item">
                                            <i class="fas fa-envelope"></i>
                                            <span id="modalLeaderEmail">rajesh.kumar@company.com</span>
                                        </div>
                                        <div class="contact-item">
                                            <i class="fas fa-phone"></i>
                                            <span id="modalLeaderPhone">+91 98765 43210</span>
                                        </div>
                                        <div class="contact-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span id="modalLeaderLocation">Mumbai, India</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="team-stats-section">
                            <h4>Team Statistics</h4>
                            <div class="stats-grid">
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="stat-info">
                                        <div class="stat-number" id="modalTeamSize">8</div>
                                        <div class="stat-text">Team Members</div>
                                    </div>
                                </div>
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                    <div class="stat-info">
                                        <div class="stat-number" id="modalTeamAge">2.5</div>
                                        <div class="stat-text">Years Active</div>
                                    </div>
                                </div>
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-trophy"></i>
                                    </div>
                                    <div class="stat-info">
                                        <div class="stat-number" id="modalTeamProjects">12</div>
                                        <div class="stat-text">Projects</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="team-detail-right">
                        <div class="team-members-section">
                            <h4>Team Members</h4>
                            <div class="members-list" id="modalMembersList">
                                <!-- Team members will be populated here -->
                            </div>
                        </div>

                        <div class="team-description-section">
                            <h4>Team Description</h4>
                            <div class="description-content">
                                <p id="modalTeamDescription">This team is responsible for developing and maintaining our core platform features, ensuring high-quality code delivery and innovative solutions.</p>
                            </div>
                        </div>

                        <div class="team-skills-section">
                            <h4>Team Skills</h4>
                            <div class="skills-tags" id="modalTeamSkills">
                                <!-- Skills will be populated here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeModal('teamDetailModal')">
                    <i class="fas fa-times"></i>
                    Close
                </button>
                <button class="btn-primary" onclick="contactTeam()">
                    <i class="fas fa-envelope"></i>
                    Contact Team
                </button>
            </div>
        </div>
    </div>

    <!-- Member Detail Modal -->
    <div class="modal-overlay" id="memberDetailModal">
        <div class="modal-container modal-sm">
            <div class="modal-header">
                <h2 class="modal-title">Member Details</h2>
                <button class="modal-close" onclick="closeModal('memberDetailModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-content">
                <div class="member-detail-content">
                    <div class="member-profile">
                        <div class="member-avatar-large">
                            <img id="modalMemberPhoto" src="/placeholder.svg" alt="Member">
                        </div>
                        <div class="member-basic-info">
                            <h3 id="modalMemberName">Priya Sharma</h3>
                            <p id="modalMemberRole">Frontend Developer</p>
                            <div class="member-department" id="modalMemberDepartment">Engineering</div>
                        </div>
                    </div>

                    <div class="member-contact-info">
                        <h4>Contact Information</h4>
                        <div class="contact-details">
                            <div class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <span id="modalMemberEmail">priya.sharma@company.com</span>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <span id="modalMemberPhone">+91 87654 32109</span>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span id="modalMemberLocation">Mumbai, India</span>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-calendar"></i>
                                <span id="modalMemberJoinDate">Joined Jan 2022</span>
                            </div>
                        </div>
                    </div>

                    <div class="member-skills-info">
                        <h4>Skills & Expertise</h4>
                        <div class="member-skills" id="modalMemberSkills">
                            <!-- Skills will be populated here -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeModal('memberDetailModal')">
                    <i class="fas fa-times"></i>
                    Close
                </button>
                <button class="btn-primary" onclick="contactMember()">
                    <i class="fas fa-envelope"></i>
                    Send Message
                </button>
            </div>
        </div>
    </div>

    <style>
        /* Base Styles */
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

        .dashboard-container {
            padding: 24px;
            max-width: 1600px;
            margin: 0 auto;
        }

        /* Page Header */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
            animation: fadeInDown 0.6s ease-out;
        }

        .header-content {
            flex: 1;
        }

        .page-title {
            font-size: 32px;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 4px;
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-subtitle {
            font-size: 16px;
            color: #6b7280;
            font-weight: 500;
        }

        .team-stats {
            display: flex;
            gap: 24px;
        }

        .stat-item {
            text-align: center;
            padding: 16px 24px;
            background: white;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            min-width: 120px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 800;
            color: #8b5cf6;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 14px;
            color: #6b7280;
            font-weight: 600;
        }

        /* Filters Section */
        .filters-section {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            animation: slideInLeft 0.6s ease-out;
        }

        .filters-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .filters-header h3 {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
        }

        .filter-toggle {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #4b5563;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .filter-toggle:hover {
            background: #e5e7eb;
        }

        .filters-content {
            display: none;
            animation: slideDown 0.3s ease-out;
        }

        .filters-content.active {
            display: block;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .filter-group label {
            font-size: 14px;
            font-weight: 600;
            color: #4b5563;
        }

        .filter-select, .filter-input {
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            color: #1f2937;
            background: #f9fafb;
            transition: all 0.2s ease;
        }

        .filter-select:focus, .filter-input:focus {
            outline: none;
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
            background: white;
        }

        .search-input-wrapper {
            position: relative;
        }

        .search-input-wrapper i {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .filter-actions {
            display: flex;
            align-items: flex-end;
            justify-content: flex-end;
        }

        .btn-reset {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #4b5563;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-reset:hover {
            background: #e5e7eb;
        }

        /* Teams Section */
        .teams-section {
            animation: slideInRight 0.6s ease-out;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .section-header h3 {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
        }

        .view-controls {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .view-toggle {
            display: flex;
            background: white;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .view-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: transparent;
            border: none;
            font-size: 14px;
            font-weight: 600;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .view-btn.active {
            background: #8b5cf6;
            color: white;
        }

        .results-info {
            font-size: 14px;
            color: #6b7280;
            font-weight: 500;
        }

        /* Teams Grid */
        .teams-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 24px;
            animation: fadeInUp 0.8s ease-out;
        }

        .team-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .team-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #8b5cf6, #7c3aed);
        }

        .team-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        .team-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .team-info {
            flex: 1;
        }

        .team-name {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .team-department {
            font-size: 14px;
            color: #6b7280;
            font-weight: 500;
        }

        .team-size-badge {
            padding: 6px 12px;
            background: #f3f4f6;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: #4b5563;
        }

        .team-leader {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
            padding: 16px;
            background: #f9fafb;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }

        .leader-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid #8b5cf6;
            flex-shrink: 0;
        }

        .leader-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .leader-details {
            flex: 1;
        }

        .leader-name {
            font-size: 16px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 2px;
        }

        .leader-role {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .leader-contact {
            font-size: 12px;
            color: #8b5cf6;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .team-members {
            margin-bottom: 20px;
        }

        .members-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .members-title {
            font-size: 14px;
            font-weight: 600;
            color: #4b5563;
        }

        .members-count {
            font-size: 12px;
            color: #6b7280;
        }

        .members-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .member-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }

        .member-avatar:hover {
            transform: scale(1.1);
            z-index: 10;
        }

        .member-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .more-members {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            color: #4b5563;
            cursor: pointer;
        }

        .team-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
        }

        .team-location {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            color: #6b7280;
        }

        .team-skills {
            display: flex;
            gap: 6px;
        }

        .skill-tag {
            padding: 4px 8px;
            background: #ede9fe;
            color: #7c3aed;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        /* Teams List */
        .teams-list {
            background: white;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .list-item {
            display: flex;
            align-items: center;
            padding: 20px 24px;
            border-bottom: 1px solid #e5e7eb;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .list-item:hover {
            background: #f9fafb;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .list-team-info {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .list-team-details {
            flex: 1;
        }

        .list-team-name {
            font-weight: 700;
            color: #1f2937;
            font-size: 16px;
            margin-bottom: 2px;
        }

        .list-team-meta {
            font-size: 14px;
            color: #6b7280;
        }

        .list-leader-info {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 200px;
        }

        .list-stats {
            display: flex;
            align-items: center;
            gap: 24px;
            min-width: 150px;
        }

        .list-stat {
            text-align: center;
        }

        .list-stat-value {
            font-weight: 700;
            color: #1f2937;
            font-size: 16px;
        }

        .list-stat-label {
            font-size: 12px;
            color: #6b7280;
        }

        /* Empty State */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 64px 24px;
            text-align: center;
        }

        .empty-state-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: #9ca3af;
            margin-bottom: 24px;
        }

        .empty-state h3 {
            font-size: 20px;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 8px;
        }

        .empty-state p {
            font-size: 16px;
            color: #6b7280;
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

        .modal-container {
            background: white;
            border-radius: 16px;
            max-width: 1000px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            transform: scale(0.9);
            transition: transform 0.3s ease;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .modal-container.modal-sm {
            max-width: 500px;
        }

        .modal-overlay.active .modal-container {
            transform: scale(1);
        }

        .modal-header {
            padding: 24px 32px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-title {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
        }

        .modal-close {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            background: #f3f4f6;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            background: #e5e7eb;
            color: #4b5563;
        }

        .modal-content {
            padding: 32px;
        }

        .modal-footer {
            padding: 24px 32px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
        }

        /* Team Detail Modal */
        .team-detail-grid {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 32px;
        }

        .team-leader-section h4,
        .team-stats-section h4,
        .team-members-section h4,
        .team-description-section h4,
        .team-skills-section h4 {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 16px;
            position: relative;
            padding-left: 16px;
        }

        .team-leader-section h4::before,
        .team-stats-section h4::before,
        .team-members-section h4::before,
        .team-description-section h4::before,
        .team-skills-section h4::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 16px;
            background: #8b5cf6;
            border-radius: 2px;
        }

        .leader-card {
            background: #f9fafb;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e5e7eb;
            margin-bottom: 24px;
        }

        .leader-card .leader-avatar {
            width: 80px;
            height: 80px;
            margin: 0 auto 16px;
        }

        .leader-info {
            text-align: center;
        }

        .leader-info h3 {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .leader-info p {
            color: #6b7280;
            margin-bottom: 16px;
        }

        .leader-contact {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #4b5563;
        }

        .contact-item i {
            color: #8b5cf6;
            width: 16px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            background: #f9fafb;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #8b5cf6;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .stat-info {
            flex: 1;
        }

        .stat-number {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
        }

        .stat-text {
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
        }

        .members-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 24px;
        }

        .member-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #f9fafb;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .member-item:hover {
            background: #f3f4f6;
        }

        .member-item .member-avatar {
            width: 40px;
            height: 40px;
            border: 2px solid #8b5cf6;
        }

        .member-details {
            flex: 1;
        }

        .member-name {
            font-weight: 600;
            color: #1f2937;
            font-size: 14px;
            margin-bottom: 2px;
        }

        .member-role {
            font-size: 12px;
            color: #6b7280;
        }

        .description-content {
            background: #f9fafb;
            border-radius: 12px;
            padding: 16px;
            border: 1px solid #e5e7eb;
            margin-bottom: 24px;
        }

        .description-content p {
            color: #4b5563;
            line-height: 1.6;
            margin: 0;
        }

        .skills-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .skill-tag {
            padding: 6px 12px;
            background: #ede9fe;
            color: #7c3aed;
            border-radius: 16px;
            font-size: 12px;
            font-weight: 600;
        }

        /* Member Detail Modal */
        .member-detail-content {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .member-profile {
            text-align: center;
        }

        .member-avatar-large {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 16px;
            border: 4px solid #8b5cf6;
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);
        }

        .member-avatar-large img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .member-basic-info h3 {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .member-basic-info p {
            color: #6b7280;
            margin-bottom: 8px;
        }

        .member-department {
            display: inline-block;
            padding: 4px 12px;
            background: #ede9fe;
            color: #7c3aed;
            border-radius: 16px;
            font-size: 12px;
            font-weight: 600;
        }

        .member-contact-info h4,
        .member-skills-info h4 {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 12px;
        }

        .contact-details {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .member-skills {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        /* Buttons */
        .btn-primary, .btn-secondary {
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-primary {
            background: #8b5cf6;
            color: white;
        }

        .btn-primary:hover {
            background: #7c3aed;
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #4b5563;
            border: 1px solid #d1d5db;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
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

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Styles */
        @media (max-width: 1200px) {
            .teams-grid {
                grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            }

            .team-detail-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 16px;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .team-stats {
                width: 100%;
                justify-content: space-between;
            }

            .stat-item {
                min-width: auto;
                flex: 1;
                padding: 12px 16px;
            }

            .filters-grid {
                grid-template-columns: 1fr;
            }

            .view-controls {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .teams-grid {
                grid-template-columns: 1fr;
            }

            .list-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .list-leader-info,
            .list-stats {
                min-width: auto;
                width: 100%;
            }
        }
    </style>

    <script>
        // Sample teams data
        const teamsData = [
            {
                id: 1,
                name: 'Rakesh Kumar',
                department: 'Team Lead',
                location: 'Bhubaneswar',
                description: 'Responsible All Loan Approval and Lead Management',
                teamAge: 2.5,
                projects: 12,
                skills: ['Makerketing', 'Finance', 'Insurance'],
                leader: {
                    id: 1,
                    name: 'Rajesh Kumar',
                    role: 'Senior Marketing Lead',
                    email: 'rajesh.kumar@company.com',
                    phone: '+91 98765 43210',
                    location: 'Odisha, India',
                    photo: 'https://ui-avatars.com/api/?name=Rajesh+Kumar&background=8b5cf6&color=fff&size=80'
                },
                members: [
                    {
                        id: 1,
                        name: 'Priya Sharma',
                        role: 'Market Analyst',
                        email: 'priya.sharma@company.com',
                        phone: '+91 87654 32109',
                        location: 'Mumbai, India',
                        joinDate: 'Jan 2022',
                        skills: ['Markting', 'analyst'],
                        photo: 'https://ui-avatars.com/api/?name=Priya+Sharma&background=10b981&color=fff&size=40'
                    },
                    {
                        id: 2,
                        name: 'Amit Patel',
                        role: 'Field analyst',
                        email: 'amit.patel@company.com',
                        phone: '+91 76543 21098',
                        location: 'Mumbai, India',
                        joinDate: 'Mar 2021',
                        skills: ['Figma', 'CSS', 'JavaScript'],
                        photo: 'https://ui-avatars.com/api/?name=Amit+Patel&background=f59e0b&color=fff&size=40'
                    },
                    {
                        id: 3,
                        name: 'Sneha Reddy',
                        role: 'Junior Developer',
                        email: 'sneha.reddy@company.com',
                        phone: '+91 65432 10987',
                        location: 'Mumbai, India',
                        joinDate: 'Aug 2023',
                        skills: ['Marketing', 'field analyst'],
                        photo: 'https://ui-avatars.com/api/?name=Sneha+Reddy&background=ef4444&color=fff&size=40'
                    },
                    {
                        id: 4,
                        name: 'Vikram Singh',
                        role: 'Team manager',
                        email: 'vikram.singh@company.com',
                        phone: '+91 54321 09876',
                        location: 'Mumbai, India',
                        joinDate: 'Jun 2022',
                        skills: ['Leadership', 'Marketing'],
                        photo: 'https://ui-avatars.com/api/?name=Vikram+Singh&background=3b82f6&color=fff&size=40'
                    }
                ]
            }
            // {
            //     id: 2,
            //     name: 'Backend Development',
            //     department: 'Engineering',
            //     location: 'Bangalore',
            //     description: 'Handles server-side development, database management, and API development for all applications.',
            //     teamAge: 3.2,
            //     projects: 18,
            //     skills: ['Node.js', 'Python', 'MongoDB', 'PostgreSQL', 'AWS'],
            //     leader: {
            //         id: 2,
            //         name: 'Anita Desai',
            //         role: 'Backend Team Lead',
            //         email: 'anita.desai@company.com',
            //         phone: '+91 98765 43211',
            //         location: 'Bangalore, India',
            //         photo: 'https://ui-avatars.com/api/?name=Anita+Desai&background=8b5cf6&color=fff&size=80'
            //     },
            //     members: [
            //         {
            //             id: 5,
            //             name: 'Rohit Sharma',
            //             role: 'Backend Developer',
            //             email: 'rohit.sharma@company.com',
            //             phone: '+91 87654 32110',
            //             location: 'Bangalore, India',
            //             joinDate: 'Feb 2021',
            //             skills: ['Node.js', 'Express', 'MongoDB'],
            //             photo: 'https://ui-avatars.com/api/?name=Rohit+Sharma&background=10b981&color=fff&size=40'
            //         },
            //         {
            //             id: 6,
            //             name: 'Kavya Nair',
            //             role: 'Database Specialist',
            //             email: 'kavya.nair@company.com',
            //             phone: '+91 76543 21099',
            //             location: 'Bangalore, India',
            //             joinDate: 'Nov 2020',
            //             skills: ['PostgreSQL', 'MySQL', 'Redis'],
            //             photo: 'https://ui-avatars.com/api/?name=Kavya+Nair&background=f59e0b&color=fff&size=40'
            //         },
            //         {
            //             id: 7,
            //             name: 'Arjun Mehta',
            //             role: 'DevOps Engineer',
            //             email: 'arjun.mehta@company.com',
            //             phone: '+91 65432 10988',
            //             location: 'Bangalore, India',
            //             joinDate: 'May 2022',
            //             skills: ['AWS', 'Docker', 'Kubernetes'],
            //             photo: 'https://ui-avatars.com/api/?name=Arjun+Mehta&background=ef4444&color=fff&size=40'
            //         },
            //         {
            //             id: 8,
            //             name: 'Pooja Gupta',
            //             role: 'API Developer',
            //             email: 'pooja.gupta@company.com',
            //             phone: '+91 54321 09877',
            //             location: 'Bangalore, India',
            //             joinDate: 'Sep 2021',
            //             skills: ['Python', 'FastAPI', 'GraphQL'],
            //             photo: 'https://ui-avatars.com/api/?name=Pooja+Gupta&background=3b82f6&color=fff&size=40'
            //         },
            //         {
            //             id: 9,
            //             name: 'Karan Joshi',
            //             role: 'Backend Developer',
            //             email: 'karan.joshi@company.com',
            //             phone: '+91 43210 98765',
            //             location: 'Bangalore, India',
            //             joinDate: 'Jan 2023',
            //             skills: ['Java', 'Spring Boot', 'MySQL'],
            //             photo: 'https://ui-avatars.com/api/?name=Karan+Joshi&background=8b5cf6&color=fff&size=40'
            //         }
            //     ]
            // },
            // {
            //     id: 3,
            //     name: 'Digital Marketing',
            //     department: 'Marketing',
            //     location: 'Delhi',
            //     description: 'Drives online marketing campaigns, social media strategy, and digital brand presence.',
            //     teamAge: 1.8,
            //     projects: 8,
            //     skills: ['SEO', 'Social Media', 'Google Ads', 'Analytics', 'Content Marketing'],
            //     leader: {
            //         id: 3,
            //         name: 'Ravi Agarwal',
            //         role: 'Digital Marketing Manager',
            //         email: 'ravi.agarwal@company.com',
            //         phone: '+91 98765 43212',
            //         location: 'Delhi, India',
            //         photo: 'https://ui-avatars.com/api/?name=Ravi+Agarwal&background=8b5cf6&color=fff&size=80'
            //     },
            //     members: [
            //         {
            //             id: 10,
            //             name: 'Neha Kapoor',
            //             role: 'SEO Specialist',
            //             email: 'neha.kapoor@company.com',
            //             phone: '+91 87654 32111',
            //             location: 'Delhi, India',
            //             joinDate: 'Apr 2022',
            //             skills: ['SEO', 'Google Analytics', 'Content Strategy'],
            //             photo: 'https://ui-avatars.com/api/?name=Neha+Kapoor&background=10b981&color=fff&size=40'
            //         },
            //         {
            //             id: 11,
            //             name: 'Sanjay Kumar',
            //             role: 'Social Media Manager',
            //             email: 'sanjay.kumar@company.com',
            //             phone: '+91 76543 21100',
            //             location: 'Delhi, India',
            //             joinDate: 'Jul 2021',
            //             skills: ['Social Media', 'Content Creation', 'Canva'],
            //             photo: 'https://ui-avatars.com/api/?name=Sanjay+Kumar&background=f59e0b&color=fff&size=40'
            //         },
            //         {
            //             id: 12,
            //             name: 'Divya Jain',
            //             role: 'Content Writer',
            //             email: 'divya.jain@company.com',
            //             phone: '+91 65432 10989',
            //             location: 'Delhi, India',
            //             joinDate: 'Dec 2022',
            //             skills: ['Content Writing', 'Copywriting', 'Blog Writing'],
            //             photo: 'https://ui-avatars.com/api/?name=Divya+Jain&background=ef4444&color=fff&size=40'
            //         }
            //     ]
            // },
            // {
            //     id: 4,
            //     name: 'Sales Team',
            //     department: 'Sales',
            //     location: 'Pune',
            //     description: 'Responsible for client acquisition, relationship management, and revenue generation.',
            //     teamAge: 2.1,
            //     projects: 15,
            //     skills: ['CRM', 'Lead Generation', 'Client Relations', 'Negotiation', 'Sales Analytics'],
            //     leader: {
            //         id: 4,
            //         name: 'Meera Iyer',
            //         role: 'Sales Director',
            //         email: 'meera.iyer@company.com',
            //         phone: '+91 98765 43213',
            //         location: 'Pune, India',
            //         photo: 'https://ui-avatars.com/api/?name=Meera+Iyer&background=8b5cf6&color=fff&size=80'
            //     },
            //     members: [
            //         {
            //             id: 13,
            //             name: 'Rahul Verma',
            //             role: 'Senior Sales Executive',
            //             email: 'rahul.verma@company.com',
            //             phone: '+91 87654 32112',
            //             location: 'Pune, India',
            //             joinDate: 'Mar 2020',
            //             skills: ['B2B Sales', 'CRM', 'Lead Generation'],
            //             photo: 'https://ui-avatars.com/api/?name=Rahul+Verma&background=10b981&color=fff&size=40'
            //         },
            //         {
            //             id: 14,
            //             name: 'Sunita Rao',
            //             role: 'Account Manager',
            //             email: 'sunita.rao@company.com',
            //             phone: '+91 76543 21101',
            //             location: 'Pune, India',
            //             joinDate: 'Aug 2021',
            //             skills: ['Account Management', 'Client Relations', 'Salesforce'],
            //             photo: 'https://ui-avatars.com/api/?name=Sunita+Rao&background=f59e0b&color=fff&size=40'
            //         },
            //         {
            //             id: 15,
            //             name: 'Manish Tiwari',
            //             role: 'Sales Representative',
            //             email: 'manish.tiwari@company.com',
            //             phone: '+91 65432 10990',
            //             location: 'Pune, India',
            //             joinDate: 'Jan 2023',
            //             skills: ['Cold Calling', 'Lead Qualification', 'Product Demo'],
            //             photo: 'https://ui-avatars.com/api/?name=Manish+Tiwari&background=ef4444&color=fff&size=40'
            //         }
            //     ]
            // },
            // {
            //     id: 5,
            //     name: 'HR Operations',
            //     department: 'HR',
            //     location: 'Remote',
            //     description: 'Manages recruitment, employee relations, training, and organizational development.',
            //     teamAge: 1.5,
            //     projects: 6,
            //     skills: ['Recruitment', 'Employee Relations', 'Training', 'HRIS', 'Policy Development'],
            //     leader: {
            //         id: 5,
            //         name: 'Deepika Singh',
            //         role: 'HR Manager',
            //         email: 'deepika.singh@company.com',
            //         phone: '+91 98765 43214',
            //         location: 'Remote',
            //         photo: 'https://ui-avatars.com/api/?name=Deepika+Singh&background=8b5cf6&color=fff&size=80'
            //     },
            //     members: [
            //         {
            //             id: 16,
            //             name: 'Arun Pandey',
            //             role: 'Recruitment Specialist',
            //             email: 'arun.pandey@company.com',
            //             phone: '+91 87654 32113',
            //             location: 'Remote',
            //             joinDate: 'Jun 2022',
            //             skills: ['Recruitment', 'Interviewing', 'LinkedIn Recruiting'],
            //             photo: 'https://ui-avatars.com/api/?name=Arun+Pandey&background=10b981&color=fff&size=40'
            //         },
            //         {
            //             id: 17,
            //             name: 'Shweta Malhotra',
            //             role: 'Training Coordinator',
            //             email: 'shweta.malhotra@company.com',
            //             phone: '+91 76543 21102',
            //             location: 'Remote',
            //             joinDate: 'Oct 2021',
            //             skills: ['Training Design', 'LMS', 'Employee Development'],
            //             photo: 'https://ui-avatars.com/api/?name=Shweta+Malhotra&background=f59e0b&color=fff&size=40'
            //         }
            //     ]
            // }
        ];

        // Global variables
        let filteredTeams = [...teamsData];
        let currentView = 'grid';
        let currentTeamId = null;
        let currentMemberId = null;

        // DOM Ready
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the page
            renderTeams();
            updateStats();
            updateResultsCount();
        });

        // Toggle filters visibility
        function toggleFilters() {
            const filtersContent = document.getElementById('filtersContent');
            const icon = document.querySelector('.filter-toggle i:last-child');
            
            filtersContent.classList.toggle('active');
            
            if (filtersContent.classList.contains('active')) {
                icon.className = 'fas fa-chevron-up';
            } else {
                icon.className = 'fas fa-chevron-down';
            }
        }

        // Filter teams
        function filterTeams() {
            const departmentFilter = document.getElementById('departmentFilter').value;
            const teamSizeFilter = document.getElementById('teamSizeFilter').value;
            const locationFilter = document.getElementById('locationFilter').value;
            const searchFilter = document.getElementById('searchFilter').value.toLowerCase();

            filteredTeams = teamsData.filter(team => {
                // Department filter
                if (departmentFilter && team.department !== departmentFilter) return false;
                
                // Team size filter
                if (teamSizeFilter) {
                    const teamSize = team.members.length;
                    switch (teamSizeFilter) {
                        case 'small':
                            if (teamSize > 5) return false;
                            break;
                        case 'medium':
                            if (teamSize < 6 || teamSize > 10) return false;
                            break;
                        case 'large':
                            if (teamSize < 11) return false;
                            break;
                    }
                }
                
                // Location filter
                if (locationFilter && team.location !== locationFilter) return false;
                
                // Search filter
                if (searchFilter) {
                    const searchableFields = [
                        team.name,
                        team.department,
                        team.leader.name,
                        team.description,
                        ...team.members.map(member => member.name),
                        ...team.skills
                    ].map(field => field.toLowerCase());
                    
                    return searchableFields.some(field => field.includes(searchFilter));
                }
                
                return true;
            });

            renderTeams();
            updateStats();
            updateResultsCount();
        }

        // Reset filters
        function resetFilters() {
            document.getElementById('departmentFilter').value = '';
            document.getElementById('teamSizeFilter').value = '';
            document.getElementById('locationFilter').value = '';
            document.getElementById('searchFilter').value = '';
            
            filteredTeams = [...teamsData];
            renderTeams();
            updateStats();
            updateResultsCount();
        }

        // Switch view
        function switchView(view) {
            currentView = view;
            
            // Update active button
            document.querySelectorAll('.view-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`[data-view="${view}"]`).classList.add('active');
            
            // Show/hide views
            document.getElementById('gridView').style.display = view === 'grid' ? 'grid' : 'none';
            document.getElementById('listView').style.display = view === 'list' ? 'block' : 'none';
            
            renderTeams();
        }

        // Render teams
        function renderTeams() {
            const gridView = document.getElementById('gridView');
            const listView = document.getElementById('listView');
            const emptyState = document.getElementById('emptyState');
            
            // Clear existing content
            gridView.innerHTML = '';
            listView.innerHTML = '';
            
            if (filteredTeams.length === 0) {
                emptyState.style.display = 'flex';
                gridView.style.display = 'none';
                listView.style.display = 'none';
                return;
            }
            
            emptyState.style.display = 'none';
            
            if (currentView === 'grid') {
                gridView.style.display = 'grid';
                listView.style.display = 'none';
                renderGridView();
            } else {
                gridView.style.display = 'none';
                listView.style.display = 'block';
                renderListView();
            }
        }

        // Render grid view
        function renderGridView() {
            const gridView = document.getElementById('gridView');
            
            filteredTeams.forEach(team => {
                const teamCard = document.createElement('div');
                teamCard.className = 'team-card';
                teamCard.onclick = () => viewTeamDetails(team.id);
                
                // Get team size category
                const teamSize = team.members.length;
                let sizeCategory = 'Small';
                if (teamSize >= 6 && teamSize <= 10) sizeCategory = 'Medium';
                else if (teamSize > 10) sizeCategory = 'Large';
                
                // Display first 4 members, then show +X more
                const displayMembers = team.members.slice(0, 4);
                const remainingCount = Math.max(0, team.members.length - 4);
                
                teamCard.innerHTML = `
                    <div class="team-header">
                        <div class="team-info">
                            <div class="team-name">${team.name}</div>
                            <div class="team-department">${team.department}</div>
                        </div>
                        <div class="team-size-badge">${sizeCategory} Team</div>
                    </div>
                    
                    <div class="team-leader">
                        <div class="leader-avatar">
                            <img src="${team.leader.photo}" alt="${team.leader.name}">
                        </div>
                        <div class="leader-details">
                            <div class="leader-name">${team.leader.name}</div>
                            <div class="leader-role">${team.leader.role}</div>
                            <div class="leader-contact">
                                <i class="fas fa-envelope"></i>
                                ${team.leader.email}
                            </div>
                        </div>
                    </div>
                    
                    <div class="team-members">
                        <div class="members-header">
                            <div class="members-title">Team Members</div>
                            <div class="members-count">${team.members.length} members</div>
                        </div>
                        <div class="members-list">
                            ${displayMembers.map(member => `
                                <div class="member-avatar" onclick="viewMemberDetails(${member.id}); event.stopPropagation();" title="${member.name} - ${member.role}">
                                    <img src="${member.photo}" alt="${member.name}">
                                </div>
                            `).join('')}
                            ${remainingCount > 0 ? `<div class="more-members" title="${remainingCount} more members">+${remainingCount}</div>` : ''}
                        </div>
                    </div>
                    
                    <div class="team-footer">
                        <div class="team-location">
                            <i class="fas fa-map-marker-alt"></i>
                            ${team.location}
                        </div>
                        <div class="team-skills">
                            ${team.skills.slice(0, 3).map(skill => `<span class="skill-tag">${skill}</span>`).join('')}
                        </div>
                    </div>
                `;
                
                gridView.appendChild(teamCard);
            });
        }

        // Render list view
        function renderListView() {
            const listView = document.getElementById('listView');
            
            filteredTeams.forEach(team => {
                const listItem = document.createElement('div');
                listItem.className = 'list-item';
                listItem.onclick = () => viewTeamDetails(team.id);
                
                listItem.innerHTML = `
                    <div class="list-team-info">
                        <div class="leader-avatar">
                            <img src="${team.leader.photo}" alt="${team.leader.name}">
                        </div>
                        <div class="list-team-details">
                            <div class="list-team-name">${team.name}</div>
                            <div class="list-team-meta">${team.department} • ${team.location}</div>
                        </div>
                    </div>
                    <div class="list-leader-info">
                        <div class="leader-details">
                            <div class="leader-name">${team.leader.name}</div>
                            <div class="leader-role">${team.leader.role}</div>
                        </div>
                    </div>
                    <div class="list-stats">
                        <div class="list-stat">
                            <div class="list-stat-value">${team.members.length}</div>
                            <div class="list-stat-label">Members</div>
                        </div>
                        <div class="list-stat">
                            <div class="list-stat-value">${team.projects}</div>
                            <div class="list-stat-label">Projects</div>
                        </div>
                    </div>
                `;
                
                listView.appendChild(listItem);
            });
        }

        // Update stats
        function updateStats() {
            const totalTeams = filteredTeams.length;
            const totalMembers = filteredTeams.reduce((sum, team) => sum + team.members.length, 0);
            const totalLeaders = filteredTeams.length;
            
            // Animate counters
            animateCounter('totalTeams', totalTeams);
            animateCounter('totalMembers', totalMembers);
            animateCounter('totalLeaders', totalLeaders);
        }

        // Animate counter
        function animateCounter(elementId, targetValue) {
            const element = document.getElementById(elementId);
            const currentValue = parseInt(element.textContent) || 0;
            const increment = targetValue > currentValue ? 1 : -1;
            const duration = 500;
            const steps = Math.abs(targetValue - currentValue);
            const stepDuration = steps > 0 ? duration / steps : 0;
            
            let current = currentValue;
            const timer = setInterval(() => {
                current += increment;
                
                if ((increment > 0 && current >= targetValue) || (increment < 0 && current <= targetValue)) {
                    element.textContent = targetValue;
                    clearInterval(timer);
                } else {
                    element.textContent = current;
                }
            }, stepDuration);
        }

        // Update results count
        function updateResultsCount() {
            document.getElementById('resultsCount').textContent = filteredTeams.length;
        }

        // View team details
        function viewTeamDetails(id) {
            const team = teamsData.find(team => team.id === id);
            if (!team) return;
            
            currentTeamId = id;
            
            // Update modal content
            document.getElementById('modalLeaderPhoto').src = team.leader.photo;
            document.getElementById('modalLeaderName').textContent = team.leader.name;
            document.getElementById('modalLeaderRole').textContent = team.leader.role;
            document.getElementById('modalLeaderEmail').textContent = team.leader.email;
            document.getElementById('modalLeaderPhone').textContent = team.leader.phone;
            document.getElementById('modalLeaderLocation').textContent = team.leader.location;
            
            document.getElementById('modalTeamSize').textContent = team.members.length;
            document.getElementById('modalTeamAge').textContent = team.teamAge;
            document.getElementById('modalTeamProjects').textContent = team.projects;
            document.getElementById('modalTeamDescription').textContent = team.description;
            
            // Update team members list
            const membersList = document.getElementById('modalMembersList');
            membersList.innerHTML = '';
            
            team.members.forEach(member => {
                const memberItem = document.createElement('div');
                memberItem.className = 'member-item';
                memberItem.onclick = () => viewMemberDetails(member.id);
                
                memberItem.innerHTML = `
                    <div class="member-avatar">
                        <img src="${member.photo}" alt="${member.name}">
                    </div>
                    <div class="member-details">
                        <div class="member-name">${member.name}</div>
                        <div class="member-role">${member.role}</div>
                    </div>
                `;
                
                membersList.appendChild(memberItem);
            });
            
            // Update team skills
            const skillsContainer = document.getElementById('modalTeamSkills');
            skillsContainer.innerHTML = '';
            
            team.skills.forEach(skill => {
                const skillTag = document.createElement('span');
                skillTag.className = 'skill-tag';
                skillTag.textContent = skill;
                skillsContainer.appendChild(skillTag);
            });
            
            // Show modal
            const modal = document.getElementById('teamDetailModal');
            modal.classList.add('active');
        }

        // View member details
        function viewMemberDetails(id) {
            // Find member across all teams
            let member = null;
            let memberTeam = null;
            
            for (const team of teamsData) {
                const foundMember = team.members.find(m => m.id === id);
                if (foundMember) {
                    member = foundMember;
                    memberTeam = team;
                    break;
                }
            }
            
            if (!member) return;
            
            currentMemberId = id;
            
            // Update modal content
            document.getElementById('modalMemberPhoto').src = member.photo;
            document.getElementById('modalMemberName').textContent = member.name;
            document.getElementById('modalMemberRole').textContent = member.role;
            document.getElementById('modalMemberDepartment').textContent = memberTeam.department;
            document.getElementById('modalMemberEmail').textContent = member.email;
            document.getElementById('modalMemberPhone').textContent = member.phone;
            document.getElementById('modalMemberLocation').textContent = member.location;
            document.getElementById('modalMemberJoinDate').textContent = `Joined ${member.joinDate}`;
            
            // Update member skills
            const skillsContainer = document.getElementById('modalMemberSkills');
            skillsContainer.innerHTML = '';
            
            member.skills.forEach(skill => {
                const skillTag = document.createElement('span');
                skillTag.className = 'skill-tag';
                skillTag.textContent = skill;
                skillsContainer.appendChild(skillTag);
            });
            
            // Close team detail modal if open
            closeModal('teamDetailModal');
            
            // Show member detail modal
            const modal = document.getElementById('memberDetailModal');
            modal.classList.add('active');
        }

        // Contact team
        function contactTeam() {
            if (!currentTeamId) return;
            
            const team = teamsData.find(team => team.id === currentTeamId);
            if (!team) return;
            
            // In a real application, this would open email client or messaging system
            showNotification(`Opening email to contact ${team.name} team leader: ${team.leader.name}`, 'info');
        }

        // Contact member
        function contactMember() {
            if (!currentMemberId) return;
            
            // Find member across all teams
            let member = null;
            
            for (const team of teamsData) {
                const foundMember = team.members.find(m => m.id === currentMemberId);
                if (foundMember) {
                    member = foundMember;
                    break;
                }
            }
            
            if (!member) return;
            
            // In a real application, this would open email client or messaging system
            showNotification(`Opening message to ${member.name}`, 'info');
        }

        // Close modal
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('active');
        }

        // Show notification
        function showNotification(message, type = 'info') {
            // Check if notification container exists, if not create it
            let container = document.getElementById('notificationContainer');
            if (!container) {
                container = document.createElement('div');
                container.id = 'notificationContainer';
                container.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    z-index: 9999;
                    display: flex;
                    flex-direction: column;
                    gap: 10px;
                `;
                document.body.appendChild(container);
            }
            
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.style.cssText = `
                background: ${type === 'success' ? '#dcfce7' : type === 'error' ? '#fee2e2' : '#dbeafe'};
                color: ${type === 'success' ? '#166534' : type === 'error' ? '#991b1b' : '#1e40af'};
                border-left: 4px solid ${type === 'success' ? '#16a34a' : type === 'error' ? '#dc2626' : '#3b82f6'};
                padding: 16px;
                border-radius: 8px;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                display: flex;
                align-items: center;
                gap: 12px;
                min-width: 300px;
                max-width: 400px;
                animation: slideInRight 0.3s ease-out;
            `;
            
            // Icon based on type
            const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle';
            
            notification.innerHTML = `
                <i class="fas fa-${icon}" style="font-size: 20px;"></i>
                <div style="flex: 1;">
                    <div style="font-weight: 600; margin-bottom: 2px;">${type.charAt(0).toUpperCase() + type.slice(1)}</div>
                    <div style="font-size: 14px;">${message}</div>
                </div>
                <button style="background: none; border: none; cursor: pointer; color: inherit; font-size: 16px;">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            // Add to container
            container.appendChild(notification);
            
            // Add click event to close button
            notification.querySelector('button').addEventListener('click', () => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    if (notification.parentNode) {
                        container.removeChild(notification);
                    }
                }, 300);
            });
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.style.opacity = '0';
                    setTimeout(() => {
                        if (notification.parentNode) {
                            container.removeChild(notification);
                        }
                    }, 300);
                }
            }, 5000);
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal-overlay')) {
                e.target.classList.remove('active');
            }
        });
    </script>
</body>
</html>