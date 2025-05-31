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
        @include('Employee.Components.header', ['title' => 'Attendance Management', 'subtitle' => 'Track your attendance and working hours'])
        
        <div class="dashboard-container">
            <!-- Page Header with Stats -->
            <div class="page-header">
                <div class="header-content">
                    <h1 class="page-title">My Attendance</h1>
                    <p class="page-subtitle">Monitor your attendance records and working hours</p>
                </div>
                <div class="header-actions">
                    <div class="attendance-stats">
                        <div class="stat-item">
                            <div class="stat-value" id="totalDays">0</div>
                            <div class="stat-label">Total Days</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="presentDays">0</div>
                            <div class="stat-label">Present</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="absentDays">0</div>
                            <div class="stat-label">Absent</div>
                        </div>
                    </div>
                    <button class="take-attendance-btn" onclick="openTakeAttendanceModal()">
                        <div class="btn-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="btn-content">
                            <span class="btn-title">Take Attendance</span>
                            <span class="btn-subtitle" id="attendanceStatus">Check In</span>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Current Status Card -->
            <div class="current-status-card">
                <div class="status-header">
                    <h3>Today's Status</h3>
                    <div class="current-date" id="currentDate"></div>
                </div>
                <div class="status-content">
                    <div class="status-item">
                        <div class="status-icon checkin">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                        <div class="status-details">
                            <div class="status-label">Check In</div>
                            <div class="status-time" id="todayCheckin">--:--</div>
                        </div>
                    </div>
                    <div class="status-divider"></div>
                    <div class="status-item">
                        <div class="status-icon checkout">
                            <i class="fas fa-sign-out-alt"></i>
                        </div>
                        <div class="status-details">
                            <div class="status-label">Check Out</div>
                            <div class="status-time" id="todayCheckout">--:--</div>
                        </div>
                    </div>
                    <div class="status-divider"></div>
                    <div class="status-item">
                        <div class="status-icon working">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="status-details">
                            <div class="status-label">Working Hours</div>
                            <div class="status-time" id="todayWorking">0h 0m</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="filters-section">
                <div class="filters-header">
                    <h3>Filter Attendance</h3>
                    <button class="filter-toggle" onclick="toggleFilters()">
                        <i class="fas fa-filter"></i>
                        <span>Show Filters</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="filters-content" id="filtersContent">
                    <div class="filters-grid">
                        <div class="filter-group">
                            <label for="monthFilter">Month</label>
                            <select id="monthFilter" class="filter-select" onchange="filterAttendance()">
                                <option value="">All Months</option>
                                <option value="2024-01">January 2024</option>
                                <option value="2024-02">February 2024</option>
                                <option value="2024-03">March 2024</option>
                                <option value="2024-04">April 2024</option>
                                <option value="2024-05">May 2024</option>
                                <option value="2024-06">June 2024</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="statusFilter">Status</label>
                            <select id="statusFilter" class="filter-select" onchange="filterAttendance()">
                                <option value="">All Status</option>
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                                <option value="late">Late</option>
                                <option value="half-day">Half Day</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="dateFromFilter">Date From</label>
                            <input type="date" id="dateFromFilter" class="filter-input" onchange="filterAttendance()">
                        </div>
                        <div class="filter-group">
                            <label for="dateToFilter">Date To</label>
                            <input type="date" id="dateToFilter" class="filter-input" onchange="filterAttendance()">
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

            <!-- Attendance Records -->
            <div class="attendance-section">
                <div class="section-header">
                    <h3>Attendance Records</h3>
                    <div class="results-info">
                        Showing <span id="resultsCount">0</span> records
                    </div>
                </div>
                
                <div class="attendance-grid" id="attendanceGrid">
                    <!-- Attendance cards will be populated here -->
                </div>

                <!-- Empty State -->
                <div id="emptyState" class="empty-state" style="display: none;">
                    <div class="empty-state-icon">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <h3>No attendance records found</h3>
                    <p>Try adjusting your filters or take your first attendance</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Take Attendance Modal -->
    <div class="modal-overlay" id="takeAttendanceModal">
        <div class="modal-container">
            <div class="modal-header">
                <h2 class="modal-title">Take Attendance</h2>
                <button class="modal-close" onclick="closeModal('takeAttendanceModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-content">
                <div class="attendance-form">
                    <div class="current-info">
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Date</label>
                                <span id="attendanceDate"></span>
                            </div>
                            <div class="info-item">
                                <label>Time</label>
                                <span id="attendanceTime"></span>
                            </div>
                            <div class="info-item">
                                <label>Employee</label>
                                <span>{{ Auth::user()->name ?? 'Rajesh Kumar' }}</span>
                            </div>
                            <div class="info-item">
                                <label>Action</label>
                                <span id="attendanceAction">Check In</span>
                            </div>
                        </div>
                    </div>

                    <div class="photo-section">
                        <h4>Take Photo</h4>
                        <div class="camera-container">
                            <video id="cameraVideo" autoplay style="display: none;"></video>
                            <canvas id="photoCanvas" style="display: none;"></canvas>
                            <div id="photoPreview" class="photo-preview">
                                <div class="camera-placeholder">
                                    <i class="fas fa-camera"></i>
                                    <p>Click to take photo</p>
                                </div>
                            </div>
                        </div>
                        <div class="camera-controls">
                            <button type="button" class="btn-camera" onclick="startCamera()">
                                <i class="fas fa-camera"></i>
                                Start Camera
                            </button>
                            <button type="button" class="btn-camera" onclick="capturePhoto()" style="display: none;" id="captureBtn">
                                <i class="fas fa-camera"></i>
                                Capture Photo
                            </button>
                            <button type="button" class="btn-camera" onclick="retakePhoto()" style="display: none;" id="retakeBtn">
                                <i class="fas fa-redo"></i>
                                Retake
                            </button>
                        </div>
                    </div>

                    <div class="location-section">
                        <h4>Location Information</h4>
                        <div class="location-info" id="locationInfo">
                            <div class="location-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span id="locationText">Getting location...</span>
                            </div>
                            <div class="location-item">
                                <i class="fas fa-globe"></i>
                                <span id="coordinatesText">Coordinates: --</span>
                            </div>
                        </div>
                        <button type="button" class="btn-location" onclick="getCurrentLocation()">
                            <i class="fas fa-location-arrow"></i>
                            Get Current Location
                        </button>
                    </div>

                    <div class="notes-section">
                        <label for="attendanceNotes">Notes (Optional)</label>
                        <textarea id="attendanceNotes" class="form-control" rows="3" placeholder="Add any notes about your attendance..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeModal('takeAttendanceModal')">
                    <i class="fas fa-times"></i>
                    Cancel
                </button>
                <button class="btn-primary" onclick="submitAttendance()" id="submitAttendanceBtn">
                    <i class="fas fa-check"></i>
                    <span id="submitBtnText">Check In</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Attendance Detail Modal -->
    <div class="modal-overlay" id="attendanceDetailModal">
        <div class="modal-container">
            <div class="modal-header">
                <h2 class="modal-title">Attendance Details</h2>
                <button class="modal-close" onclick="closeModal('attendanceDetailModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-content">
                <div class="attendance-detail-grid">
                    <div class="detail-left">
                        <div class="employee-info">
                            <div class="employee-photo">
                                <img id="modalEmployeePhoto" src="/placeholder.svg" alt="Employee Photo">
                            </div>
                            <div class="employee-details">
                                <h3 id="modalEmployeeName">{{ Auth::user()->name ?? 'Rajesh Kumar' }}</h3>
                                <p id="modalEmployeeRole">Software Developer</p>
                                <div class="attendance-status-badge">
                                    <span id="modalAttendanceStatus" class="status present">Present</span>
                                </div>
                            </div>
                        </div>

                        <div class="time-details">
                            <h4>Time Details</h4>
                            <div class="time-grid">
                                <div class="time-item">
                                    <div class="time-icon checkin">
                                        <i class="fas fa-sign-in-alt"></i>
                                    </div>
                                    <div class="time-info">
                                        <label>Check In</label>
                                        <span id="modalCheckinTime">09:15 AM</span>
                                    </div>
                                </div>
                                <div class="time-item">
                                    <div class="time-icon checkout">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </div>
                                    <div class="time-info">
                                        <label>Check Out</label>
                                        <span id="modalCheckoutTime">06:30 PM</span>
                                    </div>
                                </div>
                                <div class="time-item">
                                    <div class="time-icon working">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="time-info">
                                        <label>Total Hours</label>
                                        <span id="modalTotalHours">9h 15m</span>
                                    </div>
                                </div>
                                <div class="time-item">
                                    <div class="time-icon break">
                                        <i class="fas fa-coffee"></i>
                                    </div>
                                    <div class="time-info">
                                        <label>Break Time</label>
                                        <span id="modalBreakTime">1h 0m</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="detail-right">
                        <div class="location-details">
                            <h4>Location Information</h4>
                            <div class="location-detail-grid">
                                <div class="location-detail-item">
                                    <label>Check In Location</label>
                                    <div class="location-text">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span id="modalCheckinLocation">Office Building, Mumbai</span>
                                    </div>
                                </div>
                                <div class="location-detail-item">
                                    <label>Check Out Location</label>
                                    <div class="location-text">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span id="modalCheckoutLocation">Office Building, Mumbai</span>
                                    </div>
                                </div>
                                <div class="location-detail-item">
                                    <label>Coordinates</label>
                                    <div class="coordinates-text">
                                        <i class="fas fa-globe"></i>
                                        <span id="modalCoordinates">19.0760, 72.8777</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="notes-details">
                            <h4>Notes</h4>
                            <div class="notes-content">
                                <p id="modalNotes">Regular working day. No issues.</p>
                            </div>
                        </div>

                        <div class="complaint-section">
                            <h4>Raise Complaint</h4>
                            <p>If you have any issues with this attendance record, you can raise a complaint.</p>
                            <button class="btn-complaint" onclick="openComplaintModal()">
                                <i class="fas fa-exclamation-triangle"></i>
                                Raise Complaint
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeModal('attendanceDetailModal')">
                    <i class="fas fa-times"></i>
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Complaint Modal -->
    <div class="modal-overlay" id="complaintModal">
        <div class="modal-container modal-sm">
            <div class="modal-header">
                <h2 class="modal-title">Raise Complaint</h2>
                <button class="modal-close" onclick="closeModal('complaintModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-content">
                <form id="complaintForm" onsubmit="submitComplaint(event)">
                    <div class="form-group">
                        <label for="complaintType">Complaint Type</label>
                        <select id="complaintType" class="form-control" required>
                            <option value="">Select complaint type</option>
                            <option value="wrong-time">Wrong Check-in/Check-out Time</option>
                            <option value="location-issue">Location Issue</option>
                            <option value="system-error">System Error</option>
                            <option value="missed-punch">Missed Punch</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="complaintDescription">Description</label>
                        <textarea id="complaintDescription" class="form-control" rows="4" required placeholder="Please describe your complaint in detail..."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="complaintPriority">Priority</label>
                        <select id="complaintPriority" class="form-control" required>
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="closeModal('complaintModal')">
                            <i class="fas fa-times"></i>
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-paper-plane"></i>
                            Submit Complaint
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
            background: linear-gradient(135deg, #10b981, #059669);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-subtitle {
            font-size: 16px;
            color: #6b7280;
            font-weight: 500;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .attendance-stats {
            display: flex;
            gap: 16px;
        }

        .stat-item {
            text-align: center;
            padding: 16px 20px;
            background: white;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            min-width: 100px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 800;
            color: #10b981;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
        }

        .take-attendance-btn {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 24px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.25);
            position: relative;
            overflow: hidden;
        }

        .take-attendance-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .take-attendance-btn:hover::before {
            left: 100%;
        }

        .take-attendance-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(16, 185, 129, 0.35);
        }

        .btn-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .btn-content {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .btn-title {
            font-size: 16px;
            font-weight: 700;
            line-height: 1.2;
        }

        .btn-subtitle {
            font-size: 12px;
            opacity: 0.9;
            font-weight: 500;
        }

        /* Current Status Card */
        .current-status-card {
            background: white;
            border-radius: 20px;
            padding: 28px;
            margin-bottom: 32px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            animation: slideInLeft 0.6s ease-out;
        }

        .status-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .status-header h3 {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
        }

        .current-date {
            font-size: 14px;
            color: #6b7280;
            font-weight: 600;
        }

        .status-content {
            display: grid;
            grid-template-columns: 1fr auto 1fr auto 1fr;
            gap: 24px;
            align-items: center;
        }

        .status-item {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .status-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
        }

        .status-icon.checkin {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .status-icon.checkout {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .status-icon.working {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .status-details {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .status-label {
            font-size: 14px;
            color: #6b7280;
            font-weight: 600;
        }

        .status-time {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
        }

        .status-divider {
            width: 2px;
            height: 40px;
            background: #e5e7eb;
            border-radius: 1px;
        }

        /* Filters Section */
        .filters-section {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            animation: slideInRight 0.6s ease-out;
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
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
            background: white;
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

        /* Attendance Section */
        .attendance-section {
            animation: fadeInUp 0.8s ease-out;
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

        .results-info {
            font-size: 14px;
            color: #6b7280;
            font-weight: 500;
        }

        .attendance-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 24px;
        }

        .attendance-card {
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

        .attendance-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #10b981, #059669);
        }

        .attendance-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .card-date {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
        }

        .card-day {
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
        }

        .attendance-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status.present {
            background: #dcfce7;
            color: #166534;
        }

        .status.absent {
            background: #fee2e2;
            color: #991b1b;
        }

        .status.late {
            background: #fef3c7;
            color: #92400e;
        }

        .status.half-day {
            background: #dbeafe;
            color: #1e40af;
        }

        .card-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 20px;
        }

        .time-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .time-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
        }

        .time-value {
            font-size: 16px;
            font-weight: 700;
            color: #1f2937;
        }

        .card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
        }

        .working-hours {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #10b981;
        }

        .location-info {
            font-size: 12px;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 4px;
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
            max-width: 900px;
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

        /* Take Attendance Modal */
        .attendance-form {
            display: flex;
            flex-direction: column;
            gap: 32px;
        }

        .current-info {
            background: #f9fafb;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e5e7eb;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
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

        .photo-section h4,
        .location-section h4,
        .notes-section label {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 16px;
        }

        .camera-container {
            position: relative;
            width: 100%;
            max-width: 400px;
            margin: 0 auto 16px;
        }

        .photo-preview {
            width: 100%;
            height: 300px;
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f9fafb;
            overflow: hidden;
        }

        .camera-placeholder {
            text-align: center;
            color: #6b7280;
        }

        .camera-placeholder i {
            font-size: 48px;
            margin-bottom: 12px;
            display: block;
        }

        .camera-controls {
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .btn-camera, .btn-location {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: #10b981;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-camera:hover, .btn-location:hover {
            background: #059669;
        }

        .location-info {
            background: #f9fafb;
            border-radius: 12px;
            padding: 16px;
            border: 1px solid #e5e7eb;
            margin-bottom: 16px;
        }

        .location-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
            font-size: 14px;
            color: #4b5563;
        }

        .location-item:last-child {
            margin-bottom: 0;
        }

        .location-item i {
            color: #10b981;
            width: 16px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            color: #1f2937;
            background: #f9fafb;
            transition: all 0.2s ease;
            resize: vertical;
        }

        .form-control:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
            background: white;
        }

        /* Attendance Detail Modal */
        .attendance-detail-grid {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 32px;
        }

        .employee-info {
            text-align: center;
            margin-bottom: 32px;
        }

        .employee-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 16px;
            border: 4px solid #10b981;
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }

        .employee-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .employee-details h3 {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .employee-details p {
            color: #6b7280;
            margin-bottom: 16px;
        }

        .attendance-status-badge {
            display: flex;
            justify-content: center;
        }

        .time-details h4,
        .location-details h4,
        .notes-details h4,
        .complaint-section h4 {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 16px;
            position: relative;
            padding-left: 16px;
        }

        .time-details h4::before,
        .location-details h4::before,
        .notes-details h4::before,
        .complaint-section h4::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 16px;
            background: #10b981;
            border-radius: 2px;
        }

        .time-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .time-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: #f9fafb;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }

        .time-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: white;
        }

        .time-icon.checkin {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .time-icon.checkout {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .time-icon.working {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .time-icon.break {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .time-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .time-info label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
        }

        .time-info span {
            font-size: 16px;
            color: #1f2937;
            font-weight: 600;
        }

        .location-detail-grid {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 24px;
        }

        .location-detail-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .location-detail-item label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
        }

        .location-text, .coordinates-text {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #4b5563;
        }

        .location-text i, .coordinates-text i {
            color: #10b981;
        }

        .notes-content {
            background: #f9fafb;
            border-radius: 12px;
            padding: 16px;
            border: 1px solid #e5e7eb;
            margin-bottom: 24px;
        }

        .notes-content p {
            color: #4b5563;
            line-height: 1.6;
            margin: 0;
        }

        .complaint-section p {
            color: #6b7280;
            margin-bottom: 16px;
            line-height: 1.6;
        }

        .btn-complaint {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            background: #f59e0b;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-complaint:hover {
            background: #d97706;
        }

        /* Form Styles */
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
            background: #10b981;
            color: white;
        }

        .btn-primary:hover {
            background: #059669;
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
            .attendance-grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }

            .attendance-detail-grid {
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

            .header-actions {
                width: 100%;
                flex-direction: column;
                gap: 16px;
            }

            .attendance-stats {
                width: 100%;
                justify-content: space-between;
            }

            .stat-item {
                min-width: auto;
                flex: 1;
                padding: 12px 16px;
            }

            .status-content {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .status-divider {
                display: none;
            }

            .filters-grid {
                grid-template-columns: 1fr;
            }

            .attendance-grid {
                grid-template-columns: 1fr;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .time-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        // Sample attendance data
        const attendanceData = [
            {
                id: 1,
                date: '2024-01-30',
                day: 'Tuesday',
                checkinTime: '09:15 AM',
                checkoutTime: '06:30 PM',
                totalHours: '9h 15m',
                breakTime: '1h 0m',
                status: 'present',
                checkinLocation: 'Office Building, Mumbai',
                checkoutLocation: 'Office Building, Mumbai',
                coordinates: '19.0760, 72.8777',
                photo: 'https://ui-avatars.com/api/?name=Rajesh+Kumar&background=10b981&color=fff&size=120',
                notes: 'Regular working day. No issues.'
            },
            {
                id: 2,
                date: '2024-01-29',
                day: 'Monday',
                checkinTime: '09:30 AM',
                checkoutTime: '06:00 PM',
                totalHours: '8h 30m',
                breakTime: '1h 0m',
                status: 'late',
                checkinLocation: 'Office Building, Mumbai',
                checkoutLocation: 'Office Building, Mumbai',
                coordinates: '19.0760, 72.8777',
                photo: 'https://ui-avatars.com/api/?name=Rajesh+Kumar&background=f59e0b&color=fff&size=120',
                notes: 'Traffic delay in the morning.'
            },
            {
                id: 3,
                date: '2024-01-28',
                day: 'Sunday',
                checkinTime: '--',
                checkoutTime: '--',
                totalHours: '0h 0m',
                breakTime: '0h 0m',
                status: 'absent',
                checkinLocation: '--',
                checkoutLocation: '--',
                coordinates: '--',
                photo: '',
                notes: 'Weekend - Not working day.'
            },
            {
                id: 4,
                date: '2024-01-27',
                day: 'Saturday',
                checkinTime: '09:00 AM',
                checkoutTime: '02:00 PM',
                totalHours: '5h 0m',
                breakTime: '0h 0m',
                status: 'half-day',
                checkinLocation: 'Office Building, Mumbai',
                checkoutLocation: 'Office Building, Mumbai',
                coordinates: '19.0760, 72.8777',
                photo: 'https://ui-avatars.com/api/?name=Rajesh+Kumar&background=3b82f6&color=fff&size=120',
                notes: 'Saturday half day work.'
            },
            {
                id: 5,
                date: '2024-01-26',
                day: 'Friday',
                checkinTime: '09:10 AM',
                checkoutTime: '06:45 PM',
                totalHours: '9h 35m',
                breakTime: '1h 0m',
                status: 'present',
                checkinLocation: 'Office Building, Mumbai',
                checkoutLocation: 'Office Building, Mumbai',
                coordinates: '19.0760, 72.8777',
                photo: 'https://ui-avatars.com/api/?name=Rajesh+Kumar&background=10b981&color=fff&size=120',
                notes: 'Productive day with extra hours.'
            }
        ];

        // Global variables
        let filteredAttendance = [...attendanceData];
        let currentAttendanceId = null;
        let isCheckedIn = false;
        let todayAttendance = null;
        let cameraStream = null;
        let currentLocation = null;

        // DOM Ready
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the page
            updateCurrentDate();
            checkTodayStatus();
            renderAttendance();
            updateStats();
            updateResultsCount();
            getCurrentLocation();
        });

        // Update current date
        function updateCurrentDate() {
            const now = new Date();
            const dateString = now.toLocaleDateString('en-IN', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            document.getElementById('currentDate').textContent = dateString;
        }

        // Check today's attendance status
        function checkTodayStatus() {
            const today = new Date().toISOString().split('T')[0];
            todayAttendance = attendanceData.find(record => record.date === today);
            
            if (todayAttendance) {
                document.getElementById('todayCheckin').textContent = todayAttendance.checkinTime;
                document.getElementById('todayCheckout').textContent = todayAttendance.checkoutTime;
                document.getElementById('todayWorking').textContent = todayAttendance.totalHours;
                
                if (todayAttendance.checkinTime !== '--' && todayAttendance.checkoutTime === '--') {
                    isCheckedIn = true;
                    document.getElementById('attendanceStatus').textContent = 'Check Out';
                } else if (todayAttendance.checkoutTime !== '--') {
                    document.getElementById('attendanceStatus').textContent = 'Completed';
                }
            }
        }

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

        // Filter attendance
        function filterAttendance() {
            const monthFilter = document.getElementById('monthFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            const dateFromFilter = document.getElementById('dateFromFilter').value;
            const dateToFilter = document.getElementById('dateToFilter').value;

            filteredAttendance = attendanceData.filter(record => {
                // Month filter
                if (monthFilter && !record.date.startsWith(monthFilter)) return false;
                
                // Status filter
                if (statusFilter && record.status !== statusFilter) return false;
                
                // Date range filter
                if (dateFromFilter) {
                    const fromDate = new Date(dateFromFilter);
                    const recordDate = new Date(record.date);
                    if (recordDate < fromDate) return false;
                }
                
                if (dateToFilter) {
                    const toDate = new Date(dateToFilter);
                    const recordDate = new Date(record.date);
                    if (recordDate > toDate) return false;
                }
                
                return true;
            });

            renderAttendance();
            updateStats();
            updateResultsCount();
        }

        // Reset filters
        function resetFilters() {
            document.getElementById('monthFilter').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('dateFromFilter').value = '';
            document.getElementById('dateToFilter').value = '';
            
            filteredAttendance = [...attendanceData];
            renderAttendance();
            updateStats();
            updateResultsCount();
        }

        // Render attendance
        function renderAttendance() {
            const attendanceGrid = document.getElementById('attendanceGrid');
            const emptyState = document.getElementById('emptyState');
            
            // Clear existing content
            attendanceGrid.innerHTML = '';
            
            if (filteredAttendance.length === 0) {
                emptyState.style.display = 'flex';
                attendanceGrid.style.display = 'none';
                return;
            }
            
            emptyState.style.display = 'none';
            attendanceGrid.style.display = 'grid';
            
            filteredAttendance.forEach(record => {
                const attendanceCard = document.createElement('div');
                attendanceCard.className = 'attendance-card';
                attendanceCard.onclick = () => viewAttendanceDetails(record.id);
                
                const recordDate = new Date(record.date);
                const formattedDate = recordDate.getDate();
                const formattedMonth = recordDate.toLocaleDateString('en-IN', { month: 'short' });
                
                attendanceCard.innerHTML = `
                    <div class="card-header">
                        <div>
                            <div class="card-date">${formattedDate} ${formattedMonth}</div>
                            <div class="card-day">${record.day}</div>
                        </div>
                        <div class="attendance-status status ${record.status}">${record.status}</div>
                    </div>
                    
                    <div class="card-content">
                        <div class="time-info">
                            <div class="time-label">Check In</div>
                            <div class="time-value">${record.checkinTime}</div>
                        </div>
                        <div class="time-info">
                            <div class="time-label">Check Out</div>
                            <div class="time-value">${record.checkoutTime}</div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <div class="working-hours">
                            <i class="fas fa-clock"></i>
                            ${record.totalHours}
                        </div>
                        <div class="location-info">
                            <i class="fas fa-map-marker-alt"></i>
                            ${record.checkinLocation.split(',')[0]}
                        </div>
                    </div>
                `;
                
                attendanceGrid.appendChild(attendanceCard);
            });
        }

        // Update stats
        function updateStats() {
            const totalDays = filteredAttendance.length;
            const presentDays = filteredAttendance.filter(record => record.status === 'present' || record.status === 'late' || record.status === 'half-day').length;
            const absentDays = filteredAttendance.filter(record => record.status === 'absent').length;
            
            // Animate counters
            animateCounter('totalDays', totalDays);
            animateCounter('presentDays', presentDays);
            animateCounter('absentDays', absentDays);
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
            document.getElementById('resultsCount').textContent = filteredAttendance.length;
        }

        // Open take attendance modal
        function openTakeAttendanceModal() {
            const now = new Date();
            const dateString = now.toLocaleDateString('en-IN', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            const timeString = now.toLocaleTimeString('en-IN', {
                hour: '2-digit',
                minute: '2-digit'
            });
            
            document.getElementById('attendanceDate').textContent = dateString;
            document.getElementById('attendanceTime').textContent = timeString;
            
            // Update action text based on current status
            if (isCheckedIn) {
                document.getElementById('attendanceAction').textContent = 'Check Out';
                document.getElementById('submitBtnText').textContent = 'Check Out';
            } else {
                document.getElementById('attendanceAction').textContent = 'Check In';
                document.getElementById('submitBtnText').textContent = 'Check In';
            }
            
            // Reset form
            document.getElementById('attendanceNotes').value = '';
            resetCamera();
            
            // Show modal
            const modal = document.getElementById('takeAttendanceModal');
            modal.classList.add('active');
        }

        // View attendance details
        function viewAttendanceDetails(id) {
            const record = attendanceData.find(record => record.id === id);
            if (!record) return;
            
            currentAttendanceId = id;
            
            // Update modal content
            document.getElementById('modalEmployeePhoto').src = record.photo || 'https://ui-avatars.com/api/?name=Employee&background=10b981&color=fff&size=120';
            
            const statusElement = document.getElementById('modalAttendanceStatus');
            statusElement.textContent = record.status;
            statusElement.className = `status ${record.status}`;
            
            document.getElementById('modalCheckinTime').textContent = record.checkinTime;
            document.getElementById('modalCheckoutTime').textContent = record.checkoutTime;
            document.getElementById('modalTotalHours').textContent = record.totalHours;
            document.getElementById('modalBreakTime').textContent = record.breakTime;
            document.getElementById('modalCheckinLocation').textContent = record.checkinLocation;
            document.getElementById('modalCheckoutLocation').textContent = record.checkoutLocation;
            document.getElementById('modalCoordinates').textContent = record.coordinates;
            document.getElementById('modalNotes').textContent = record.notes;
            
            // Show modal
            const modal = document.getElementById('attendanceDetailModal');
            modal.classList.add('active');
        }

        // Start camera
        function startCamera() {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function(stream) {
                    cameraStream = stream;
                    const video = document.getElementById('cameraVideo');
                    video.srcObject = stream;
                    video.style.display = 'block';
                    
                    // Hide placeholder and show capture button
                    document.querySelector('.camera-placeholder').style.display = 'none';
                    document.querySelector('.btn-camera').style.display = 'none';
                    document.getElementById('captureBtn').style.display = 'inline-flex';
                })
                .catch(function(error) {
                    console.error('Error accessing camera:', error);
                    showNotification('Unable to access camera. Please check permissions.', 'error');
                });
        }

        // Capture photo
        function capturePhoto() {
            const video = document.getElementById('cameraVideo');
            const canvas = document.getElementById('photoCanvas');
            const context = canvas.getContext('2d');
            
            // Set canvas dimensions to match video
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            // Draw video frame to canvas
            context.drawImage(video, 0, 0);
            
            // Convert to image and display
            const imageData = canvas.toDataURL('image/jpeg');
            const photoPreview = document.getElementById('photoPreview');
            photoPreview.innerHTML = `<img src="${imageData}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">`;
            
            // Stop camera and show retake button
            stopCamera();
            document.getElementById('captureBtn').style.display = 'none';
            document.getElementById('retakeBtn').style.display = 'inline-flex';
        }

        // Retake photo
        function retakePhoto() {
            resetCamera();
            startCamera();
        }

        // Stop camera
        function stopCamera() {
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
                cameraStream = null;
            }
            document.getElementById('cameraVideo').style.display = 'none';
        }

        // Reset camera
        function resetCamera() {
            stopCamera();
            document.getElementById('photoPreview').innerHTML = `
                <div class="camera-placeholder">
                    <i class="fas fa-camera"></i>
                    <p>Click to take photo</p>
                </div>
            `;
            document.querySelector('.btn-camera').style.display = 'inline-flex';
            document.getElementById('captureBtn').style.display = 'none';
            document.getElementById('retakeBtn').style.display = 'none';
        }

        // Get current location
        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        currentLocation = { lat, lng };
                        
                        document.getElementById('coordinatesText').textContent = `Coordinates: ${lat.toFixed(4)}, ${lng.toFixed(4)}`;
                        
                        // Reverse geocoding (simplified)
                        document.getElementById('locationText').textContent = 'Office Building, Mumbai';
                    },
                    function(error) {
                        console.error('Error getting location:', error);
                        document.getElementById('locationText').textContent = 'Location unavailable';
                        document.getElementById('coordinatesText').textContent = 'Coordinates: --';
                    }
                );
            } else {
                document.getElementById('locationText').textContent = 'Geolocation not supported';
                document.getElementById('coordinatesText').textContent = 'Coordinates: --';
            }
        }

        // Submit attendance
        function submitAttendance() {
            const notes = document.getElementById('attendanceNotes').value;
            const photoCanvas = document.getElementById('photoCanvas');
            const hasPhoto = photoCanvas.width > 0;
            
            if (!hasPhoto) {
                showNotification('Please take a photo before submitting attendance.', 'error');
                return;
            }
            
            if (!currentLocation) {
                showNotification('Location information is required.', 'error');
                return;
            }
            
            const now = new Date();
            const today = now.toISOString().split('T')[0];
            const timeString = now.toLocaleTimeString('en-IN', {
                hour: '2-digit',
                minute: '2-digit'
            });
            
            // Create or update today's attendance record
            let existingRecord = attendanceData.find(record => record.date === today);
            
            if (!existingRecord) {
                // Create new record for check-in
                const newRecord = {
                    id: attendanceData.length + 1,
                    date: today,
                    day: now.toLocaleDateString('en-IN', { weekday: 'long' }),
                    checkinTime: timeString,
                    checkoutTime: '--',
                    totalHours: '0h 0m',
                    breakTime: '1h 0m',
                    status: 'present',
                    checkinLocation: 'Office Building, Mumbai',
                    checkoutLocation: '--',
                    coordinates: `${currentLocation.lat.toFixed(4)}, ${currentLocation.lng.toFixed(4)}`,
                    photo: photoCanvas.toDataURL('image/jpeg'),
                    notes: notes || 'Check-in completed.'
                };
                
                attendanceData.unshift(newRecord);
                isCheckedIn = true;
                document.getElementById('attendanceStatus').textContent = 'Check Out';
                showNotification('Check-in successful!', 'success');
            } else if (isCheckedIn) {
                // Update existing record for check-out
                existingRecord.checkoutTime = timeString;
                existingRecord.checkoutLocation = 'Office Building, Mumbai';
                
                // Calculate total hours
                const checkinTime = new Date(`${today} ${existingRecord.checkinTime}`);
                const checkoutTime = new Date(`${today} ${timeString}`);
                const diffMs = checkoutTime - checkinTime;
                const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
                const diffMinutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
                existingRecord.totalHours = `${diffHours}h ${diffMinutes}m`;
                
                if (notes) {
                    existingRecord.notes += ` Check-out: ${notes}`;
                }
                
                isCheckedIn = false;
                document.getElementById('attendanceStatus').textContent = 'Completed';
                showNotification('Check-out successful!', 'success');
            }
            
            // Update today's status
            checkTodayStatus();
            
            // Refresh attendance list
            filteredAttendance = [...attendanceData];
            renderAttendance();
            updateStats();
            updateResultsCount();
            
            // Close modal
            closeModal('takeAttendanceModal');
        }

        // Open complaint modal
        function openComplaintModal() {
            // Close detail modal first
            closeModal('attendanceDetailModal');
            
            // Show complaint modal
            const modal = document.getElementById('complaintModal');
            modal.classList.add('active');
        }

        // Submit complaint
        function submitComplaint(event) {
            event.preventDefault();
            
            const complaintType = document.getElementById('complaintType').value;
            const description = document.getElementById('complaintDescription').value;
            const priority = document.getElementById('complaintPriority').value;
            
            // In a real application, this would send the complaint to the server
            console.log('Complaint submitted:', {
                attendanceId: currentAttendanceId,
                type: complaintType,
                description: description,
                priority: priority,
                timestamp: new Date().toISOString()
            });
            
            showNotification('Complaint submitted successfully. HR will review it shortly.', 'success');
            
            // Reset form
            document.getElementById('complaintForm').reset();
            
            // Close modal
            closeModal('complaintModal');
        }

        // Close modal
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('active');
            
            // Stop camera if take attendance modal is closed
            if (modalId === 'takeAttendanceModal') {
                stopCamera();
            }
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
                
                // Stop camera if take attendance modal is closed
                if (e.target.id === 'takeAttendanceModal') {
                    stopCamera();
                }
            }
        });
    </script>
</body>
</html>