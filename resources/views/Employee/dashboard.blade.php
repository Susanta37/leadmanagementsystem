<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Lead Management System</title>
      <link rel="icon" type="image/png" href="{{ asset('logo1.png') }}">
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
        @include('Employee.Components.header', ['title' => 'Dashboard', 'subtitle' => 'Overview'])
        
        <div class="dashboard-container">
            <!-- Dashboard Header -->
            <div class="dashboard-header">
                <h1 class="dashboard-title">Welcome back, {{ Auth::user()->name ?? 'Employee' }}! 👋</h1>
                <p class="dashboard-subtitle">Here's your performance overview for today.</p>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-trend up">
                            <i class="fas fa-arrow-up"></i>
                            12%
                        </div>
                    </div>
                    <div class="stat-value">47</div>
                    <div class="stat-label">Total Leads</div>
                </div>

                <div class="stat-card blue">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-fire"></i>
                        </div>
                        <div class="stat-trend up">
                            <i class="fas fa-arrow-up"></i>
                            8%
                        </div>
                    </div>
                    <div class="stat-value">12</div>
                    <div class="stat-label">Disbursed Leads</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-trend up">
                            <i class="fas fa-arrow-up"></i>
                            15%
                        </div>
                    </div>
                    <div class="stat-value">68%</div>
                    <div class="stat-label">Success Rate</div>
                </div>

                <div class="stat-card blue">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div class="stat-trend down">
                            <i class="fas fa-arrow-down"></i>
                            3%
                        </div>
                    </div>
                    <div class="stat-value">8</div>
                    <div class="stat-label">Pending Tasks</div>
                </div>
            </div>

            <!-- Main Dashboard Grid -->
            <div class="dashboard-grid">
                <!-- Leads Section -->
                <div class="leads-section">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="fas fa-users"></i>
                            Recent Leads
                        </h2>
                        <button class="action-btn primary" onclick="openAddLeadModal()">
                            <i class="fas fa-plus"></i>
                            Add Lead
                        </button>
                    </div>
                    
                    <div class="table-container">
                        <div class="table-wrapper">
                            <table class="leads-table">
                                <thead>
                                    <tr>
                                        <th>Lead Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Company</th>
                                        <th>Amount</th>
                                        <th>Success %</th>
                                        <th>Expected Month</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-row" onclick="openLeadModal(1)">
                                        <td>
                                            <div class="lead-info">
                                                <div class="lead-avatar">JS</div>
                                                <div>
                                                    <div class="lead-name">Susanta Swain</div>
                                                    <div class="lead-location">Odisha, India</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>+91 6372356394</td>
                                        <td>susant.swain@email.com</td>
                                        <td>Nexpro Solution Inc.</td>
                                        <td class="amount">15,000</td>
                                        <td>
                                            <div class="progress-bar">
                                                <div class="progress-fill" style="width: 75%"></div>
                                                <span class="progress-text">85%</span>
                                            </div>
                                        </td>
                                        <td>March 2024</td>
                                        <td><span class="status hot">Rejected</span></td>
                                        <td>
                                            <button class="table-action-btn" onclick="event.stopPropagation(); forwardToTeamLead(1)">
                                                <i class="fas fa-share"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="table-row" onclick="openLeadModal(2)">
                                        <td>
                                            <div class="lead-info">
                                                <div class="lead-avatar">SJ</div>
                                                <div>
                                                    <div class="lead-name">Sarah Johnson</div>
                                                    <div class="lead-location">California, USA</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>+1 234 567 8901</td>
                                        <td>sarah.j@company.com</td>
                                        <td>Digital Solutions</td>
                                        <td class="amount">$8,500</td>
                                        <td>
                                            <div class="progress-bar">
                                                <div class="progress-fill" style="width: 45%"></div>
                                                <span class="progress-text">45%</span>
                                            </div>
                                        </td>
                                        <td>April 2024</td>
                                        <td><span class="status warm">Pending</span></td>
                                        <td>
                                            <button class="table-action-btn" onclick="event.stopPropagation(); forwardToTeamLead(2)">
                                                <i class="fas fa-share"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="table-row" onclick="openLeadModal(3)">
                                        <td>
                                            <div class="lead-info">
                                                <div class="lead-avatar">MD</div>
                                                <div>
                                                    <div class="lead-name">Mike Davis</div>
                                                    <div class="lead-location">Texas, USA</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>+1 234 567 8902</td>
                                        <td>mike.davis@startup.com</td>
                                        <td>StartupXYZ</td>
                                        <td class="amount">$25,000</td>
                                        <td>
                                            <div class="progress-bar">
                                                <div class="progress-fill" style="width: 90%"></div>
                                                <span class="progress-text">90%</span>
                                            </div>
                                        </td>
                                        <td>February 2024</td>
                                        <td><span class="status hot">Rejected</span></td>
                                        <td>
                                            <button class="table-action-btn" onclick="event.stopPropagation(); forwardToTeamLead(3)">
                                                <i class="fas fa-share"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="table-row" onclick="openLeadModal(4)">
                                        <td>
                                            <div class="lead-info">
                                                <div class="lead-avatar">EB</div>
                                                <div>
                                                    <div class="lead-name">Emily Brown</div>
                                                    <div class="lead-location">Florida, USA</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>+1 234 567 8903</td>
                                        <td>emily.brown@corp.com</td>
                                        <td>Global Corp</td>
                                        <td class="amount">$12,000</td>
                                        <td>
                                            <div class="progress-bar">
                                                <div class="progress-fill" style="width: 30%"></div>
                                                <span class="progress-text">30%</span>
                                            </div>
                                        </td>
                                        <td>May 2024</td>
                                        <td><span class="status cold">Cold</span></td>
                                        <td>
                                            <button class="table-action-btn" onclick="event.stopPropagation(); forwardToTeamLead(4)">
                                                <i class="fas fa-share"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="table-row" onclick="openLeadModal(5)">
                                        <td>
                                            <div class="lead-info">
                                                <div class="lead-avatar">RW</div>
                                                <div>
                                                    <div class="lead-name">Robert Wilson</div>
                                                    <div class="lead-location">Washington, USA</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>+1 234 567 8904</td>
                                        <td>robert.w@enterprise.com</td>
                                        <td>Enterprise Ltd</td>
                                        <td class="amount">$35,000</td>
                                        <td>
                                            <div class="progress-bar">
                                                <div class="progress-fill" style="width: 60%"></div>
                                                <span class="progress-text">60%</span>
                                            </div>
                                        </td>
                                        <td>March 2024</td>
                                        <td><span class="status warm">Pending</span></td>
                                        <td>
                                            <button class="table-action-btn" onclick="event.stopPropagation(); forwardToTeamLead(5)">
                                                <i class="fas fa-share"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Attendance & Tasks Section -->
                <div class="side-panel">
                    <!-- Attendance Section -->
                    <div class="attendance-section">
                        <div class="section-header">
                            <h3 class="section-title">
                                <i class="fas fa-clock"></i>
                                Attendance (Last 7 Days)
                            </h3>
                        </div>
                        
                        <div class="attendance-list">
                            <div class="attendance-item" onclick="openAttendanceModal('2024-01-15')">
                                <div class="attendance-date">
                                    <div class="day">Mon</div>
                                    <div class="date">15</div>
                                </div>
                                <div class="attendance-details">
                                    <div class="check-in">
                                        <i class="fas fa-sign-in-alt"></i>
                                        <span>09:15 AM</span>
                                    </div>
                                    <div class="check-out">
                                        <i class="fas fa-sign-out-alt"></i>
                                        <span>06:30 PM</span>
                                    </div>
                                </div>
                                <div class="attendance-status present">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>

                            <div class="attendance-item" onclick="openAttendanceModal('2024-01-14')">
                                <div class="attendance-date">
                                    <div class="day">Sun</div>
                                    <div class="date">14</div>
                                </div>
                                <div class="attendance-details">
                                    <div class="check-in">
                                        <i class="fas fa-sign-in-alt"></i>
                                        <span>09:00 AM</span>
                                    </div>
                                    <div class="check-out">
                                        <i class="fas fa-sign-out-alt"></i>
                                        <span>06:00 PM</span>
                                    </div>
                                </div>
                                <div class="attendance-status present">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>

                            <div class="attendance-item" onclick="openAttendanceModal('2024-01-13')">
                                <div class="attendance-date">
                                    <div class="day">Sat</div>
                                    <div class="date">13</div>
                                </div>
                                <div class="attendance-details">
                                    <div class="check-in">
                                        <i class="fas fa-sign-in-alt"></i>
                                        <span>09:30 AM</span>
                                    </div>
                                    <div class="check-out">
                                        <i class="fas fa-sign-out-alt"></i>
                                        <span>05:45 PM</span>
                                    </div>
                                </div>
                                <div class="attendance-status late">
                                    <i class="fas fa-exclamation"></i>
                                </div>
                            </div>

                            <div class="attendance-item" onclick="openAttendanceModal('2024-01-12')">
                                <div class="attendance-date">
                                    <div class="day">Fri</div>
                                    <div class="date">12</div>
                                </div>
                                <div class="attendance-details">
                                    <div class="check-in">
                                        <i class="fas fa-sign-in-alt"></i>
                                        <span>08:45 AM</span>
                                    </div>
                                    <div class="check-out">
                                        <i class="fas fa-sign-out-alt"></i>
                                        <span>06:15 PM</span>
                                    </div>
                                </div>
                                <div class="attendance-status present">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>

                            <div class="attendance-item" onclick="openAttendanceModal('2024-01-11')">
                                <div class="attendance-date">
                                    <div class="day">Thu</div>
                                    <div class="date">11</div>
                                </div>
                                <div class="attendance-details">
                                    <div class="absent-text">Absent</div>
                                </div>
                                <div class="attendance-status absent">
                                    <i class="fas fa-times"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tasks Section -->
                    <div class="tasks-section">
                        <div class="section-header">
                            <h3 class="section-title">
                                <i class="fas fa-tasks"></i>
                                Today's Tasks
                            </h3>
                        </div>
                        
                        <div class="tasks-list">
                            <div class="task-item">
                                <div class="task-checkbox">
                                    <input type="checkbox" id="task1" checked>
                                    <label for="task1"></label>
                                </div>
                                <div class="task-content">
                                    <div class="task-title">Follow up with John Smith</div>
                                    <div class="task-time">10:00 AM</div>
                                </div>
                                <div class="task-priority high">High</div>
                            </div>

                            <div class="task-item">
                                <div class="task-checkbox">
                                    <input type="checkbox" id="task2">
                                    <label for="task2"></label>
                                </div>
                                <div class="task-content">
                                    <div class="task-title">Prepare proposal for TechCorp</div>
                                    <div class="task-time">02:00 PM</div>
                                </div>
                                <div class="task-priority medium">Medium</div>
                            </div>

                            <div class="task-item">
                                <div class="task-checkbox">
                                    <input type="checkbox" id="task3">
                                    <label for="task3"></label>
                                </div>
                                <div class="task-content">
                                    <div class="task-title">Team meeting</div>
                                    <div class="task-time">04:00 PM</div>
                                </div>
                                <div class="task-priority low">Low</div>
                            </div>

                            <div class="task-item">
                                <div class="task-checkbox">
                                    <input type="checkbox" id="task4">
                                    <label for="task4"></label>
                                </div>
                                <div class="task-content">
                                    <div class="task-title">Update lead database</div>
                                    <div class="task-time">05:30 PM</div>
                                </div>
                                <div class="task-priority medium">Medium</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lead Detail Modal -->
    <div class="modal-overlay" id="leadModal">
        <div class="modal-container">
            <div class="modal-header">
                <h2 class="modal-title">Lead Details</h2>
                <button class="modal-close" onclick="closeModal('leadModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-content">
                <div class="lead-detail-grid">
                    <div class="lead-detail-left">
                        <div class="lead-avatar-large">JS</div>
                        <div class="lead-basic-info">
                            <h3 id="modalLeadName">Susanta Swain</h3>
                            <p id="modalLeadCompany">Nexpro Solution.</p>
                            <div class="lead-contact">
                                <div class="contact-item">
                                    <i class="fas fa-phone"></i>
                                    <span id="modalLeadPhone">+91 6372356394</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-envelope"></i>
                                    <span id="modalLeadEmail">Susanta.swain@email.com</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span id="modalLeadLocation">Puri,Odisha, India</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-birthday-cake"></i>
                                    <span id="modalLeadDOB">January 15, 1985</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lead-detail-right">
                        <div class="detail-section">
                            <h4>Business Information</h4>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <label>Lead Amount</label>
                                    <span id="modalLeadAmount" class="amount">15,000</span>
                                </div>
                                <div class="detail-item">
                                    <label>Expected Salary</label>
                                    <span id="modalLeadSalary">85,000/year</span>
                                </div>
                                <div class="detail-item">
                                    <label>Success Rate</label>
                                    <span id="modalLeadSuccess">75%</span>
                                </div>
                                <div class="detail-item">
                                    <label>Expected Month</label>
                                    <span id="modalLeadMonth">March 2024</span>
                                </div>
                                <div class="detail-item">
                                    <label>Created Date</label>
                                    <span id="modalLeadCreated">January 10, 2024</span>
                                </div>
                                <div class="detail-item">
                                    <label>Status</label>
                                    <span id="modalLeadStatus" class="status hot">Rejected</span>
                                </div>
                            </div>
                        </div>
                        <div class="detail-section">
                            <h4>Remarks</h4>
                            <div class="remarks-box">
                                <p id="modalLeadRemarks">Very interested in our premium package. Scheduled follow-up call for next week. Decision maker confirmed.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn secondary" onclick="closeModal('leadModal')">Close</button>
                <button class="btn primary" onclick="forwardToTeamLead()">
                    <i class="fas fa-share"></i>
                    Forward to Team Lead
                </button>
            </div>
        </div>
    </div>

    <!-- Attendance Detail Modal -->
    <div class="modal-overlay" id="attendanceModal">
        <div class="modal-container">
            <div class="modal-header">
                <h2 class="modal-title">Attendance Details</h2>
                <button class="modal-close" onclick="closeModal('attendanceModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-content">
                <div class="attendance-detail">
                    <div class="attendance-photo">
                        <img id="modalAttendancePhoto" src="https://ui-avatars.com/api/?name=John+Doe&background=f97316&color=fff&size=150" alt="Attendance Photo">
                    </div>
                    <div class="attendance-info">
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Employee Name</label>
                                <span id="modalEmployeeName">{{ Auth::user()->name ?? 'John Doe' }}</span>
                            </div>
                            <div class="info-item">
                                <label>Date</label>
                                <span id="modalAttendanceDate">Monday, January 15, 2024</span>
                            </div>
                            <div class="info-item">
                                <label>Check-in Time</label>
                                <span id="modalCheckInTime">09:15 AM</span>
                            </div>
                            <div class="info-item">
                                <label>Check-out Time</label>
                                <span id="modalCheckOutTime">06:30 PM</span>
                            </div>
                            <div class="info-item">
                                <label>Total Hours</label>
                                <span id="modalTotalHours">9 hours 15 minutes</span>
                            </div>
                            <div class="info-item">
                                <label>Status</label>
                                <span id="modalAttendanceStatus" class="status present">Present</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn secondary" onclick="closeModal('attendanceModal')">Close</button>
            </div>
        </div>
    </div>

    <style>
        /* Dashboard Styles */
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
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
        }

        .leads-section {
            background: white;
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid #f3f4f6;
            animation: slideInLeft 0.8s ease-out;
        }

        .side-panel {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .attendance-section, .tasks-section {
            background: white;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid #f3f4f6;
            animation: slideInRight 0.8s ease-out;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title i {
            color: #f97316;
        }

        .action-btn {
            padding: 20px 40px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .action-btn.primary {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
        }

        .action-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(249, 115, 22, 0.3);
        }

        /* Table Styles */
        .table-container {
            overflow: hidden;
            border-radius: 16px;
            border: 1px solid #f3f4f6;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .leads-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .leads-table th {
            background: #f9fafb;
            padding: 16px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
        }

        .leads-table td {
            padding: 16px 12px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 14px;
        }

        .table-row {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .table-row:hover {
            background: #fef3f2;
            transform: scale(1.01);
        }

        .lead-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .lead-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }

        .lead-name {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 2px;
        }

        .lead-location {
            font-size: 12px;
            color: #6b7280;
        }

        .amount {
            font-weight: 700;
            color: #059669;
        }

        .progress-bar {
            position: relative;
            width: 80px;
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #f97316, #ea580c);
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        .progress-text {
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
        }

        .status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status.hot {
            background: #fee2e2;
            color: #dc2626;
        }

        .status.warm {
            background: #fef3c7;
            color: #d97706;
        }

        .status.cold {
            background: #dbeafe;
            color: #2563eb;
        }

        .table-action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .table-action-btn:hover {
            background: #f97316;
            border-color: #f97316;
            color: white;
            transform: scale(1.1);
        }

        /* Attendance Styles */
        .attendance-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .attendance-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: #f9fafb;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .attendance-item:hover {
            background: #f3f4f6;
            transform: translateX(4px);
        }

        .attendance-date {
            text-align: center;
            min-width: 40px;
        }

        .attendance-date .day {
            font-size: 12px;
            color: #6b7280;
            font-weight: 500;
        }

        .attendance-date .date {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
        }

        .attendance-details {
            flex: 1;
        }

        .check-in, .check-out {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .check-in i {
            color: #059669;
        }

        .check-out i {
            color: #dc2626;
        }

        .absent-text {
            color: #6b7280;
            font-style: italic;
            font-size: 14px;
        }

        .attendance-status {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .attendance-status.present {
            background: #dcfce7;
            color: #16a34a;
        }

        .attendance-status.late {
            background: #fef3c7;
            color: #d97706;
        }

        .attendance-status.absent {
            background: #fee2e2;
            color: #dc2626;
        }

        /* Tasks Styles */
        .tasks-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .task-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            background: #f9fafb;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .task-item:hover {
            background: #f3f4f6;
        }

        .task-checkbox {
            position: relative;
        }

        .task-checkbox input[type="checkbox"] {
            width: 20px;
            height: 20px;
            accent-color: #f97316;
            cursor: pointer;
        }

        .task-content {
            flex: 1;
        }

        .task-title {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .task-time {
            font-size: 12px;
            color: #6b7280;
        }

        .task-priority {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .task-priority.high {
            background: #fee2e2;
            color: #dc2626;
        }

        .task-priority.medium {
            background: #fef3c7;
            color: #d97706;
        }

        .task-priority.low {
            background: #dcfce7;
            color: #16a34a;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
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
            border-radius: 20px;
            max-width: 800px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            transform: scale(0.9);
            transition: transform 0.3s ease;
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
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            background: #f97316;
            color: white;
        }

        .modal-content {
            padding: 32px;
        }

        .lead-detail-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 32px;
        }

        .lead-avatar-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .lead-basic-info h3 {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .lead-basic-info p {
            color: #6b7280;
            margin-bottom: 20px;
        }

        .lead-contact {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
        }

        .contact-item i {
            width: 16px;
            color: #f97316;
        }

        .detail-section {
            margin-bottom: 24px;
        }

        .detail-section h4 {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 16px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .detail-item label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
        }

        .detail-item span {
            font-size: 14px;
            color: #1f2937;
            font-weight: 500;
        }

        .remarks-box {
            background: #f9fafb;
            border-radius: 12px;
            padding: 16px;
            border: 1px solid #e5e7eb;
        }

        .remarks-box p {
            color: #374151;
            line-height: 1.6;
            margin: 0;
        }

        .modal-footer {
            padding: 24px 32px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn.primary {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
        }

        .btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(249, 115, 22, 0.3);
        }

        .btn.secondary {
            background: #f3f4f6;
            color: #6b7280;
            border: 1px solid #e5e7eb;
        }

        .btn.secondary:hover {
            background: #e5e7eb;
        }

        /* Attendance Modal Styles */
        .attendance-detail {
            display: flex;
            gap: 32px;
            align-items: flex-start;
        }

        .attendance-photo {
            flex-shrink: 0;
        }

        .attendance-photo img {
            width: 150px;
            height: 150px;
            border-radius: 16px;
            object-fit: cover;
            border: 3px solid #f97316;
        }

        .attendance-info {
            flex: 1;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .info-item label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
        }

        .info-item span {
            font-size: 16px;
            color: #1f2937;
            font-weight: 600;
        }

        .status.present {
            background: #dcfce7;
            color: #16a34a;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-block;
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

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .lead-detail-grid {
                grid-template-columns: 1fr;
            }
            
            .detail-grid {
                grid-template-columns: 1fr;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .attendance-detail {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>

    <script>
        // Add any dashboard-specific JavaScript here
        document.addEventListener('DOMContentLoaded', function() {
            // Animate counters
            const statValues = document.querySelectorAll('.stat-value');
            
            statValues.forEach(stat => {
                const finalValue = stat.textContent;
                const numericValue = parseInt(finalValue.replace(/[^0-9]/g, ''));
                
                if (!isNaN(numericValue)) {
                    let currentValue = 0;
                    const increment = numericValue / 50;
                    const timer = setInterval(() => {
                        currentValue += increment;
                        if (currentValue >= numericValue) {
                            stat.textContent = finalValue;
                            clearInterval(timer);
                        } else {
                            stat.textContent = Math.floor(currentValue).toLocaleString();
                        }
                    }, 30);
                }
            });
        });

        // Modal Functions
        function openLeadModal(leadId) {
            const modal = document.getElementById('leadModal');
            modal.classList.add('active');
            
            // Here you would typically fetch lead data based on leadId
            // For demo purposes, we'll use static data
            const leadData = {
                1: {
                    name: 'John Smith',
                    company: 'TechCorp Inc.',
                    phone: '+1 234 567 8900',
                    email: 'john.smith@email.com',
                    location: 'New York, USA',
                    dob: 'January 15, 1985',
                    amount: '$15,000',
                    salary: '$85,000/year',
                    success: '75%',
                    month: 'March 2024',
                    created: 'January 10, 2024',
                    status: 'Hot',
                    remarks: 'Very interested in our premium package. Scheduled follow-up call for next week. Decision maker confirmed.'
                }
                // Add more lead data as needed
            };
            
            const lead = leadData[leadId] || leadData[1];
            
            // Update modal content
            document.getElementById('modalLeadName').textContent = lead.name;
            document.getElementById('modalLeadCompany').textContent = lead.company;
            document.getElementById('modalLeadPhone').textContent = lead.phone;
            document.getElementById('modalLeadEmail').textContent = lead.email;
            document.getElementById('modalLeadLocation').textContent = lead.location;
            document.getElementById('modalLeadDOB').textContent = lead.dob;
            document.getElementById('modalLeadAmount').textContent = lead.amount;
            document.getElementById('modalLeadSalary').textContent = lead.salary;
            document.getElementById('modalLeadSuccess').textContent = lead.success;
            document.getElementById('modalLeadMonth').textContent = lead.month;
            document.getElementById('modalLeadCreated').textContent = lead.created;
            document.getElementById('modalLeadStatus').textContent = lead.status;
            document.getElementById('modalLeadRemarks').textContent = lead.remarks;
        }

        function openAttendanceModal(date) {
            const modal = document.getElementById('attendanceModal');
            modal.classList.add('active');
            
            // Update modal content based on date
            const attendanceData = {
                '2024-01-15': {
                    employeeName: '{{ Auth::user()->name ?? "John Doe" }}',
                    date: 'Monday, January 15, 2024',
                    checkIn: '09:15 AM',
                    checkOut: '06:30 PM',
                    totalHours: '9 hours 15 minutes',
                    status: 'Present',
                    photo: 'https://ui-avatars.com/api/?name=John+Doe&background=f97316&color=fff&size=150'
                }
                // Add more attendance data as needed
            };
            
            const attendance = attendanceData[date] || attendanceData['2024-01-15'];
            
            // Update modal content
            document.getElementById('modalEmployeeName').textContent = attendance.employeeName;
            document.getElementById('modalAttendanceDate').textContent = attendance.date;
            document.getElementById('modalCheckInTime').textContent = attendance.checkIn;
            document.getElementById('modalCheckOutTime').textContent = attendance.checkOut;
            document.getElementById('modalTotalHours').textContent = attendance.totalHours;
            document.getElementById('modalAttendanceStatus').textContent = attendance.status;
            document.getElementById('modalAttendancePhoto').src = attendance.photo;
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('active');
        }

        function forwardToTeamLead(leadId) {
            // Handle forwarding to team lead
            alert('Lead forwarded to team lead successfully!');
            closeModal('leadModal');
        }

        function openAddLeadModal() {
            // Handle opening add lead modal
            alert('Add Lead modal would open here');
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