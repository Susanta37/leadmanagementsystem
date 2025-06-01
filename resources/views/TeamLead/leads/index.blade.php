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
        @include('TeamLead.Components.header', ['title' => 'Leads Management', 'subtitle' => 'Create, view and manage your leads'])
        
        <div class="dashboard-container">
            <!-- Page Header with Filters -->
            <div class="page-header">
                <div class="header-content">
                    <h1 class="page-title">Manage Leads</h1>
                    <p class="page-subtitle">Create new leads and manage existing ones</p>
                </div>
                <div class="filter-section">
                    <button class="filter-toggle" onclick="toggleFilters()">
                        <i class="fas fa-filter"></i>
                        <span>Filters</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
            </div>

            <!-- Advanced Filters (Hidden by default) -->
            <div class="advanced-filters" id="advancedFilters">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label for="statusFilter">Status</label>
                        <select id="statusFilter" class="filter-select" onchange="filterLeads()">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="assignmentFilter">Assignment</label>
                        <select id="assignmentFilter" class="filter-select" onchange="filterLeads()">
                            <option value="">All</option>
                            <option value="assigned">Assigned</option>
                            <option value="unassigned">Unassigned</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="dateFromFilter">Date From</label>
                        <input type="date" id="dateFromFilter" class="filter-input" onchange="filterLeads()">
                    </div>
                    <div class="filter-group">
                        <label for="dateToFilter">Date To</label>
                        <input type="date" id="dateToFilter" class="filter-input" onchange="filterLeads()">
                    </div>
                    <div class="filter-group">
                        <label for="searchFilter">Search</label>
                        <div class="search-input-wrapper">
                            <input type="text" id="searchFilter" class="filter-input" placeholder="Search by name, company..." oninput="filterLeads()">
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

            <!-- Main Content Grid -->
            <div class="leads-content-grid">
                <!-- Left Side - Lead Form -->
                <div class="lead-form-container">
                    <div class="form-header">
                        <h2 id="formTitle">Add New Lead</h2>
                        <p id="formSubtitle">Fill in the details to create a new lead</p>
                    </div>
                    <form id="leadForm" class="lead-form" onsubmit="saveLead(event)">
                        <input type="hidden" id="leadId" value="">
                        
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-user"></i>
                                Personal Information
                            </h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="leadName">Full Name <span class="required">*</span></label>
                                    <input type="text" id="leadName" name="name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="leadEmail">Email Address <span class="required">*</span></label>
                                    <input type="email" id="leadEmail" name="email" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="leadPhone">Phone Number <span class="required">*</span></label>
                                    <input type="tel" id="leadPhone" name="phone" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="leadLocation">Location</label>
                                    <input type="text" id="leadLocation" name="location" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-building"></i>
                                Company Details
                            </h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="leadCompany">Company Name</label>
                                    <input type="text" id="leadCompany" name="company" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="leadPosition">Position</label>
                                    <input type="text" id="leadPosition" name="position" class="form-control">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="leadIndustry">Industry</label>
                                    <select id="leadIndustry" name="industry" class="form-control">
                                        <option value="">Select Industry</option>
                                        <option value="IT">Information Technology</option>
                                        <option value="Finance">Finance</option>
                                        <option value="Healthcare">Healthcare</option>
                                        <option value="Education">Education</option>
                                        <option value="Manufacturing">Manufacturing</option>
                                        <option value="Retail">Retail</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="leadWebsite">Website</label>
                                    <input type="url" id="leadWebsite" name="website" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-chart-line"></i>
                                Lead Details
                            </h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="leadAmount">Deal Amount (₹)</label>
                                    <input type="number" id="leadAmount" name="amount" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="leadStatus">Status</label>
                                    <select id="leadStatus" name="status" class="form-control">
                                        <option value="pending">Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="leadSource">Lead Source</label>
                                    <select id="leadSource" name="source" class="form-control">
                                        <option value="">Select Source</option>
                                        <option value="Website">Website</option>
                                        <option value="Referral">Referral</option>
                                        <option value="Social Media">Social Media</option>
                                        <option value="Email Campaign">Email Campaign</option>
                                        <option value="Cold Call">Cold Call</option>
                                        <option value="Event">Event</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="leadExpectedDate">Expected Closing Date</label>
                                    <input type="date" id="leadExpectedDate" name="expected_date" class="form-control">
                                </div>
                            </div>
                            <div class="form-group full-width">
                                <label for="leadNotes">Notes</label>
                                <textarea id="leadNotes" name="notes" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn-secondary" onclick="resetForm()">
                                <i class="fas fa-times"></i>
                                Cancel
                            </button>
                            <button type="submit" id="saveButton" class="btn-primary">
                                <i class="fas fa-save"></i>
                                Save Lead
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Side - Leads Table -->
                <div class="leads-table-container">
                    <div class="table-header">
                        <h2>ALL Leads</h2>
                        <div class="table-actions">
                            <div class="results-info">
                                Showing <span id="resultsCount">0</span> leads
                            </div>
                            <button class="btn-refresh" onclick="refreshLeads()">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                    <div class="table-wrapper">
                        <table id="leadsTable" class="leads-table">
                            <thead>
                                <tr>
                                    <th>Lead</th>
                                    <th>Company</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Team Lead</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="leadsTableBody">
                                <!-- Table rows will be populated dynamically -->
                            </tbody>
                        </table>
                    </div>
                    <div id="emptyState" class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>No leads found</h3>
                        <p>Try adjusting your filters or add a new lead</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lead Detail Modal -->
    <div class="modal-overlay" id="leadDetailModal">
        <div class="modal-container">
            <div class="modal-header">
                <h2 class="modal-title">Lead Details</h2>
                <button class="modal-close" onclick="closeModal('leadDetailModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-content">
                <div class="lead-detail-grid">
                    <div class="lead-detail-left">
                        <div class="lead-avatar-large" id="modalLeadInitials">RK</div>
                        <div class="lead-basic-info">
                            <h3 id="modalLeadName">Rajesh Kumar</h3>
                            <p id="modalLeadCompany">TechCorp India</p>
                            <div class="lead-contact">
                                <div class="contact-item">
                                    <i class="fas fa-phone"></i>
                                    <span id="modalLeadPhone">+91 98765 43210</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-envelope"></i>
                                    <span id="modalLeadEmail">rajesh.kumar@email.com</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span id="modalLeadLocation">Mumbai, Maharashtra</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-globe"></i>
                                    <span id="modalLeadWebsite">www.techcorp.com</span>
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
                                    <span id="modalLeadAmount" class="amount">₹12,50,000</span>
                                </div>
                                <div class="detail-item">
                                    <label>Industry</label>
                                    <span id="modalLeadIndustry">Information Technology</span>
                                </div>
                                <div class="detail-item">
                                    <label>Position</label>
                                    <span id="modalLeadPosition">CTO</span>
                                </div>
                                <div class="detail-item">
                                    <label>Lead Source</label>
                                    <span id="modalLeadSource">Website</span>
                                </div>
                                <div class="detail-item">
                                    <label>Expected Closing</label>
                                    <span id="modalLeadExpectedDate">March 15, 2024</span>
                                </div>
                                <div class="detail-item">
                                    <label>Status</label>
                                    <span id="modalLeadStatus" class="status pending">Pending</span>
                                </div>
                            </div>
                        </div>
                        <div class="detail-section">
                            <h4>Notes</h4>
                            <div class="remarks-box">
                                <p id="modalLeadNotes">Very interested in our premium package. Scheduled follow-up call for next week. Decision maker confirmed.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeModal('leadDetailModal')">
                    <i class="fas fa-times"></i>
                    Close
                </button>
                <button class="btn-primary" onclick="editLead(currentLeadId)">
                    <i class="fas fa-edit"></i>
                    Edit Lead
                </button>
                <button class="btn-primary" onclick="forwardToTeamLead(currentLeadId)">
        <i class="fas fa-share"></i>
        Approve & Forward to Operations
    </button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteConfirmModal">
        <div class="modal-container modal-sm">
            <div class="modal-header">
                <h2 class="modal-title">Confirm Delete</h2>
                <button class="modal-close" onclick="closeModal('deleteConfirmModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-content">
                <div class="confirm-message">
                    <div class="confirm-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <p>Are you sure you want to delete this lead? This action cannot be undone.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeModal('deleteConfirmModal')">
                    <i class="fas fa-times"></i>
                    Cancel
                </button>
                <button class="btn-danger" onclick="confirmDelete()">
                    <i class="fas fa-trash-alt"></i>
                    Delete
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
            margin-bottom: 24px;
            animation: fadeInDown 0.6s ease-out;
        }

        .header-content {
            flex: 1;
        }

        .page-title {
            font-size: 28px;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .page-subtitle {
            font-size: 15px;
            color: #6b7280;
            font-weight: 500;
        }

        .filter-toggle {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #4b5563;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .filter-toggle:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }

        /* Advanced Filters */
        .advanced-filters {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            display: none;
            animation: slideDown 0.3s ease-out;
        }

        .advanced-filters.active {
            display: block;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .filter-group label {
            font-size: 13px;
            font-weight: 600;
            color: #4b5563;
        }

        .filter-select, .filter-input {
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
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
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            color: #4b5563;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-reset:hover {
            background: #e5e7eb;
        }

        /* Main Content Grid */
        .leads-content-grid {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 24px;
        }

        /* Lead Form */
        .lead-form-container {
            background: white;
            border-radius: 12px;
            padding: 24px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            animation: slideInLeft 0.6s ease-out;
            height: fit-content;
        }

        .form-header {
            margin-bottom: 24px;
        }

        .form-header h2 {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .form-header p {
            font-size: 14px;
            color: #6b7280;
        }

        .form-section {
            margin-bottom: 24px;
            padding-bottom: 24px;
            border-bottom: 1px solid #e5e7eb;
        }

        .form-section:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title i {
            color: #3b82f6;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: #4b5563;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .required {
            color: #ef4444;
        }

        .form-control {
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
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

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
        }

        /* Leads Table */
        .leads-table-container {
            background: white;
            border-radius: 12px;
            padding: 24px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            animation: slideInRight 0.6s ease-out;
        }

        .table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .table-header h2 {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
        }

        .table-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .results-info {
            font-size: 14px;
            color: #6b7280;
        }

        .btn-refresh {
            width: 36px;
            height: 36px;
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

        .btn-refresh:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
            color: #3b82f6;
        }

        .table-wrapper {
            overflow-x: auto;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }

        .leads-table {
            width: 100%;
            border-collapse: collapse;
        }

        .leads-table th {
            background: #f9fafb;
            padding: 12px 16px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            color: #4b5563;
            border-bottom: 1px solid #e5e7eb;
        }

        .leads-table td {
            padding: 12px 16px;
            font-size: 14px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }

        .leads-table tr:last-child td {
            border-bottom: none;
        }

        .leads-table tbody tr {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .leads-table tbody tr:hover {
            background: #f9fafb;
        }

        /* Lead Info in Table */
        .lead-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .lead-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #3b82f6;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }

        .lead-details {
            display: flex;
            flex-direction: column;
        }

        .lead-name {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 2px;
        }

        .lead-email {
            font-size: 12px;
            color: #6b7280;
        }

        /* Status Badges */
        .status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .status.pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status.approved {
            background: #dcfce7;
            color: #166534;
        }

        .status.rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .status.completed {
            background: #dbeafe;
            color: #1e40af;
        }

        /* Table Actions */
        .row-actions {
            display: flex;
            align-items: center;
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

        .action-btn.edit:hover {
            background: #dbeafe;
            border-color: #93c5fd;
            color: #2563eb;
        }

        .action-btn.delete:hover {
            background: #fee2e2;
            border-color: #fecaca;
            color: #dc2626;
        }

        /* Empty State */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 24px;
            text-align: center;
            display: none;
        }

        .empty-state-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #9ca3af;
            margin-bottom: 16px;
        }

        .empty-state h3 {
            font-size: 18px;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 8px;
        }

        .empty-state p {
            font-size: 14px;
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
            border-radius: 12px;
            max-width: 800px;
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
            padding: 20px 24px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
        }

        .modal-close {
            width: 36px;
            height: 36px;
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
            padding: 24px;
        }

        .modal-footer {
            padding: 20px 24px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
        }

        /* Lead Detail Modal */
        .lead-detail-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 24px;
        }

        .lead-avatar-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #3b82f6;
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
            margin-bottom: 4px;
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
            color: #3b82f6;
        }

        .detail-section {
            margin-bottom: 24px;
        }

        .detail-section h4 {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 16px;
            position: relative;
            padding-left: 16px;
        }

        .detail-section h4::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 20px;
            background: #3b82f6;
            border-radius: 2px;
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
            border-radius: 8px;
            padding: 16px;
            border: 1px solid #e5e7eb;
        }

        .remarks-box p {
            color: #4b5563;
            line-height: 1.6;
            margin: 0;
        }

        /* Delete Confirmation Modal */
        .confirm-message {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 16px;
        }

        .confirm-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #fee2e2;
            color: #dc2626;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .confirm-message p {
            color: #4b5563;
            font-size: 15px;
            line-height: 1.6;
        }

        /* Buttons */
        .btn-primary, .btn-secondary, .btn-danger {
            padding: 10px 16px;
            border-radius: 6px;
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

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
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

        /* Responsive Styles */
        @media (max-width: 1200px) {
            .leads-content-grid {
                grid-template-columns: 1fr;
            }

            .lead-form-container {
                order: 2;
            }

            .leads-table-container {
                order: 1;
                margin-bottom: 24px;
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

            .filter-section {
                width: 100%;
            }

            .filter-toggle {
                width: 100%;
                justify-content: space-between;
            }

            .filters-grid {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .lead-detail-grid {
                grid-template-columns: 1fr;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        // Sample data for leads
        const leadsData = [
            {
                id: 1,
                name: 'Rajesh Kumar',
                email: 'rajesh.kumar@email.com',
                phone: '+91 98765 43210',
                location: 'Mumbai, Maharashtra',
                company: 'TechCorp India',
                position: 'CTO',
                industry: 'IT',
                website: 'www.techcorp.com',
                amount: 1250000,
                status: 'pending',
                source: 'Website',
                expected_date: '2024-03-15',
                notes: 'Very interested in our premium package. Scheduled follow-up call for next week. Decision maker confirmed.',
                created_at: '2024-01-10',
                assigned: true,
                team_lead_assigned: false
            },
            {
                id: 2,
                name: 'Priya Sharma',
                email: 'priya.sharma@company.com',
                phone: '+91 87654 32109',
                location: 'Delhi, NCR',
                company: 'Digital Solutions',
                position: 'Marketing Director',
                industry: 'Marketing',
                website: 'www.digitalsolutions.in',
                amount: 725000,
                status: 'approved',
                source: 'Referral',
                expected_date: '2024-04-20',
                notes: 'Interested in digital marketing services. Needs more information about pricing.',
                created_at: '2024-01-15',
                assigned: true
            },
            {
                id: 3,
                name: 'Amit Patel',
                email: 'amit.patel@startup.com',
                phone: '+91 76543 21098',
                location: 'Ahmedabad, Gujarat',
                company: 'StartupXYZ',
                position: 'CEO',
                industry: 'Finance',
                website: 'www.startupxyz.co.in',
                amount: 2075000,
                status: 'completed',
                source: 'Event',
                expected_date: '2024-02-28',
                notes: 'Deal closed. Implementation starting next month.',
                created_at: '2023-12-05',
                assigned: true
            },
            {
                id: 4,
                name: 'Sneha Reddy',
                email: 'sneha.reddy@corp.com',
                phone: '+91 65432 10987',
                location: 'Bangalore, Karnataka',
                company: 'Global Corp',
                position: 'CFO',
                industry: 'Manufacturing',
                website: 'www.globalcorp.com',
                amount: 980000,
                status: 'rejected',
                source: 'Cold Call',
                expected_date: '2024-05-10',
                notes: 'Budget constraints. May revisit next quarter.',
                created_at: '2024-01-20',
                assigned: false
            },
            {
                id: 5,
                name: 'Vikram Singh',
                email: 'vikram.singh@edutech.com',
                phone: '+91 54321 09876',
                location: 'Pune, Maharashtra',
                company: 'EduTech Solutions',
                position: 'Director',
                industry: 'Education',
                website: 'www.edutech.co.in',
                amount: 1450000,
                status: 'pending',
                source: 'Email Campaign',
                expected_date: '2024-04-05',
                notes: 'Interested in our enterprise solution. Demo scheduled for next week.',
                created_at: '2024-01-25',
                assigned: false
            }
        ];

        // Global variables
        let currentLeadId = null;
        let filteredLeads = [...leadsData];
        let isEditing = false;

        // DOM Ready
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the page
            renderLeadsTable();
            updateResultsCount();
            
            // Add event listeners
            document.getElementById('leadForm').addEventListener('submit', saveLead);
        });

        // Toggle filters visibility
        function toggleFilters() {
            const filtersElement = document.getElementById('advancedFilters');
            filtersElement.classList.toggle('active');
            
            // Change the icon based on state
            const icon = document.querySelector('.filter-toggle i:last-child');
            if (filtersElement.classList.contains('active')) {
                icon.className = 'fas fa-chevron-up';
            } else {
                icon.className = 'fas fa-chevron-down';
            }
        }

        // Filter leads based on criteria
        function filterLeads() {
            const statusFilter = document.getElementById('statusFilter').value;
            const assignmentFilter = document.getElementById('assignmentFilter').value;
            const dateFromFilter = document.getElementById('dateFromFilter').value;
            const dateToFilter = document.getElementById('dateToFilter').value;
            const searchFilter = document.getElementById('searchFilter').value.toLowerCase();
            
            filteredLeads = leadsData.filter(lead => {
                // Status filter
                if (statusFilter && lead.status !== statusFilter) return false;
                
                // Assignment filter
                if (assignmentFilter === 'assigned' && !lead.assigned) return false;
                if (assignmentFilter === 'unassigned' && lead.assigned) return false;
                
                // Date range filter
                if (dateFromFilter) {
                    const fromDate = new Date(dateFromFilter);
                    const leadDate = new Date(lead.created_at);
                    if (leadDate < fromDate) return false;
                }
                
                if (dateToFilter) {
                    const toDate = new Date(dateToFilter);
                    const leadDate = new Date(lead.created_at);
                    if (leadDate > toDate) return false;
                }
                
                // Search filter
                if (searchFilter) {
                    const searchableFields = [
                        lead.name,
                        lead.email,
                        lead.company,
                        lead.location,
                        lead.phone
                    ].map(field => field ? field.toLowerCase() : '');
                    
                    return searchableFields.some(field => field.includes(searchFilter));
                }
                
                return true;
            });
            
            renderLeadsTable();
            updateResultsCount();
        }

        // Reset all filters
        function resetFilters() {
            document.getElementById('statusFilter').value = '';
            document.getElementById('assignmentFilter').value = '';
            document.getElementById('dateFromFilter').value = '';
            document.getElementById('dateToFilter').value = '';
            document.getElementById('searchFilter').value = '';
            
            filteredLeads = [...leadsData];
            renderLeadsTable();
            updateResultsCount();
        }

        // Render leads table
        function renderLeadsTable() {
            const tableBody = document.getElementById('leadsTableBody');
            const emptyState = document.getElementById('emptyState');
            const tableWrapper = document.querySelector('.table-wrapper');
            
            // Clear existing rows
            tableBody.innerHTML = '';
            
            if (filteredLeads.length === 0) {
                emptyState.style.display = 'flex';
                tableWrapper.style.display = 'none';
                return;
            }
            
            emptyState.style.display = 'none';
            tableWrapper.style.display = 'block';
            
            // Add rows for each lead
            filteredLeads.forEach(lead => {
                const row = document.createElement('tr');
                row.onclick = () => viewLeadDetails(lead.id);
                
                // Format amount with rupee symbol and commas
                const formattedAmount = new Intl.NumberFormat('en-IN', {
                    style: 'currency',
                    currency: 'INR',
                    maximumFractionDigits: 0
                }).format(lead.amount);
                
                // Format date
                const createdDate = new Date(lead.created_at);
                const formattedDate = createdDate.toLocaleDateString('en-IN', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
                
                // Get initials for avatar
                const initials = lead.name.split(' ').map(n => n[0]).join('');
                
                row.innerHTML = `
    <td>
        <div class="lead-info">
            <div class="lead-avatar">${initials}</div>
            <div class="lead-details">
                <div class="lead-name">${lead.name}</div>
                <div class="lead-email">${lead.email}</div>
            </div>
        </div>
    </td>
    <td>${lead.company || '-'}</td>
    <td>${formattedAmount}</td>
    <td><span class="status ${lead.status}">${lead.status.charAt(0).toUpperCase() + lead.status.slice(1)}</span></td>
    <td>
        ${lead.team_lead_assigned 
            ? '<span class="status approved">Assigned</span>' 
            : '<span class="status pending">Not Assigned</span>'}
    </td>
    <td>${formattedDate}</td>
    <td>
        <div class="row-actions">
            <button class="action-btn edit" onclick="editLead(${lead.id}); event.stopPropagation();" title="Edit">
                <i class="fas fa-edit"></i>
            </button>
            <button class="action-btn delete" onclick="deleteLead(${lead.id}); event.stopPropagation();" title="Delete">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </td>
`;
                
                tableBody.appendChild(row);
            });
        }

        // Update results count
        function updateResultsCount() {
            document.getElementById('resultsCount').textContent = filteredLeads.length;
        }

        // View lead details
        function viewLeadDetails(id) {
            const lead = leadsData.find(lead => lead.id === id);
            if (!lead) return;
            
            currentLeadId = id;
            
            // Format amount with rupee symbol and commas
            const formattedAmount = new Intl.NumberFormat('en-IN', {
                style: 'currency',
                currency: 'INR',
                maximumFractionDigits: 0
            }).format(lead.amount);
            
            // Format date
            const expectedDate = new Date(lead.expected_date);
            const formattedDate = expectedDate.toLocaleDateString('en-IN', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            
            // Get initials for avatar
            const initials = lead.name.split(' ').map(n => n[0]).join('');
            
            // Update modal content
            document.getElementById('modalLeadInitials').textContent = initials;
            document.getElementById('modalLeadName').textContent = lead.name;
            document.getElementById('modalLeadCompany').textContent = lead.company || '-';
            document.getElementById('modalLeadPhone').textContent = lead.phone;
            document.getElementById('modalLeadEmail').textContent = lead.email;
            document.getElementById('modalLeadLocation').textContent = lead.location || '-';
            document.getElementById('modalLeadWebsite').textContent = lead.website || '-';
            document.getElementById('modalLeadAmount').textContent = formattedAmount;
            document.getElementById('modalLeadIndustry').textContent = getIndustryName(lead.industry);
            document.getElementById('modalLeadPosition').textContent = lead.position || '-';
            document.getElementById('modalLeadSource').textContent = lead.source || '-';
            document.getElementById('modalLeadExpectedDate').textContent = formattedDate;
            
            const statusElement = document.getElementById('modalLeadStatus');
            statusElement.textContent = lead.status.charAt(0).toUpperCase() + lead.status.slice(1);
            statusElement.className = `status ${lead.status}`;
            
            document.getElementById('modalLeadNotes').textContent = lead.notes || 'No notes available';
            
            // Show modal
            const modal = document.getElementById('leadDetailModal');
            modal.classList.add('active');
        }

        // Edit lead
        function editLead(id) {
            const lead = leadsData.find(lead => lead.id === id);
            if (!lead) return;
            
            isEditing = true;
            currentLeadId = id;
            
            // Update form title
            document.getElementById('formTitle').textContent = 'Edit Lead';
            document.getElementById('formSubtitle').textContent = 'Update the lead information';
            document.getElementById('saveButton').innerHTML = '<i class="fas fa-save"></i> Update Lead';
            
            // Populate form fields
            document.getElementById('leadId').value = lead.id;
            document.getElementById('leadName').value = lead.name;
            document.getElementById('leadEmail').value = lead.email;
            document.getElementById('leadPhone').value = lead.phone;
            document.getElementById('leadLocation').value = lead.location || '';
            document.getElementById('leadCompany').value = lead.company || '';
            document.getElementById('leadPosition').value = lead.position || '';
            document.getElementById('leadIndustry').value = lead.industry || '';
            document.getElementById('leadWebsite').value = lead.website || '';
            document.getElementById('leadAmount').value = lead.amount;
            document.getElementById('leadStatus').value = lead.status;
            document.getElementById('leadSource').value = lead.source || '';
            document.getElementById('leadExpectedDate').value = lead.expected_date || '';
            document.getElementById('leadNotes').value = lead.notes || '';
            
            // Close detail modal if open
            closeModal('leadDetailModal');
            
            // Scroll to form
            document.querySelector('.lead-form-container').scrollIntoView({ behavior: 'smooth' });
        }

        // Delete lead
        function deleteLead(id) {
            currentLeadId = id;
            const modal = document.getElementById('deleteConfirmModal');
            modal.classList.add('active');
        }

        // Confirm delete
        function confirmDelete() {
            if (!currentLeadId) return;
            
            // Find index of lead to delete
            const index = leadsData.findIndex(lead => lead.id === currentLeadId);
            if (index !== -1) {
                // Remove from data
                leadsData.splice(index, 1);
                
                // Update filtered leads
                filteredLeads = filteredLeads.filter(lead => lead.id !== currentLeadId);
                
                // Re-render table
                renderLeadsTable();
                updateResultsCount();
                
                // Show notification
                showNotification('Lead deleted successfully', 'success');
            }
            
            // Close modal
            closeModal('deleteConfirmModal');
        }

        // Save or update lead
        function saveLead(event) {
            event.preventDefault();
            
            const leadId = document.getElementById('leadId').value;
            const leadData = {
                name: document.getElementById('leadName').value,
                email: document.getElementById('leadEmail').value,
                phone: document.getElementById('leadPhone').value,
                location: document.getElementById('leadLocation').value,
                company: document.getElementById('leadCompany').value,
                position: document.getElementById('leadPosition').value,
                industry: document.getElementById('leadIndustry').value,
                website: document.getElementById('leadWebsite').value,
                amount: parseFloat(document.getElementById('leadAmount').value) || 0,
                status: document.getElementById('leadStatus').value,
                source: document.getElementById('leadSource').value,
                expected_date: document.getElementById('leadExpectedDate').value,
                notes: document.getElementById('leadNotes').value,
                assigned: true // Default to assigned for new leads
            };
            
            if (isEditing) {
                // Update existing lead
                const index = leadsData.findIndex(lead => lead.id === parseInt(leadId));
                if (index !== -1) {
                    // Preserve created_at and id
                    leadData.id = parseInt(leadId);
                    leadData.created_at = leadsData[index].created_at;
                    
                    // Update data
                    leadsData[index] = leadData;
                    
                    // Show notification
                    showNotification('Lead updated successfully', 'success');
                }
            } else {
                // Add new lead
                leadData.id = leadsData.length > 0 ? Math.max(...leadsData.map(lead => lead.id)) + 1 : 1;
                leadData.created_at = new Date().toISOString().split('T')[0];
                
                // Add to data
                leadsData.push(leadData);
                
                // Show notification
                showNotification('Lead added successfully', 'success');
            }
            
            // Reset form and state
            resetForm();
            
            // Update filtered leads and table
            filterLeads();
        }

        // Reset form
        function resetForm() {
            document.getElementById('leadForm').reset();
            document.getElementById('leadId').value = '';
            
            // Reset form title
            document.getElementById('formTitle').textContent = 'Add New Lead';
            document.getElementById('formSubtitle').textContent = 'Fill in the details to create a new lead';
            document.getElementById('saveButton').innerHTML = '<i class="fas fa-save"></i> Save Lead';
            
            isEditing = false;
            currentLeadId = null;
        }

        // Refresh leads
        function refreshLeads() {
            // In a real app, this would fetch fresh data from the server
            // For demo, we'll just reset filters and re-render
            resetFilters();
            showNotification('Leads refreshed', 'info');
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
                border-radius: 6px;
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
                    container.removeChild(notification);
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

        // Helper function to get industry name
        function getIndustryName(code) {
            const industries = {
                'IT': 'Information Technology',
                'Finance': 'Finance',
                'Healthcare': 'Healthcare',
                'Education': 'Education',
                'Manufacturing': 'Manufacturing',
                'Retail': 'Retail',
                'Other': 'Other'
            };
            
            return industries[code] || code || '-';
        }

        function forwardToTeamLead(id) {
            const lead = leadsData.find(lead => lead.id === id);
            if (!lead) return;
            if (lead.team_lead_assigned) {
                showNotification('Already assigned to Team Lead.', 'info');
                return;
            }
            lead.team_lead_assigned = true;
            showNotification('Lead forwarded to Operations!', 'success');
            renderLeadsTable();
            closeModal('leadDetailModal');
        }
    </script>
</body>
</html>