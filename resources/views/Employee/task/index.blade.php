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
        @include('Employee.Components.header', ['title' => 'Task Management', 'subtitle' => 'Monitor and manage all tasks'])
        
        <div class="dashboard-container">
            <!-- Page Header with Stats -->
            <div class="page-header">
                <div class="header-content">
                    <h1 class="page-title">Task Management</h1>
                    <p class="page-subtitle">Track progress and manage team tasks efficiently</p>
                </div>
                <div class="task-stats">
                    <div class="stat-item">
                        <div class="stat-value" id="totalTasks">0</div>
                        <div class="stat-label">Total Tasks</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="pendingTasks">0</div>
                        <div class="stat-label">Pending</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="completedTasks">0</div>
                        <div class="stat-label">Completed</div>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="filters-section">
                <div class="filters-header">
                    <h3>Filters</h3>
                    <button class="filter-toggle" onclick="toggleFilters()">
                        <i class="fas fa-filter"></i>
                        <span>Show Filters</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="filters-content" id="filtersContent">
                    <div class="filters-grid">
                        <div class="filter-group">
                            <label for="employeeFilter">Employee</label>
                            <select id="employeeFilter" class="filter-select" onchange="filterTasks()">
                                <option value="">All Employees</option>
                                <option value="rajesh-kumar">Rajesh Kumar</option>
                                <option value="priya-sharma">Priya Sharma</option>
                                <option value="amit-patel">Amit Patel</option>
                                <option value="sneha-reddy">Sneha Reddy</option>
                                <option value="vikram-singh">Vikram Singh</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="statusFilter">Status</label>
                            <select id="statusFilter" class="filter-select" onchange="filterTasks()">
                                <option value="">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="in-progress">In Progress</option>
                                <option value="review">Under Review</option>
                                <option value="completed">Completed</option>
                                <option value="overdue">Overdue</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="priorityFilter">Priority</label>
                            <select id="priorityFilter" class="filter-select" onchange="filterTasks()">
                                <option value="">All Priorities</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="dateFilter">Date Range</label>
                            <select id="dateFilter" class="filter-select" onchange="filterTasks()">
                                <option value="">All Time</option>
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                                <option value="overdue">Overdue</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="searchFilter">Search</label>
                            <div class="search-input-wrapper">
                                <input type="text" id="searchFilter" class="filter-input" placeholder="Search tasks..." oninput="filterTasks()">
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

            <!-- View Toggle -->
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
                    Showing <span id="resultsCount">0</span> of <span id="totalCount">0</span> tasks
                </div>
            </div>

            <!-- Tasks Container -->
            <div class="tasks-container">
                <!-- Grid View -->
                <div id="gridView" class="tasks-grid">
                    <!-- Task cards will be populated here -->
                </div>

                <!-- List View -->
                <div id="listView" class="tasks-list" style="display: none;">
                    <div class="list-header">
                        <div class="list-col">Task</div>
                        <div class="list-col">Assigned To</div>
                        <div class="list-col">Priority</div>
                        <div class="list-col">Status</div>
                        <div class="list-col">Due Date</div>
                        <div class="list-col">Actions</div>
                    </div>
                    <div id="listContent">
                        <!-- List items will be populated here -->
                    </div>
                </div>

                <!-- Empty State -->
                <div id="emptyState" class="empty-state" style="display: none;">
                    <div class="empty-state-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <h3>No tasks found</h3>
                    <p>Try adjusting your filters or create a new task</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Detail Modal -->
    <div class="modal-overlay" id="taskDetailModal">
        <div class="modal-container">
            <div class="modal-header">
                <h2 class="modal-title">Task Details</h2>
                <button class="modal-close" onclick="closeModal('taskDetailModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-content">
                <div class="task-detail-grid">
                    <div class="task-detail-left">
                        <div class="task-header">
                            <h3 id="modalTaskTitle">Complete Lead Analysis Report</h3>
                            <div class="task-meta">
                                <span id="modalTaskPriority" class="priority high">High Priority</span>
                                <span id="modalTaskStatus" class="status in-progress">In Progress</span>
                            </div>
                        </div>
                        
                        <div class="task-description">
                            <h4>Description</h4>
                            <p id="modalTaskDescription">Analyze the lead conversion data for Q1 2024 and prepare a comprehensive report with insights and recommendations for improving conversion rates.</p>
                        </div>

                        <div class="task-assignment">
                            <h4>Assignment Details</h4>
                            <div class="assignment-info">
                                <div class="assignee-info">
                                    <div class="assignee-avatar" id="modalAssigneeAvatar">RK</div>
                                    <div class="assignee-details">
                                        <div class="assignee-name" id="modalAssigneeName">Rajesh Kumar</div>
                                        <div class="assignee-role" id="modalAssigneeRole">Senior Analyst</div>
                                    </div>
                                </div>
                                <div class="assignment-dates">
                                    <div class="date-item">
                                        <label>Assigned On</label>
                                        <span id="modalAssignedDate">January 15, 2024</span>
                                    </div>
                                    <div class="date-item">
                                        <label>Due Date</label>
                                        <span id="modalDueDate">January 30, 2024</span>
                                    </div>
                                    <div class="date-item">
                                        <label>Assigned By</label>
                                        <span id="modalAssignedBy">Admin User</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="task-detail-right">
                        <div class="task-progress">
                            <h4>Progress Tracking</h4>
                            <div class="progress-info">
                                <div class="progress-bar">
                                    <div class="progress-fill" id="modalProgressFill" style="width: 65%"></div>
                                </div>
                                <div class="progress-text">
                                    <span id="modalProgressPercent">65%</span> Complete
                                </div>
                            </div>
                        </div>

                        <div class="task-timeline">
                            <h4>Activity Timeline</h4>
                            <div class="timeline-list" id="modalTimeline">
                                <!-- Timeline items will be populated here -->
                            </div>
                        </div>

                        <div class="task-attachments">
                            <h4>Attachments</h4>
                            <div class="attachments-list" id="modalAttachments">
                                <div class="attachment-item">
                                    <i class="fas fa-file-pdf"></i>
                                    <span>Q1_Lead_Data.pdf</span>
                                    <button class="download-btn">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeModal('taskDetailModal')">
                    <i class="fas fa-times"></i>
                    Close
                </button>
                <button class="btn-primary" onclick="openStatusModal()">
                    <i class="fas fa-edit"></i>
                    Update Status
                </button>
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div class="modal-overlay" id="statusUpdateModal">
        <div class="modal-container modal-sm">
            <div class="modal-header">
                <h2 class="modal-title">Update Task Status</h2>
                <button class="modal-close" onclick="closeModal('statusUpdateModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-content">
                <form id="statusUpdateForm" onsubmit="updateTaskStatus(event)">
                    <input type="hidden" id="updateTaskId" value="">
                    
                    <div class="form-group">
                        <label for="newStatus">New Status</label>
                        <select id="newStatus" class="form-control" required>
                            <option value="pending">Pending</option>
                            <option value="in-progress">In Progress</option>
                            <option value="review">Under Review</option>
                            <option value="completed">Completed</option>
                            <option value="overdue">Overdue</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="progressPercent">Progress (%)</label>
                        <input type="range" id="progressPercent" class="progress-slider" min="0" max="100" value="0" oninput="updateProgressDisplay()">
                        <div class="progress-display">
                            <span id="progressDisplay">0%</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="statusRemarks">Remarks</label>
                        <textarea id="statusRemarks" class="form-control" rows="4" placeholder="Add any comments or notes about this status update..."></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="closeModal('statusUpdateModal')">
                            <i class="fas fa-times"></i>
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i>
                            Update Status
                        </button>
                    </div>
                </form>
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
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-subtitle {
            font-size: 16px;
            color: #6b7280;
            font-weight: 500;
        }

        .task-stats {
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
            color: #3b82f6;
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
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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

        /* View Controls */
        .view-controls {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            animation: slideInRight 0.6s ease-out;
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
            background: #3b82f6;
            color: white;
        }

        .results-info {
            font-size: 14px;
            color: #6b7280;
            font-weight: 500;
        }

        /* Tasks Container */
        .tasks-container {
            animation: fadeInUp 0.8s ease-out;
        }

        /* Grid View */
        .tasks-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 24px;
        }

        .task-card {
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

        .task-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3b82f6, #1d4ed8);
        }

        .task-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        .task-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .task-title {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .task-id {
            font-size: 12px;
            color: #6b7280;
            font-weight: 500;
        }

        .task-priority {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .priority.low {
            background: #dcfce7;
            color: #166534;
        }

        .priority.medium {
            background: #fef3c7;
            color: #92400e;
        }

        .priority.high {
            background: #fee2e2;
            color: #991b1b;
        }

        .priority.urgent {
            background: #fdf2f8;
            color: #be185d;
        }

        .task-description {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 20px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .task-meta {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
        }

        .task-assignee {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .assignee-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #3b82f6;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 12px;
        }

        .assignee-name {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
        }

        .task-due-date {
            font-size: 12px;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .task-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
        }

        .task-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status.pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status.in-progress {
            background: #dbeafe;
            color: #1e40af;
        }

        .status.review {
            background: #fdf4ff;
            color: #a21caf;
        }

        .status.completed {
            background: #dcfce7;
            color: #166534;
        }

        .status.overdue {
            background: #fee2e2;
            color: #991b1b;
        }

        .task-progress {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .progress-bar {
            width: 60px;
            height: 6px;
            background: #e5e7eb;
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #3b82f6, #1d4ed8);
            border-radius: 3px;
            transition: width 0.3s ease;
        }

        .progress-text {
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
        }

        /* List View */
        .tasks-list {
            background: white;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .list-header {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr 1fr;
            gap: 16px;
            padding: 16px 24px;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            font-weight: 700;
            color: #4b5563;
        }

        .list-item {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr 1fr;
            gap: 16px;
            padding: 20px 24px;
            border-bottom: 1px solid #e5e7eb;
            transition: all 0.2s ease;
            cursor: pointer;
            align-items: center;
        }

        .list-item:hover {
            background: #f9fafb;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .list-task-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .list-task-title {
            font-weight: 600;
            color: #1f2937;
            font-size: 15px;
        }

        .list-task-id {
            font-size: 12px;
            color: #6b7280;
        }

        .list-assignee {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .list-actions {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            color: #4b5563;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            background: #3b82f6;
            border-color: #3b82f6;
            color: white;
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

        /* Task Detail Modal */
        .task-detail-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 32px;
        }

        .task-header {
            margin-bottom: 24px;
        }

        .task-header h3 {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .task-meta {
            display: flex;
            gap: 12px;
        }

        .task-description {
            margin-bottom: 32px;
        }

        .task-description h4,
        .task-assignment h4,
        .task-progress h4,
        .task-timeline h4,
        .task-attachments h4 {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 16px;
            position: relative;
            padding-left: 16px;
        }

        .task-description h4::before,
        .task-assignment h4::before,
        .task-progress h4::before,
        .task-timeline h4::before,
        .task-attachments h4::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 16px;
            background: #3b82f6;
            border-radius: 2px;
        }

        .task-description p {
            color: #4b5563;
            line-height: 1.7;
            font-size: 15px;
        }

        .assignment-info {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .assignee-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .assignee-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #3b82f6;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
        }

        .assignee-details {
            flex: 1;
        }

        .assignee-name {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 2px;
        }

        .assignee-role {
            font-size: 14px;
            color: #6b7280;
        }

        .assignment-dates {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .date-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .date-item label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
        }

        .date-item span {
            font-size: 14px;
            color: #1f2937;
            font-weight: 500;
        }

        .progress-info {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-text {
            font-size: 14px;
            font-weight: 600;
            color: #4b5563;
        }

        .timeline-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .timeline-item {
            display: flex;
            gap: 12px;
            padding: 12px;
            background: #f9fafb;
            border-radius: 8px;
            border-left: 3px solid #3b82f6;
        }

        .timeline-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #3b82f6;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            flex-shrink: 0;
        }

        .timeline-content {
            flex: 1;
        }

        .timeline-action {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 2px;
        }

        .timeline-date {
            font-size: 12px;
            color: #6b7280;
        }

        .attachments-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .attachment-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #f9fafb;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .attachment-item i {
            color: #ef4444;
            font-size: 16px;
        }

        .attachment-item span {
            flex: 1;
            font-size: 14px;
            color: #1f2937;
            font-weight: 500;
        }

        .download-btn {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            background: white;
            color: #4b5563;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .download-btn:hover {
            background: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }

        /* Status Update Form */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            color: #1f2937;
            background: #f9fafb;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            background: white;
        }

        .progress-slider {
            width: 100%;
            height: 6px;
            border-radius: 3px;
            background: #e5e7eb;
            outline: none;
            -webkit-appearance: none;
        }

        .progress-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #3b82f6;
            cursor: pointer;
        }

        .progress-display {
            text-align: center;
            margin-top: 8px;
        }

        .progress-display span {
            font-size: 16px;
            font-weight: 600;
            color: #3b82f6;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
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
            background: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
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
            .tasks-grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }

            .task-detail-grid {
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

            .task-stats {
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

            .tasks-grid {
                grid-template-columns: 1fr;
            }

            .list-header,
            .list-item {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .list-header {
                display: none;
            }

            .list-item {
                flex-direction: column;
                align-items: flex-start;
                padding: 16px;
            }

            .assignment-dates {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        // Sample task data
        const tasksData = [
            {
                id: 1,
                title: 'Complete Lead Analysis Report',
                description: 'Analyze the lead conversion data for Q1 2024 and prepare a comprehensive report with insights and recommendations for improving conversion rates.',
                assignee: {
                    id: 'rajesh-kumar',
                    name: 'Amresh Pradhan',
                    role: 'Market Analyst',
                    avatar: 'AP'
                },
                assignedBy: 'Team Lead Ramesh',
                assignedDate: '2024-01-15',
                dueDate: '2024-01-30',
                status: 'in-progress',
                priority: 'high',
                progress: 50,
                timeline: [
                    { action: 'Task assigned', date: '2024-01-15 09:00 AM', icon: 'plus' },
                    { action: 'Started working', date: '2024-01-16 10:30 AM', icon: 'play' },
                    { action: 'Progress update: 30%', date: '2024-01-20 02:15 PM', icon: 'chart-line' },
                    { action: 'Progress update: 65%', date: '2024-01-25 11:45 AM', icon: 'chart-line' }
                ],
                attachments: [
                    { name: 'Q1_Lead_Data.pdf', type: 'pdf' },
                    { name: 'Analysis_Template.xlsx', type: 'excel' }
                ]
            },
            // {
            //     id: 2,
            //     title: 'Update Customer Database',
            //     description: 'Clean and update the customer database by removing duplicates, validating contact information, and adding missing details.',
            //     assignee: {
            //         id: 'priya-sharma',
            //         name: 'Priya Sharma',
            //         role: 'Data Specialist',
            //         avatar: 'PS'
            //     },
            //     assignedBy: 'Admin User',
            //     assignedDate: '2024-01-20',
            //     dueDate: '2024-02-05',
            //     status: 'pending',
            //     priority: 'medium',
            //     progress: 0,
            //     timeline: [
            //         { action: 'Task assigned', date: '2024-01-20 02:30 PM', icon: 'plus' }
            //     ],
            //     attachments: []
            // },
            // {
            //     id: 3,
            //     title: 'Prepare Monthly Sales Presentation',
            //     description: 'Create a comprehensive presentation showcasing monthly sales performance, key metrics, and strategic recommendations for the upcoming quarter.',
            //     assignee: {
            //         id: 'amit-patel',
            //         name: 'Amit Patel',
            //         role: 'Sales Manager',
            //         avatar: 'AP'
            //     },
            //     assignedBy: 'Admin User',
            //     assignedDate: '2024-01-10',
            //     dueDate: '2024-01-25',
            //     status: 'review',
            //     priority: 'high',
            //     progress: 90,
            //     timeline: [
            //         { action: 'Task assigned', date: '2024-01-10 09:00 AM', icon: 'plus' },
            //         { action: 'Started working', date: '2024-01-11 08:30 AM', icon: 'play' },
            //         { action: 'First draft completed', date: '2024-01-18 04:00 PM', icon: 'file' },
            //         { action: 'Submitted for review', date: '2024-01-22 11:00 AM', icon: 'eye' }
            //     ],
            //     attachments: [
            //         { name: 'Sales_Presentation_Draft.pptx', type: 'powerpoint' }
            //     ]
            // },
            // {
            //     id: 4,
            //     title: 'Implement Security Updates',
            //     description: 'Apply latest security patches and updates to all systems, conduct vulnerability assessment, and update security protocols.',
            //     assignee: {
            //         id: 'sneha-reddy',
            //         name: 'Sneha Reddy',
            //         role: 'IT Security Specialist',
            //         avatar: 'SR'
            //     },
            //     assignedBy: 'Admin User',
            //     assignedDate: '2024-01-18',
            //     dueDate: '2024-01-28',
            //     status: 'overdue',
            //     priority: 'urgent',
            //     progress: 45,
            //     timeline: [
            //         { action: 'Task assigned', date: '2024-01-18 10:00 AM', icon: 'plus' },
            //         { action: 'Started security audit', date: '2024-01-19 09:15 AM', icon: 'shield-alt' },
            //         { action: 'Progress update: 45%', date: '2024-01-24 03:30 PM', icon: 'chart-line' }
            //     ],
            //     attachments: [
            //         { name: 'Security_Checklist.pdf', type: 'pdf' },
            //         { name: 'Vulnerability_Report.docx', type: 'word' }
            //     ]
            // },
            // {
            //     id: 5,
            //     title: 'Design Marketing Campaign',
            //     description: 'Create visual assets and marketing materials for the upcoming product launch campaign including banners, social media posts, and email templates.',
            //     assignee: {
            //         id: 'vikram-singh',
            //         name: 'Vikram Singh',
            //         role: 'Graphic Designer',
            //         avatar: 'VS'
            //     },
            //     assignedBy: 'Admin User',
            //     assignedDate: '2024-01-22',
            //     dueDate: '2024-02-10',
            //     status: 'in-progress',
            //     priority: 'medium',
            //     progress: 30,
            //     timeline: [
            //         { action: 'Task assigned', date: '2024-01-22 11:30 AM', icon: 'plus' },
            //         { action: 'Initial concepts created', date: '2024-01-24 02:00 PM', icon: 'palette' },
            //         { action: 'Progress update: 30%', date: '2024-01-26 04:45 PM', icon: 'chart-line' }
            //     ],
            //     attachments: [
            //         { name: 'Brand_Guidelines.pdf', type: 'pdf' },
            //         { name: 'Campaign_Brief.docx', type: 'word' }
            //     ]
            // },
            {
                id: 6,
                title: 'Make a Lead Report of January 2024',
                description: 'Compile a detailed report of all leads generated in January 2024, including source analysis and conversion rates.',
                assignee: {
                    id: 'rajesh-kumar',
                    name: 'Amresh Pradhan',
                    role: 'Marketing',
                    avatar: 'AP'
                },
                assignedBy: 'Team Lead ramesh',
                assignedDate: '2024-01-25',
                dueDate: '2024-02-08',
                status: 'completed',
                priority: 'high',
                progress: 100,
                timeline: [
                    { action: 'Task assigned', date: '2024-01-25 09:00 AM', icon: 'plus' },
                    { action: 'Code review started', date: '2024-01-26 10:00 AM', icon: 'code' },
                    { action: 'Testing completed', date: '2024-01-28 03:30 PM', icon: 'check' },
                    { action: 'Task completed', date: '2024-01-29 05:00 PM', icon: 'check-circle' }
                ],
                attachments: [
                    { name: 'Test_Results.pdf', type: 'pdf' },
                    { name: 'Code_Review_Notes.txt', type: 'text' }
                ]
            }
        ];

        // Global variables
        let filteredTasks = [...tasksData];
        let currentView = 'grid';
        let currentTaskId = null;

        // DOM Ready
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the page
            renderTasks();
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

        // Filter tasks
        function filterTasks() {
            const employeeFilter = document.getElementById('employeeFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            const priorityFilter = document.getElementById('priorityFilter').value;
            const dateFilter = document.getElementById('dateFilter').value;
            const searchFilter = document.getElementById('searchFilter').value.toLowerCase();

            filteredTasks = tasksData.filter(task => {
                // Employee filter
                if (employeeFilter && task.assignee.id !== employeeFilter) return false;
                
                // Status filter
                if (statusFilter && task.status !== statusFilter) return false;
                
                // Priority filter
                if (priorityFilter && task.priority !== priorityFilter) return false;
                
                // Date filter
                if (dateFilter) {
                    const today = new Date();
                    const taskDue = new Date(task.dueDate);
                    
                    switch (dateFilter) {
                        case 'today':
                            if (taskDue.toDateString() !== today.toDateString()) return false;
                            break;
                        case 'week':
                            const weekFromNow = new Date(today.getTime() + 7 * 24 * 60 * 60 * 1000);
                            if (taskDue < today || taskDue > weekFromNow) return false;
                            break;
                        case 'month':
                            const monthFromNow = new Date(today.getFullYear(), today.getMonth() + 1, today.getDate());
                            if (taskDue < today || taskDue > monthFromNow) return false;
                            break;
                        case 'overdue':
                            if (taskDue >= today || task.status === 'completed') return false;
                            break;
                    }
                }
                
                // Search filter
                if (searchFilter) {
                    const searchableFields = [
                        task.title,
                        task.description,
                        task.assignee.name
                    ].map(field => field.toLowerCase());
                    
                    return searchableFields.some(field => field.includes(searchFilter));
                }
                
                return true;
            });

            renderTasks();
            updateStats();
            updateResultsCount();
        }

        // Reset filters
        function resetFilters() {
            document.getElementById('employeeFilter').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('priorityFilter').value = '';
            document.getElementById('dateFilter').value = '';
            document.getElementById('searchFilter').value = '';
            
            filteredTasks = [...tasksData];
            renderTasks();
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
            
            renderTasks();
        }

        // Render tasks
        function renderTasks() {
            const gridView = document.getElementById('gridView');
            const listContent = document.getElementById('listContent');
            const emptyState = document.getElementById('emptyState');
            
            // Clear existing content
            gridView.innerHTML = '';
            listContent.innerHTML = '';
            
            if (filteredTasks.length === 0) {
                emptyState.style.display = 'flex';
                gridView.style.display = 'none';
                document.getElementById('listView').style.display = 'none';
                return;
            }
            
            emptyState.style.display = 'none';
            
            if (currentView === 'grid') {
                gridView.style.display = 'grid';
                document.getElementById('listView').style.display = 'none';
                renderGridView();
            } else {
                gridView.style.display = 'none';
                document.getElementById('listView').style.display = 'block';
                renderListView();
            }
        }

        // Render grid view
        function renderGridView() {
            const gridView = document.getElementById('gridView');
            
            filteredTasks.forEach(task => {
                const taskCard = document.createElement('div');
                taskCard.className = 'task-card';
                taskCard.onclick = () => viewTaskDetails(task.id);
                
                const dueDate = new Date(task.dueDate);
                const formattedDueDate = dueDate.toLocaleDateString('en-IN', {
                    day: 'numeric',
                    month: 'short'
                });
                
                // Check if overdue
                const isOverdue = dueDate < new Date() && task.status !== 'completed';
                const actualStatus = isOverdue ? 'overdue' : task.status;
                
                taskCard.innerHTML = `
                    <div class="task-card-header">
                        <div>
                            <div class="task-title">${task.title}</div>
                            <div class="task-id">#${task.id.toString().padStart(3, '0')}</div>
                        </div>
                        <div class="task-priority priority ${task.priority}">${task.priority}</div>
                    </div>
                    
                    <div class="task-description">${task.description}</div>
                    
                    <div class="task-meta">
                        <div class="task-assignee">
                            <div class="assignee-avatar">${task.assignee.avatar}</div>
                            <div class="assignee-name">${task.assignee.name}</div>
                        </div>
                        <div class="task-due-date">
                            <i class="fas fa-calendar"></i>
                            ${formattedDueDate}
                        </div>
                    </div>
                    
                    <div class="task-footer">
                        <div class="task-status status ${actualStatus}">${actualStatus.replace('-', ' ')}</div>
                        <div class="task-progress">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: ${task.progress}%"></div>
                            </div>
                            <div class="progress-text">${task.progress}%</div>
                        </div>
                    </div>
                `;
                
                gridView.appendChild(taskCard);
            });
        }

        // Render list view
        function renderListView() {
            const listContent = document.getElementById('listContent');
            
            filteredTasks.forEach(task => {
                const listItem = document.createElement('div');
                listItem.className = 'list-item';
                listItem.onclick = () => viewTaskDetails(task.id);
                
                const dueDate = new Date(task.dueDate);
                const formattedDueDate = dueDate.toLocaleDateString('en-IN', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
                
                // Check if overdue
                const isOverdue = dueDate < new Date() && task.status !== 'completed';
                const actualStatus = isOverdue ? 'overdue' : task.status;
                
                listItem.innerHTML = `
                    <div class="list-task-info">
                        <div class="list-task-title">${task.title}</div>
                        <div class="list-task-id">#${task.id.toString().padStart(3, '0')}</div>
                    </div>
                    <div class="list-assignee">
                        <div class="assignee-avatar">${task.assignee.avatar}</div>
                        <div class="assignee-name">${task.assignee.name}</div>
                    </div>
                    <div class="task-priority priority ${task.priority}">${task.priority}</div>
                    <div class="task-status status ${actualStatus}">${actualStatus.replace('-', ' ')}</div>
                    <div>${formattedDueDate}</div>
                    <div class="list-actions">
                        <button class="action-btn" onclick="viewTaskDetails(${task.id}); event.stopPropagation();" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="action-btn" onclick="openStatusModal(${task.id}); event.stopPropagation();" title="Update Status">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                `;
                
                listContent.appendChild(listItem);
            });
        }

        // Update stats
        function updateStats() {
            const totalTasks = filteredTasks.length;
            const pendingTasks = filteredTasks.filter(task => task.status === 'pending' || task.status === 'in-progress').length;
            const completedTasks = filteredTasks.filter(task => task.status === 'completed').length;
            
            // Animate counters
            animateCounter('totalTasks', totalTasks);
            animateCounter('pendingTasks', pendingTasks);
            animateCounter('completedTasks', completedTasks);
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
            document.getElementById('resultsCount').textContent = filteredTasks.length;
            document.getElementById('totalCount').textContent = tasksData.length;
        }

        // View task details
        function viewTaskDetails(id) {
            const task = tasksData.find(task => task.id === id);
            if (!task) return;
            
            currentTaskId = id;
            
            // Format dates
            const assignedDate = new Date(task.assignedDate);
            const dueDate = new Date(task.dueDate);
            const formattedAssignedDate = assignedDate.toLocaleDateString('en-IN', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            const formattedDueDate = dueDate.toLocaleDateString('en-IN', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            
            // Check if overdue
            const isOverdue = dueDate < new Date() && task.status !== 'completed';
            const actualStatus = isOverdue ? 'overdue' : task.status;
            
            // Update modal content
            document.getElementById('modalTaskTitle').textContent = task.title;
            document.getElementById('modalTaskDescription').textContent = task.description;
            
            const priorityElement = document.getElementById('modalTaskPriority');
            priorityElement.textContent = `${task.priority} Priority`;
            priorityElement.className = `priority ${task.priority}`;
            
            const statusElement = document.getElementById('modalTaskStatus');
            statusElement.textContent = actualStatus.replace('-', ' ');
            statusElement.className = `status ${actualStatus}`;
            
            document.getElementById('modalAssigneeAvatar').textContent = task.assignee.avatar;
            document.getElementById('modalAssigneeName').textContent = task.assignee.name;
            document.getElementById('modalAssigneeRole').textContent = task.assignee.role;
            document.getElementById('modalAssignedDate').textContent = formattedAssignedDate;
            document.getElementById('modalDueDate').textContent = formattedDueDate;
            document.getElementById('modalAssignedBy').textContent = task.assignedBy;
            
            // Update progress
            document.getElementById('modalProgressFill').style.width = `${task.progress}%`;
            document.getElementById('modalProgressPercent').textContent = task.progress;
            
            // Update timeline
            const timelineContainer = document.getElementById('modalTimeline');
            timelineContainer.innerHTML = '';
            
            task.timeline.forEach(item => {
                const timelineItem = document.createElement('div');
                timelineItem.className = 'timeline-item';
                timelineItem.innerHTML = `
                    <div class="timeline-icon">
                        <i class="fas fa-${item.icon}"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-action">${item.action}</div>
                        <div class="timeline-date">${item.date}</div>
                    </div>
                `;
                timelineContainer.appendChild(timelineItem);
            });
            
            // Update attachments
            const attachmentsContainer = document.getElementById('modalAttachments');
            attachmentsContainer.innerHTML = '';
            
            if (task.attachments.length === 0) {
                attachmentsContainer.innerHTML = '<p style="color: #6b7280; font-style: italic;">No attachments</p>';
            } else {
                task.attachments.forEach(attachment => {
                    const attachmentItem = document.createElement('div');
                    attachmentItem.className = 'attachment-item';
                    
                    let iconClass = 'fas fa-file';
                    switch (attachment.type) {
                        case 'pdf':
                            iconClass = 'fas fa-file-pdf';
                            break;
                        case 'excel':
                            iconClass = 'fas fa-file-excel';
                            break;
                        case 'word':
                            iconClass = 'fas fa-file-word';
                            break;
                        case 'powerpoint':
                            iconClass = 'fas fa-file-powerpoint';
                            break;
                        case 'text':
                            iconClass = 'fas fa-file-alt';
                            break;
                    }
                    
                    attachmentItem.innerHTML = `
                        <i class="${iconClass}"></i>
                        <span>${attachment.name}</span>
                        <button class="download-btn" onclick="downloadAttachment('${attachment.name}')">
                            <i class="fas fa-download"></i>
                        </button>
                    `;
                    attachmentsContainer.appendChild(attachmentItem);
                });
            }
            
            // Show modal
            const modal = document.getElementById('taskDetailModal');
            modal.classList.add('active');
        }

        // Open status update modal
        function openStatusModal(taskId = null) {
            if (taskId) {
                currentTaskId = taskId;
            }
            
            if (!currentTaskId) return;
            
            const task = tasksData.find(task => task.id === currentTaskId);
            if (!task) return;
            
            // Populate current values
            document.getElementById('updateTaskId').value = currentTaskId;
            document.getElementById('newStatus').value = task.status;
            document.getElementById('progressPercent').value = task.progress;
            document.getElementById('progressDisplay').textContent = `${task.progress}%`;
            document.getElementById('statusRemarks').value = '';
            
            // Close detail modal if open
            closeModal('taskDetailModal');
            
            // Show status update modal
            const modal = document.getElementById('statusUpdateModal');
            modal.classList.add('active');
        }

        // Update progress display
        function updateProgressDisplay() {
            const progressValue = document.getElementById('progressPercent').value;
            document.getElementById('progressDisplay').textContent = `${progressValue}%`;
        }

        // Update task status
        function updateTaskStatus(event) {
            event.preventDefault();
            
            const taskId = parseInt(document.getElementById('updateTaskId').value);
            const newStatus = document.getElementById('newStatus').value;
            const newProgress = parseInt(document.getElementById('progressPercent').value);
            const remarks = document.getElementById('statusRemarks').value;
            
            // Find and update task
            const taskIndex = tasksData.findIndex(task => task.id === taskId);
            if (taskIndex !== -1) {
                tasksData[taskIndex].status = newStatus;
                tasksData[taskIndex].progress = newProgress;
                
                // Add timeline entry
                const now = new Date();
                const timeString = now.toLocaleDateString('en-IN', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                }) + ' ' + now.toLocaleTimeString('en-IN', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
                
                let action = `Status updated to ${newStatus.replace('-', ' ')}`;
                if (remarks) {
                    action += ` - ${remarks}`;
                }
                
                tasksData[taskIndex].timeline.push({
                    action: action,
                    date: timeString,
                    icon: 'edit'
                });
                
                // Update filtered tasks
                filterTasks();
                
                // Show notification
                showNotification('Task status updated successfully', 'success');
            }
            
            // Close modal
            closeModal('statusUpdateModal');
        }

        // Download attachment
        function downloadAttachment(filename) {
            // In a real application, this would trigger an actual download
            showNotification(`Downloading ${filename}`, 'info');
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