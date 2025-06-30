<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Leads Management - Lead Management System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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

        .status.personal_lead {
            background: #fef3c7;
            color: #92400e;
        }

        .status.authorized {
            background: #dbeafe;
            color: #1e40af;
        }

        .status.approved {
            background: #dcfce7;
            color: #166534;
        }

        .status.rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .status.disbursed {
            background: #d1fae5;
            color: #065f46;
        }

        .status.future_lead {
            background: #fef9c3;
            color: #713f12;
        }

        .status.login {
            background: #e0e7ff;
            color: #3730a3;
        }

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
            .main-content {
                margin-left: 0;
            }

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
</head>
<body>
    @include('TeamLead.Components.sidebar')
    
    <div class="main-content">
        @include('TeamLead.Components.header', ['title' => 'Leads Management', 'subtitle' => 'Create, view, and manage your leads'])
        
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

            <!-- Advanced Filters -->
            <div class="advanced-filters" id="advancedFilters">
                <form id="filterForm">
                    <div class="filters-grid">
                        <div class="filter-group">
                            <label for="statusFilter">Status</label>
                            <select id="statusFilter" name="status" class="filter-select" onchange="filterLeads()">
                                <option value="">All Statuses</option>
                                <option value="personal_lead" {{ request('status') == 'personal_lead' ? 'selected' : '' }}>Personal Lead</option>
                                <option value="authorized" {{ request('status') == 'authorized' ? 'selected' : '' }}>Authorized</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="disbursed" {{ request('status') == 'disbursed' ? 'selected' : '' }}>Disbursed</option>
                                <option value="future_lead" {{ request('status') == 'future_lead' ? 'selected' : '' }}>Future Lead</option>
                                <option value="login" {{ request('status') == 'login' ? 'selected' : '' }}>Login</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="assignmentFilter">Assignment</label>
                            <select id="assignmentFilter" name="assignment" class="filter-select" onchange="filterLeads()">
                                <option value="">All</option>
                                <option value="assigned" {{ request('assignment') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                <option value="unassigned" {{ request('assignment') == 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="dateFromFilter">Date From</label>
                            <input type="date" id="dateFromFilter" name="date_from" class="filter-input" value="{{ request('date_from') }}" onchange="filterLeads()">
                        </div>
                        <div class="filter-group">
                            <label for="dateToFilter">Date To</label>
                            <input type="date" id="dateToFilter" name="date_to" class="filter-input" value="{{ request('date_to') }}" onchange="filterLeads()">
                        </div>
                        <div class="filter-group">
                            <label for="searchFilter">Search</label>
                            <div class="search-input-wrapper">
                                <input type="text" id="searchFilter" name="search" class="filter-input" placeholder="Search by name, company..." value="{{ request('search') }}" oninput="filterLeads()">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                        <div class="filter-actions">
                            <button type="button" class="btn-reset" onclick="resetFilters()">
                                <i class="fas fa-undo"></i>
                                Reset
                            </button>
                        </div>
                    </div>
                </form>
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
                        <input type="hidden" id="leadId" name="id">
                        
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
                                    <label for="leadEmail">Email Address</label>
                                    <input type="email" id="leadEmail" name="email" class="form-control">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="leadPhone">Phone Number <span class="required">*</span></label>
                                    <input type="tel" id="leadPhone" name="phone" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="leadDob">Date of Birth</label>
                                    <input type="date" id="leadDob" name="dob" class="form-control">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="leadCity">City</label>
                                    <input type="text" id="leadCity" name="city" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="leadDistrict">District</label>
                                    <input type="text" id="leadDistrict" name="district" class="form-control">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="leadState">State</label>
                                    <input type="text" id="leadState" name="state" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="leadTeamLead">Team Lead</label>
                                    <select id="leadTeamLead" name="team_lead_id" class="form-control">
                                        {{-- <option value="">Select Team Lead</option>
                                        @foreach($teamLeads as $teamLead)
                                            <option value="{{ $teamLead->id }}">{{ $teamLead->name }}</option>
                                        @endforeach --}}
                                    </select>
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
                                    <input type="text" id="leadCompany" name="company_name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="leadBankName">Bank Name</label>
                                    <input type="text" id="leadBankName" name="bank_name" class="form-control">
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
                                    <label for="leadAmount">Lead Amount (₹) <span class="required">*</span></label>
                                    <input type="number" id="leadAmount" name="lead_amount" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="leadSalary">Salary</label>
                                    <input type="number" id="leadSalary" name="salary" class="form-control">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="leadSuccessPercentage">Success Percentage <span class="required">*</span></label>
                                    <input type="number" id="leadSuccessPercentage" name="success_percentage" class="form-control" min="0" max="100" required>
                                </div>
                                <div class="form-group">
                                    <label for="leadExpectedMonth">Expected Month</label>
                                    <input type="month" id="leadExpectedMonth" name="expected_month" class="form-control">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="leadType">Lead Type</label>
                                    <input type="text" id="leadType" name="lead_type" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="leadTurnoverAmount">Turnover Amount</label>
                                    <input type="number" id="leadTurnoverAmount" name="turnover_amount" class="form-control">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="leadVintageYear">Vintage Year</label>
                                    <input type="number" id="leadVintageYear" name="vintage_year" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="leadVoiceRecording">Voice Recording URL</label>
                                    <input type="url" id="leadVoiceRecording" name="voice_recording" class="form-control">
                                </div>
                            </div>
                            <div class="form-group full-width">
                                <label for="leadNotes">Notes</label>
                                <textarea id="leadNotes" name="remarks" class="form-control" rows="3"></textarea>
                            </div>
                            <input type="hidden" id="leadStatus" name="status" value="personal_lead">
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
                        <h2>All Leads</h2>
                        <div class="table-actions">
                            <div class="results-info">
                                Showing <span id="resultsCount">{{ $leads->total() }}</span> leads
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
                                    <th>Employee</th>
                                    <th>Company</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Team Lead</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="leadsTableBody">
                                @foreach($formattedLeads as $lead)
                                    <tr onclick="viewLeadDetails({{ $lead['id'] }})">
                                        <td>
                                            <div class="lead-info">
                                                <div class="lead-avatar">{{ substr($lead['name'], 0, 1) }}</div>
                                                <div class="lead-details">
                                                    <div class="lead-name">{{ $lead['name'] }}</div>
                                                    <div class="lead-email">{{ $lead['email'] }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $lead['employee_name'] }}</td>
                                        <td>{{ $lead['company'] }}</td>
                                        <td>₹{{ $lead['amount'] }}</td>
                                        <td><span class="status {{ $lead['status'] }}">{{ str_replace('_', ' ', ucfirst($lead['status'])) }}</span></td>
                                        <td>
                                            <span class="status {{ $lead['team_lead_assigned'] ? 'approved' : 'personal_lead' }}">
                                                {{ $lead['team_lead_assigned'] ? 'Assigned' : 'Not Assigned' }}
                                            </span>
                                        </td>
                                        <td>{{ $lead['created_at'] }}</td>
                                        <td>
                                            <div class="row-actions">
                                                <button class="action-btn edit" onclick="editLead({{ $lead['id'] }}); event.stopPropagation();" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="action-btn delete" onclick="deleteLead({{ $lead['id'] }}); event.stopPropagation();" title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="emptyState" class="empty-state" style="display: {{ $leads->isEmpty() ? 'flex' : 'none' }};">
                        <div class="empty-state-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>No leads found</h3>
                        <p>Try adjusting your filters or add a new lead</p>
                    </div>
                    <div class="pagination" id="paginationContainer">
                        {{ $leads->links() }}
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
                        <div class="lead-avatar-large" id="modalLeadInitials"></div>
                        <div class="lead-basic-info">
                            <h3 id="modalLeadName"></h3>
                            <p id="modalLeadCompany"></p>
                            <div class="lead-contact">
                                <div class="contact-item">
                                    <i class="fas fa-phone"></i>
                                    <span id="modalLeadPhone"></span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-envelope"></i>
                                    <span id="modalLeadEmail"></span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span id="modalLeadLocation"></span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-university"></i>
                                    <span id="modalLeadBankName"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lead-detail-right">
                        <div class="detail-section">
                            <h4>Lead Information</h4>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <label>Lead Amount</label>
                                    <span id="modalLeadAmount"></span>
                                </div>
                                <div class="detail-item">
                                    <label>Status</label>
                                    <span id="modalLeadStatus" class="status"></span>
                                </div>
                                <div class="detail-item">
                                    <label>Success Percentage</label>
                                    <span id="modalLeadSuccessPercentage"></span>
                                </div>
                                <div class="detail-item">
                                    <label>Expected Month</label>
                                    <span id="modalLeadExpectedMonth"></span>
                                </div>
                                <div class="detail-item">
                                    <label>Lead Type</label>
                                    <span id="modalLeadType"></span>
                                </div>
                                <div class="detail-item">
                                    <label>Turnover Amount</label>
                                    <span id="modalLeadTurnoverAmount"></span>
                                </div>
                                <div class="detail-item">
                                    <label>Vintage Year</label>
                                    <span id="modalLeadVintageYear"></span>
                                </div>
                                <div class="detail-item">
                                    <label>Voice Recording</label>
                                    <span id="modalLeadVoiceRecording"></span>
                                </div>
                            </div>
                        </div>
                        <div class="detail-section">
                            <h4>Employee Information</h4>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <label>Created By</label>
                                    <span id="modalLeadEmployeeName"></span>
                                </div>
                                <div class="detail-item">
                                    <label>Team Lead</label>
                                    <span id="modalLeadTeamLeadName"></span>
                                </div>
                            </div>
                        </div>
                        <div class="detail-section">
                            <h4>Notes</h4>
                            <div class="remarks-box">
                                <p id="modalLeadNotes"></p>
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
                @if(auth()->user()->hasDesignation('team_lead'))
                    <button class="btn-primary" onclick="forwardToTeamLead(currentLeadId)">
                        <i class="fas fa-share"></i>
                        Forward to Team Lead
                    </button>
                    <button class="btn-primary" onclick="setAuthorized(currentLeadId)">
                        <i class="fas fa-check-circle"></i>
                        Authorize
                    </button>
                    <button class="btn-primary" onclick="setFutureLead(currentLeadId)">
                        <i class="fas fa-clock"></i>
                        Mark as Future Lead
                    </button>
                    <button class="btn-primary" onclick="approveLead(currentLeadId, false)">
                        <i class="fas fa-check"></i>
                        Approve
                    </button>
                    <button class="btn-primary" onclick="approveLead(currentLeadId, true)">
                        <i class="fas fa-share"></i>
                        Approve & Forward to Operations
                    </button>
                    <button class="btn-danger" onclick="rejectLead(currentLeadId)">
                        <i class="fas fa-times-circle"></i>
                        Reject
                    </button>
                @endif
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

    <script>
        // Initial leads data from server
        let leadsData = @json($formattedLeads);
        let filteredLeads = [...leadsData];
        let currentLeadId = null;
        let isEditing = false;

        // DOM Ready
        document.addEventListener('DOMContentLoaded', function() {
            renderLeadsTable();
            updateResultsCount();
            document.getElementById('leadForm').addEventListener('submit', saveLead);
        });

        // Toggle filters visibility
        function toggleFilters() {
            const filtersElement = document.getElementById('advancedFilters');
            filtersElement.classList.toggle('active');
            const icon = document.querySelector('.filter-toggle i:last-child');
            icon.className = filtersElement.classList.contains('active') ? 'fas fa-chevron-up' : 'fas fa-chevron-down';
        }

        // Filter leads
        function filterLeads() {
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            const query = new URLSearchParams(formData).toString();
            window.location.href = `{{ route('team_lead.leads.index') }}?${query}`;
        }

        // Reset filters
        function resetFilters() {
            window.location.href = '{{ route('team_lead.leads.index') }}';
        }

        // Render leads table
        function renderLeadsTable() {
            const tableBody = document.getElementById('leadsTableBody');
            const emptyState = document.getElementById('emptyState');
            const tableWrapper = document.querySelector('.table-wrapper');
            
            if (filteredLeads.length === 0) {
                emptyState.style.display = 'flex';
                tableWrapper.style.display = 'none';
                return;
            }
            
            emptyState.style.display = 'none';
            tableWrapper.style.display = 'block';
            
            tableBody.innerHTML = '';
            filteredLeads.forEach(lead => {
                const row = document.createElement('tr');
                row.onclick = () => viewLeadDetails(lead.id);
                
                const formattedAmount = new Intl.NumberFormat('en-IN', {
                    style: 'currency',
                    currency: 'INR',
                    maximumFractionDigits: 0
                }).format(lead.amount);
                
                const initials = lead.name.split(' ').map(n => n[0]).join('');
                
                row.innerHTML = `
                    <td>
                        <div class="lead-info">
                            <div class="lead-avatar">${initials}</div>
                            <div class="lead-details">
                                <div class="lead-name">${lead.name}</div>
                                <div class="lead-email">${lead.email || '-'}</div>
                            </div>
                        </div>
                    </td>
                    <td>${lead.employee_name || '-'}</td>
                    <td>${lead.company || '-'}</td>
                    <td>${formattedAmount}</td>
                    <td><span class="status ${lead.status}">${lead.status.replace('_', ' ').split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')}</span></td>
                    <td>
                        <span class="status ${lead.team_lead_assigned ? 'approved' : 'personal_lead'}">
                            ${lead.team_lead_assigned ? 'Assigned' : 'Not Assigned'}
                        </span>
                    </td>
                    <td>${lead.created_at}</td>
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
            
            const formattedAmount = new Intl.NumberFormat('en-IN', {
                style: 'currency',
                currency: 'INR',
                maximumFractionDigits: 0
            }).format(lead.amount);
            
            const formattedTurnover = lead.turnover_amount ? new Intl.NumberFormat('en-IN', {
                style: 'currency',
                currency: 'INR',
                maximumFractionDigits: 0
            }).format(lead.turnover_amount) : '-';
            
            const initials = lead.name.split(' ').map(n => n[0]).join('');
            
            document.getElementById('modalLeadInitials').textContent = initials;
            document.getElementById('modalLeadName').textContent = lead.name;
            document.getElementById('modalLeadCompany').textContent = lead.company || '-';
            document.getElementById('modalLeadPhone').textContent = lead.phone;
            document.getElementById('modalLeadEmail').textContent = lead.email || '-';
            document.getElementById('modalLeadLocation').textContent = lead.city ? `${lead.city}, ${lead.state || ''}` : (lead.state || '-');
            document.getElementById('modalLeadBankName').textContent = lead.bank_name || '-';
            document.getElementById('modalLeadAmount').textContent = formattedAmount;
            document.getElementById('modalLeadStatus').textContent = lead.status.replace('_', ' ').split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
            document.getElementById('modalLeadStatus').className = `status ${lead.status}`;
            document.getElementById('modalLeadSuccessPercentage').textContent = lead.success_percentage ? `${lead.success_percentage}%` : '-';
            document.getElementById('modalLeadExpectedMonth').textContent = lead.expected_date || '-';
            document.getElementById('modalLeadType').textContent = lead.lead_type || '-';
            document.getElementById('modalLeadTurnoverAmount').textContent = formattedTurnover;
            document.getElementById('modalLeadVintageYear').textContent = lead.vintage_year || '-';
            document.getElementById('modalLeadVoiceRecording').textContent = lead.voice_recording || '-';
            document.getElementById('modalLeadEmployeeName').textContent = lead.employee_name || '-';
            document.getElementById('modalLeadTeamLeadName').textContent = lead.team_lead_name || '-';
            document.getElementById('modalLeadNotes').textContent = lead.notes || 'No notes available';
            
            document.getElementById('leadDetailModal').classList.add('active');
        }

        // Edit lead
        function editLead(id) {
            const lead = leadsData.find(lead => lead.id === id);
            if (!lead) return;
            
            isEditing = true;
            currentLeadId = id;
            
            document.getElementById('formTitle').textContent = 'Edit Lead';
            document.getElementById('formSubtitle').textContent = 'Update the lead information';
            document.getElementById('saveButton').innerHTML = '<i class="fas fa-save"></i> Update Lead';
            
            document.getElementById('leadId').value = lead.id;
            document.getElementById('leadName').value = lead.name;
            document.getElementById('leadEmail').value = lead.email || '';
            document.getElementById('leadPhone').value = lead.phone;
            document.getElementById('leadDob').value = lead.dob || '';
            document.getElementById('leadCity').value = lead.city || '';
            document.getElementById('leadDistrict').value = lead.district || '';
            document.getElementById('leadState').value = lead.state || '';
            document.getElementById('leadCompany').value = lead.company || '';
            document.getElementById('leadBankName').value = lead.bank_name || '';
            document.getElementById('leadAmount').value = lead.amount;
            document.getElementById('leadSalary').value = lead.salary || '';
            document.getElementById('leadSuccessPercentage').value = lead.success_percentage || '';
            document.getElementById('leadExpectedMonth').value = lead.expected_month || '';
            document.getElementById('leadType').value = lead.lead_type || '';
            document.getElementById('leadTurnoverAmount').value = lead.turnover_amount || '';
            document.getElementById('leadVintageYear').value = lead.vintage_year || '';
            document.getElementById('leadVoiceRecording').value = lead.voice_recording || '';
            document.getElementById('leadNotes').value = lead.notes || '';
            document.getElementById('leadTeamLead').value = lead.team_lead_id || '';
            
            closeModal('leadDetailModal');
            document.querySelector('.lead-form-container').scrollIntoView({ behavior: 'smooth' });
        }

        // Delete lead
        function deleteLead(id) {
            currentLeadId = id;
            document.getElementById('deleteConfirmModal').classList.add('active');
        }

        // Confirm delete
        function confirmDelete() {
            if (!currentLeadId) return;
            
            fetch(`/team-lead/leads/${currentLeadId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showNotification(data.message, 'success');
                    leadsData = leadsData.filter(lead => lead.id !== currentLeadId);
                    filteredLeads = filteredLeads.filter(lead => lead.id !== currentLeadId);
                    renderLeadsTable();
                    updateResultsCount();
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                showNotification('Error deleting lead', 'error');
                console.error(error);
            });
            
            closeModal('deleteConfirmModal');
        }

        // Save or update lead
        function saveLead(event) {
            event.preventDefault();
            
            const form = document.getElementById('leadForm');
            const formData = new FormData(form);
            const leadData = Object.fromEntries(formData);
            
            const url = isEditing ? `/team-lead/leads/${leadData.id}` : '';
            const method = isEditing ? 'PUT' : 'POST';
            
            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(leadData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showNotification(data.message, 'success');
                    fetchLeads();
                    resetForm();
                } else {
                    showNotification(data.message || 'Error saving lead', 'error');
                }
            })
            .catch(error => {
                showNotification('Error saving lead', 'error');
                console.error(error);
            });
        }

        // Forward to team lead
        function forwardToTeamLead(id) {
            const lead = leadsData.find(lead => lead.id === id);
            if (!lead) return;
            
            const teamLeadId = prompt('Enter Team Lead ID:');
            const remarks = prompt('Enter remarks (optional):');
            
            if (!teamLeadId) {
                showNotification('Team Lead ID is required.', 'error');
                return;
            }
            
            fetch(`/team-lead/leads/${id}/forward`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    team_lead_id: teamLeadId,
                    remarks: remarks || undefined
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showNotification(data.message, 'success');
                    lead.team_lead_assigned = true;
                    lead.team_lead_id = teamLeadId;
                    if (remarks) lead.notes = remarks;
                    fetchLeads();
                    closeModal('leadDetailModal');
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                showNotification('Error forwarding lead', 'error');
                console.error(error);
            });
        }

        // Set authorized
        function setAuthorized(id) {
            const lead = leadsData.find(lead => lead.id === id);
            if (!lead) return;
            
            const remarks = prompt('Enter remarks (optional):');
            
            fetch(`/team-lead/leads/${id}/authorize`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    remarks: remarks || undefined
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showNotification(data.message, 'success');
                    lead.status = 'authorized';
                    if (remarks) lead.notes = remarks;
                    fetchLeads();
                    closeModal('leadDetailModal');
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                showNotification('Error authorizing lead', 'error');
                console.error(error);
            });
        }

        // Set future lead
        function setFutureLead(id) {
            const lead = leadsData.find(lead => lead.id === id);
            if (!lead) return;
            
            const remarks = prompt('Enter remarks (optional):');
            const expectedMonth = prompt('Enter expected month (YYYY-MM, optional):');
            
            fetch(`/team-lead/leads/${id}/future`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    remarks: remarks || undefined,
                    expected_month: expectedMonth || undefined
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showNotification(data.message, 'success');
                    lead.status = 'future_lead';
                    if (remarks) lead.notes = remarks;
                    if (expectedMonth) lead.expected_month = expectedMonth;
                    fetchLeads();
                    closeModal('leadDetailModal');
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                showNotification('Error updating lead', 'error');
                console.error(error);
            });
        }

        // Approve lead
        function approveLead(id, forwardToOperations) {
            const lead = leadsData.find(lead => lead.id === id);
            if (!lead) return;
            
            const remarks = prompt('Enter remarks (optional):');
            
            fetch(`/team-lead/leads/${id}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    remarks: remarks || undefined,
                    forward_to_operations: forwardToOperations
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showNotification(data.message, 'success');
                    lead.status = forwardToOperations ? 'login' : 'approved';
                    if (remarks) lead.notes = remarks;
                    fetchLeads();
                    closeModal('leadDetailModal');
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                showNotification('Error approving lead', 'error');
                console.error(error);
            });
        }

        // Reject lead
        function rejectLead(id) {
            const lead = leadsData.find(lead => lead.id === id);
            if (!lead) return;
            
            const remarks = prompt('Enter remarks (optional):');
            
            fetch(`/team-lead/leads/${id}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    remarks: remarks || undefined
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showNotification(data.message, 'success');
                    lead.status = 'rejected';
                    if (remarks) lead.notes = remarks;
                    fetchLeads();
                    closeModal('leadDetailModal');
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                showNotification('Error rejecting lead', 'error');
                console.error(error);
            });
        }

        // Fetch updated leads
        function fetchLeads() {
            fetch('{{ route('team_lead.leads.index') }}?' + new URLSearchParams({
                status: document.getElementById('statusFilter').value,
                assignment: document.getElementById('assignmentFilter').value,
                date_from: document.getElementById('dateFromFilter').value,
                date_to: document.getElementById('dateToFilter').value,
                search: document.getElementById('searchFilter').value
            }))
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTableBody = doc.querySelector('#leadsTableBody').innerHTML;
                const newResultsCount = doc.querySelector('#resultsCount').textContent;
                const newEmptyState = doc.querySelector('#emptyState').style.display;
                
                document.getElementById('leadsTableBody').innerHTML = newTableBody;
                document.getElementById('resultsCount').textContent = newResultsCount;
                document.querySelector('.table-wrapper').style.display = newEmptyState === 'flex' ? 'none' : 'block';
                document.getElementById('emptyState').style.display = newEmptyState;
                
                // Update leadsData
                fetch('{{ route('team_lead.leads.index') }}?data_only=true')
                    .then(response => response.json())
                    .then(data => {
                        leadsData = data.formattedLeads;
                        filteredLeads = [...leadsData];
                        renderLeadsTable();
                        updateResultsCount();
                    });
            })
            .catch(error => {
                showNotification('Error refreshing leads', 'error');
                console.error(error);
            });
        }

        // Reset form
        function resetForm() {
            document.getElementById('leadForm').reset();
            document.getElementById('leadId').value = '';
            document.getElementById('formTitle').textContent = 'Add New Lead';
            document.getElementById('formSubtitle').textContent = 'Fill in the details to create a new lead';
            document.getElementById('saveButton').innerHTML = '<i class="fas fa-save"></i> Save Lead';
            isEditing = false;
            currentLeadId = null;
        }

        // Refresh leads
        function refreshLeads() {
            fetchLeads();
            showNotification('Leads refreshed', 'info');
        }

        // Close modal
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        // Show notification
        function showNotification(message, type = 'info') {
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
            
            container.appendChild(notification);
            
            notification.querySelector('button').addEventListener('click', () => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    if (notification.parentNode) container.removeChild(notification);
                }, 300);
            });
            
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.style.opacity = '0';
                    setTimeout(() => {
                        if (notification.parentNode) container.removeChild(notification);
                    }, 300);
                }
            }, 5000);
        }
    </script>
</body>
</html>