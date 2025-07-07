<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="lead-report-url" content="{{ route('team_lead.report') }}">

    <title>Lead Reports - Lead Management System</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.jpg') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
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
            background: linear-gradient(90deg, #6366f1, #4f46e5);
        }

        .stat-card.blue::before {
            background: linear-gradient(90deg, #3b82f6, #1d4ed8);
        }

        .stat-card.green::before {
            background: linear-gradient(90deg, #10b981, #059669);
        }

        .stat-card.red::before {
            background: linear-gradient(90deg, #ef4444, #dc2626);
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
            background: linear-gradient(135deg, #6366f1, #4f46e5);
        }

        .stat-card.blue .stat-icon {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .stat-card.green .stat-icon {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .stat-card.red .stat-icon {
            background: linear-gradient(135deg, #ef4444, #dc2626);
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
            grid-template-columns: 1fr 1fr;
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
            background: #6366f1;
            border-color: #6366f1;
            color: white;
        }

        .chart-container {
            height: 300px;
            position: relative;
        }

        /* Filter Section */
        .filters-section {
            background: white;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 32px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid #f3f4f6;
            animation: fadeInDown 0.6s ease-out;
        }

        .filters-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .filters-title {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
        }

        .filters-toggle {
            background: none;
            border: none;
            color: #6366f1;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filters-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .filter-label {
            font-size: 14px;
            font-weight: 600;
            color: #4b5563;
        }

        .filter-input,
        .filter-select {
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            color: #1f2937;
            background: #f9fafb;
            transition: all 0.2s ease;
        }

        .filter-input:focus,
        .filter-select:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            background: white;
        }

        .filter-actions {
            display: flex;
            align-items: flex-end;
            gap: 12px;
        }

        .btn-filter {
            padding: 10px 16px;
            background: #6366f1;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-filter:hover {
            background: #4f46e5;
        }

        .btn-reset {
            padding: 10px 16px;
            background: #f3f4f6;
            color: #4b5563;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-reset:hover {
            background: #e5e7eb;
        }

        /* Team Performance Section */
        .team-performance-section {
            margin-bottom: 32px;
            animation: slideInRight 0.8s ease-out;
        }

        .team-performance-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .team-performance-title {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
        }

        .team-performance-tabs {
            display: flex;
            background: #f3f4f6;
            border-radius: 8px;
            padding: 4px;
        }

        .team-tab {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            color: #4b5563;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .team-tab.active {
            background: #6366f1;
            color: white;
        }

        .team-performance-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .team-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid #f3f4f6;
            transition: all 0.3s ease;
        }

        .team-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .team-card-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
        }

        .team-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 700;
            color: #6366f1;
        }

        .team-info {
            flex: 1;
        }

        .team-name {
            font-size: 16px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .team-role {
            font-size: 14px;
            color: #6b7280;
        }

        .team-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
        }

        .team-stat {
            background: #f9fafb;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
        }

        .team-stat-value {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .team-stat-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 500;
        }

        .team-progress {
            margin-bottom: 12px;
        }

        .progress-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .progress-label {
            font-size: 14px;
            font-weight: 600;
            color: #4b5563;
        }

        .progress-value {
            font-size: 14px;
            font-weight: 700;
            color: #1f2937;
        }

        .progress-bar {
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #6366f1, #4f46e5);
            border-radius: 4px;
            transition: width 0.5s ease;
        }

        /* Leads Table Section */
        .leads-table-section {
            background: white;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid #f3f4f6;
            margin-bottom: 32px;
            animation: slideUp 0.6s ease-out;
        }

        .leads-table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .leads-table-title {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
        }

        .leads-table-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .leads-search {
            position: relative;
        }

        .leads-search input {
            padding: 10px 16px 10px 40px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            color: #1f2937;
            background: #f9fafb;
            width: 240px;
            transition: all 0.2s ease;
        }

        .leads-search input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            background: white;
        }

        .leads-search i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .table-container {
            overflow-x: auto;
        }

        .leads-table {
            width: 100%;
            border-collapse: collapse;
        }

        .leads-table th {
            padding: 12px 16px;
            text-align: left;
            font-size: 14px;
            font-weight: 600;
            color: #4b5563;
            border-bottom: 1px solid #e5e7eb;
            white-space: nowrap;
        }

        .leads-table td {
            padding: 12px 16px;
            font-size: 14px;
            color: #1f2937;
            border-bottom: 1px solid #e5e7eb;
            white-space: nowrap;
        }

        .leads-table tr:hover td {
            background: #f9fafb;
        }

        .lead-status {
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .status-new {
            background: #e0f2fe;
            color: #0284c7;
        }

        .status-contacted {
            background: #fef3c7;
            color: #d97706;
        }

        .status-qualified {
            background: #dcfce7;
            color: #16a34a;
        }

        .status-converted {
            background: #dbeafe;
            color: #2563eb;
        }

        .status-closed {
            background: #fee2e2;
            color: #dc2626;
        }

        .lead-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f3f4f6;
            color: #4b5563;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-icon:hover {
            background: #e5e7eb;
            color: #1f2937;
        }

        .btn-view {
            background: #ede9fe;
            color: #6366f1;
        }

        .btn-view:hover {
            background: #ddd6fe;
            color: #4f46e5;
        }

        .pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 20px;
        }

        .pagination-info {
            font-size: 14px;
            color: #6b7280;
        }

        .pagination-controls {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pagination-button {
            width: 36px;
            height: 36px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f3f4f6;
            color: #4b5563;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .pagination-button:hover {
            background: #e5e7eb;
            color: #1f2937;
        }

        .pagination-button.active {
            background: #6366f1;
            color: white;
        }

        .pagination-button.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .chart-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    padding: 10px;
    font-size: 0.9rem;
    justify-content: center;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 5px;
}

.legend-color {
    width: 12px;
    height: 12px;
    display: inline-block;
    border-radius: 2px;
}

.team-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    padding: 20px;
    transition: 0.3s ease;
}

.team-card:hover {
    transform: translateY(-4px);
}

.team-card-header {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.team-avatar {
    background-color: #007bff;
    color: white;
    width: 50px;
    height: 50px;
    font-weight: bold;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin-right: 15px;
}

.team-info .team-name {
    font-size: 18px;
    font-weight: 600;
}

.team-info .team-role {
    font-size: 14px;
    color: #888;
}

.team-stats {
    display: flex;
    justify-content: space-between;
    margin: 15px 0;
}

.team-stat-value {
    font-size: 20px;
    font-weight: 600;
}

.team-progress .progress-bar {
    background-color: #f1f1f1;
    border-radius: 10px;
    height: 10px;
    overflow: hidden;
}

.team-progress .progress-fill {
    background-color: #28a745;
    height: 100%;
    width: 0;
    border-radius: 10px;
}
.filter-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
    margin-left:200px;
    align-items: center;
    flex-wrap: wrap; /* handles mobile wrapping */
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
        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }

            .dashboard-container {
                padding: 20px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .filters-content {
                grid-template-columns: 1fr;
            }

            .team-performance-grid {
                grid-template-columns: 1fr;
            }

            .leads-table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .leads-table-actions {
                width: 100%;
            }

            .leads-search input {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    @include('TeamLead.Components.sidebar')

    <div class="main-content">
        @include('TeamLead.Components.header', ['title' => 'Lead Reports', 'subtitle' => 'Analyze lead performance and conversion metrics'])

        <div class="dashboard-container">
            <!-- Stats Overview -->
           <div class="stats-grid">
    <!-- Total Leads -->
    <div class="stat-card blue">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-user-friends"></i></div>
        </div>
        <div class="stat-value">{{ $stats['total_leads'] }}</div>
        <div class="stat-label">Total Leads</div>
    </div>

    <!-- Total Lead Value -->
    <div class="stat-card red">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-rupee-sign"></i></div>
        </div>
        <div class="stat-value">₹{{ number_format($stats['total_lead_value']) }}</div>
        <div class="stat-label">Total Lead Value</div>
    </div>

    <!-- Authorized Leads -->
    <div class="stat-card green">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        </div>
        <div class="stat-value">{{ $stats['authorized_leads'] }}</div>
        <div class="stat-label">Authorized Leads</div>
    </div>

    <!-- Authorized Lead Value -->
    <div class="stat-card green">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
        </div>
        <div class="stat-value">₹{{ number_format($stats['authorized_lead_value']) }}</div>
        <div class="stat-label">Authorized Lead Value</div>
    </div>

    <!-- Login Leads -->
    <div class="stat-card purple">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-sign-in-alt"></i></div>
        </div>
        <div class="stat-value">{{ $stats['login_leads'] }}</div>
        <div class="stat-label">Login Leads</div>
    </div>

    <!-- Login Lead Value -->
    <div class="stat-card purple">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-wallet"></i></div>
        </div>
        <div class="stat-value">₹{{ number_format($stats['login_lead_value']) }}</div>
        <div class="stat-label">Login Lead Value</div>
    </div>

    <!-- Approved Leads -->
    <div class="stat-card teal">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-thumbs-up"></i></div>
        </div>
        <div class="stat-value">{{ $stats['approved_leads'] }}</div>
        <div class="stat-label">Approved Leads</div>
    </div>

    <!-- Approved Lead Value -->
    <div class="stat-card teal">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-coins"></i></div>
        </div>
        <div class="stat-value">₹{{ number_format($stats['approved_lead_value']) }}</div>
        <div class="stat-label">Approved Lead Value</div>
    </div>

    <!-- Disbursed Leads -->
    <div class="stat-card yellow">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-hand-holding-usd"></i></div>
        </div>
        <div class="stat-value">{{ $stats['disbursed_leads'] }}</div>
        <div class="stat-label">Disbursed Leads</div>
    </div>

    <!-- Disbursed Lead Value -->
    <div class="stat-card yellow">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-piggy-bank"></i></div>
        </div>
        <div class="stat-value">₹{{ number_format($stats['disbursed_lead_value']) }}</div>
        <div class="stat-label">Disbursed Lead Value</div>
    </div>

    <!-- Rejected Leads -->
    <div class="stat-card dark">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
        </div>
        <div class="stat-value">{{ $stats['rejected_leads'] }}</div>
        <div class="stat-label">Rejected Leads</div>
    </div>

    <!-- Rejected Lead Value -->
    <div class="stat-card dark">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-ban"></i></div>
        </div>
        <div class="stat-value">₹{{ number_format($stats['rejected_lead_value']) }}</div>
        <div class="stat-label">Rejected Lead Value</div>
    </div>

    <!-- Deleted (Soft-Deleted) Employees -->
    <div class="stat-card grey">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-user"></i></div>
        </div>
        <div class="stat-value">{{ $stats['active_employees'] }}</div>
        <div class="stat-label">Active Employees</div>
    </div>
</div>


        <!-- Filters Section -->
<form method="GET" action="{{ route('team_lead.report') }}" id="filterForm">

<div class="filters-section">
    <div class="filters-header">
        <h3 class="filters-title">Filter Reports</h3>
        <button class="filters-toggle" type="button" id="toggleFilters">
            <span>Advanced Filters</span>
            <i class="fas fa-chevron-down"></i>
        </button>
    </div>

    <div class="filters-content" id="filtersContent">
        {{-- Date Range --}}
        <div class="filter-group">
            <label class="filter-label">Date Range</label>
            <select class="filter-select" id="dateRangeFilter" name="date_range">
                <option value="7">Last 7 Days</option>
                <option value="30" selected>Last 30 Days</option>
                <option value="90">Last 90 Days</option>
                <option value="180">Last 6 Months</option>
                <option value="365">Last Year</option>
                <option value="custom">Custom Range</option>
            </select>
        </div>

        {{-- Team Lead --}}
        <div class="filter-group">
            <label class="filter-label">Team Lead</label>
            <select class="filter-select" id="teamLeadFilter" name="team_lead_id">
                <option value="">All Team Leads</option>
                @foreach ($teamLeads as $lead)
                    <option value="{{ $lead->id }}">{{ $lead->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Employee --}}
        <div class="filter-group">
            <label class="filter-label">Employee</label>
            <select class="filter-select" id="employeeFilter" name="employee_id">
                <option value="">All Employees</option>
                @foreach ($employees as $emp)
                    <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Status --}}
        <div class="filter-group">
            <label class="filter-label">Status</label>
            <select class="filter-select" id="statusFilter" name="status">
                <option value="">All Statuses</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>

        {{-- Company --}}
        <div class="filter-group">
            <label class="filter-label">Company</label>
            <select class="filter-select" id="companyFilter" name="company">
                <option value="">All Companies</option>
                @foreach ($companies as $company)
                    <option value="{{ $company }}">{{ $company }}</option>
                @endforeach
            </select>
        </div>

        {{-- State --}}
        <div class="filter-group">
            <label class="filter-label">State</label>
            <select class="filter-select" id="stateFilter" name="state">
                <option value="">All States</option>
                @foreach ($states as $state)
                    <option value="{{ $state }}">{{ $state }}</option>
                @endforeach
            </select>
        </div>

        {{-- District --}}
        <div class="filter-group">
            <label class="filter-label">District</label>
            <select class="filter-select" id="districtFilter" name="district">
                <option value="">All Districts</option>
                @foreach ($districts as $district)
                    <option value="{{ $district }}">{{ $district }}</option>
                @endforeach
            </select>
        </div>

        {{-- City --}}
        <div class="filter-group">
            <label class="filter-label">City</label>
            <select class="filter-select" id="cityFilter" name="city">
                <option value="">All Cities</option>
                @foreach ($cities as $city)
                    <option value="{{ $city }}">{{ $city }}</option>
                @endforeach
            </select>
        </div>

        {{-- Bank --}}
        <div class="filter-group">
            <label class="filter-label">Bank</label>
            <select class="filter-select" id="bankFilter" name="bank">
                <option value="">All Banks</option>
                @foreach ($banks as $bank)
                    <option value="{{ $bank }}">{{ $bank }}</option>
                @endforeach
            </select>
        </div>

        {{-- Lead Amount --}}
        <div class="filter-group">
            <label class="filter-label">Lead Amount</label>
            <div style="display: flex; gap: 8px;">
                <input type="number" class="filter-input" name="min_amount" id="minAmountFilter" placeholder="Min">
                <input type="number" class="filter-input" name="max_amount" id="maxAmountFilter" placeholder="Max">
            </div>
        </div>

        {{-- Filter Buttons --}}
        <div class="filter-actions">
            <button class="btn-filter" type="submit">
                <i class="fas fa-filter"></i> Apply Filters
            </button>
            <button type="button" class="btn-reset" id="resetFilters">
                <i class="fas fa-undo"></i> Reset
            </button>
        </div>
    </div>
</div>
</form>




    <!-- Charts Section -->
<div class="dashboard-grid">
    <!-- Leads Per Employee Chart -->
    <div class="chart-card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-users"></i> Leads per Employee</h3>
        </div>
        <div class="chart-container">
            <canvas id="leadsPerEmployeeChart" width="400" height="250"></canvas>
        </div>
    </div>

    <!-- Lead Status Distribution Chart -->
    <div class="chart-card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-chart-bar"></i> Lead Status Distribution</h3>
        </div>
        <div class="chart-container">
            <canvas id="leadStatusChart" width="400" height="250"></canvas>
        </div>
    </div>
</div>



            <!-- Team Performance Section -->
  <div class="team-performance-section">
    <div class="team-performance-header">
        <h3 class="team-performance-title">Team Performance</h3>
    </div>

    <div class="team-performance-grid">
        @forelse ($teamPerformance as $emp)
            @php
                $initials = strtoupper(substr($emp['name'], 0, 1)) .
                            strtoupper(substr(explode(' ', $emp['name'])[1] ?? '', 0, 1));
            @endphp

            <div class="team-card">
                <div class="team-card-header">
                    <div class="team-avatar">{{ $initials }}</div>
                    <div class="team-info">
                        <div class="team-name">{{ $emp['name'] }}</div>
                        <div class="team-role">Employee</div>
                    </div>
                </div>

                <div class="team-stats">
                    <div class="team-stat">
                        <div class="team-stat-value">{{ $emp['conversion_rate'] }}%</div>
                        <div class="team-stat-label">Conversion Rate</div>
                    </div>
                    <div class="team-stat">
                        <div class="team-stat-value">{{ $emp['total_leads'] }}</div>
                        <div class="team-stat-label">Total Leads</div>
                    </div>
                </div>

                <div class="team-progress">
                    <div class="progress-header">
                        <div class="progress-label">Monthly Target</div>
                        <div class="progress-value">{{ $emp['target_percentage'] }}%</div>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $emp['target_percentage'] }}%;"></div>
                    </div>
                </div>
            </div>
        @empty
            <p>No employees found under you.</p>
        @endforelse
    </div>
</div>





<!-- Leads Table Section -->
<div class="leads-table-section">
    <div class="leads-table-header">
        <h3 class="leads-table-title">Team Leads - Assigned Leads</h3>
        <div class="leads-table-actions">
            <div class="leads-search">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search leads..." id="leadsSearch">
            </div>
            <button class="btn-filter" id="exportLeads">
                <i class="fas fa-file-export"></i>
                Export
            </button>
        </div>
    </div>

    <div class="table-container">
        <table class="leads-table">
            <thead>
                <tr>
                    <th>Client Name</th>
                    <th>Employee</th>
                    <th>Company</th>
                    <th>Location</th>
                    <th>Lead Amount</th>
                    <th>Success %</th>
                    <th>Expected Month</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($leads as $lead)
                    <tr>
                        <td>{{ $lead->name }}</td>
                        <td>{{ $lead->employee->name ?? 'Unknown' }}</td>
                        <td>{{ $lead->company_name ?? 'N/A' }}</td>
                        <td>{{ $lead->city ?? $lead->district ?? $lead->state ?? 'N/A' }}</td>
                        <td>₹{{ number_format($lead->lead_amount) }}</td>
                        <td>{{ $lead->success_percentage ?? '-' }}%</td>
                        <td>
                            {{ $lead->expected_month ? \Carbon\Carbon::parse($lead->expected_month)->format('F Y') : 'N/A' }}
                        </td>
                        <td>
                            <span class="lead-status status-{{ strtolower($lead->status) }}">
                                {{ ucfirst($lead->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No leads found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    {{-- <div class="pagination">
        <div class="pagination-info">
            Showing <span>{{ $leads->firstItem() ?? 0 }}</span> to
            <span>{{ $leads->lastItem() ?? 0 }}</span> of
            <span>{{ $leads->total() }}</span> entries
        </div>
        <div class="pagination-controls">
            {{ $leads->links('pagination::bootstrap-4') }}
        </div>
    </div>--}}
</div>



        </div>
    </div>


   <script>
    document.addEventListener('DOMContentLoaded', function () {
        const leadsPerEmployeeChartData = @json($leadsPerEmployee ?? []);
        const leadStatusChartData = @json($statusCounts ?? []);
        const empLabels = @json($leadsPerEmployee->pluck('employee'));
        const empData = @json($leadsPerEmployee->pluck('count'));

        // Chart: Leads per Employee
        new Chart(document.getElementById('leadsPerEmployeeChart'), {
            type: 'bar',
            data: {
                labels: empLabels,
                datasets: [{
                    label: 'Leads Assigned',
                    data: empData,
                    backgroundColor: '#2196F3'
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } }
            }
        });

        // Chart: Lead Status
        const statusLabels = @json($statusCounts->keys());
        const statusData = @json($statusCounts->values());

        new Chart(document.getElementById('leadStatusChart'), {
            type: 'bar',
            data: {
                labels: statusLabels,
                datasets: [{
                    label: 'Lead Count',
                    data: statusData,
                    backgroundColor: '#4CAF50'
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } }
            }
        });

        // Toggle Advanced Filters
        const toggleFiltersBtn = document.getElementById('toggleFilters');
        const filtersContent = document.getElementById('filtersContent');
        if (toggleFiltersBtn && filtersContent) {
            toggleFiltersBtn.addEventListener('click', function () {
                const isExpanded = filtersContent.style.display === 'none' || filtersContent.style.display === '';
                filtersContent.style.display = isExpanded ? 'grid' : 'none';
                toggleFiltersBtn.querySelector('i').className = isExpanded ? 'fas fa-chevron-up' : 'fas fa-chevron-down';
            });
        }

        // Filter apply
        const applyFiltersBtn = document.getElementById('applyFilters');
        if (applyFiltersBtn) {
            applyFiltersBtn.addEventListener('click', function () {
                document.getElementById('filterForm').submit();
            });
        }

     const resetFiltersBtn = document.getElementById('resetFilters');
if (resetFiltersBtn) {
    resetFiltersBtn.addEventListener('click', function () {
        window.location.href = "{{ route('team_lead.dashboard') }}"; // or your actual route
    });
}


        // Export Leads
        const exportBtn = document.getElementById('exportLeads');
        if (exportBtn) {
            exportBtn.addEventListener("click", function () {
                window.location.href = "{{ route('team_lead.leads.export') }}";
            });
        }

        // Search Filter
        const leadsSearch = document.getElementById('leadsSearch');
        if (leadsSearch) {
            leadsSearch.addEventListener('input', function () {
                const value = this.value.toLowerCase();
                document.querySelectorAll('.leads-table tbody tr').forEach(row => {
                    const rowText = row.innerText.toLowerCase();
                    row.style.display = rowText.includes(value) ? '' : 'none';
                });
            });
        }
    });
</script>


</body>
</html>
