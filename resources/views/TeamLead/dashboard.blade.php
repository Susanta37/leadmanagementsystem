<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-trend up">
                            <i class="fas fa-arrow-up"></i>
                            <span>12.5%</span>
                        </div>
                    </div>
                    <div class="stat-value">68.7%</div>
                    <div class="stat-label">Overall Conversion Rate</div>
                </div>
                
                <div class="stat-card blue">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-trend up">
                            <i class="fas fa-arrow-up"></i>
                            <span>8.3%</span>
                        </div>
                    </div>
                    <div class="stat-value">1,284</div>
                    <div class="stat-label">Total Leads</div>
                </div>
                
                <div class="stat-card green">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-trend up">
                            <i class="fas fa-arrow-up"></i>
                            <span>15.2%</span>
                        </div>
                    </div>
                    <div class="stat-value">882</div>
                    <div class="stat-label">Converted Leads</div>
                </div>
                
                <div class="stat-card red">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-rupee-sign"></i>
                        </div>
                        <div class="stat-trend up">
                            <i class="fas fa-arrow-up"></i>
                            <span>18.7%</span>
                        </div>
                    </div>
                    <div class="stat-value">₹42.8L</div>
                    <div class="stat-label">Total Lead Value</div>
                </div>
            </div>
            
            <!-- Filters Section -->
            <div class="filters-section">
                <div class="filters-header">
                    <h3 class="filters-title">Filter Reports</h3>
                    <button class="filters-toggle" id="toggleFilters">
                        <span>Advanced Filters</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="filters-content" id="filtersContent">
                    <div class="filter-group">
                        <label class="filter-label">Date Range</label>
                        <select class="filter-select" id="dateRangeFilter">
                            <option value="7">Last 7 Days</option>
                            <option value="30" selected>Last 30 Days</option>
                            <option value="90">Last 90 Days</option>
                            <option value="180">Last 6 Months</option>
                            <option value="365">Last Year</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Team Lead</label>
                        <select class="filter-select" id="teamLeadFilter">
                            <option value="">All Team Leads</option>
                            <option value="1">Rajesh Kumar</option>
                            <option value="2">Priya Sharma</option>
                            <option value="3">Vikram Singh</option>
                            <option value="4">Neha Patel</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Status</label>
                        <select class="filter-select" id="statusFilter">
                            <option value="">All Statuses</option>
                            <option value="new">New</option>
                            <option value="contacted">Contacted</option>
                            <option value="qualified">Qualified</option>
                            <option value="converted">Converted</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Location</label>
                        <select class="filter-select" id="locationFilter">
                            <option value="">All Locations</option>
                            <option value="Mumbai">Mumbai</option>
                            <option value="Delhi">Delhi</option>
                            <option value="Bangalore">Bangalore</option>
                            <option value="Hyderabad">Hyderabad</option>
                            <option value="Chennai">Chennai</option>
                            <option value="Kolkata">Kolkata</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Lead Amount</label>
                        <div style="display: flex; gap: 8px;">
                            <input type="number" class="filter-input" id="minAmountFilter" placeholder="Min">
                            <input type="number" class="filter-input" id="maxAmountFilter" placeholder="Max">
                        </div>
                    </div>
                    
                    <div class="filter-actions">
                        <button class="btn-filter" id="applyFilters">
                            <i class="fas fa-filter"></i>
                            Apply Filters
                        </button>
                        <button class="btn-reset" id="resetFilters">
                            <i class="fas fa-undo"></i>
                            Reset
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Charts Section -->
            <div class="dashboard-grid">
                <div class="chart-card">
                    <div class="card-header">
                        <h3 class="card-title">Lead Conversion Rate</h3>
                        <button class="card-action" id="downloadConversionChart">
                            <i class="fas fa-download"></i>
                            Export
                        </button>
                    </div>
                    <div class="chart-container">
                        <canvas id="conversionRateChart"></canvas>
                    </div>
                </div>
                
                <div class="chart-card">
                    <div class="card-header">
                        <h3 class="card-title">Lead Status Distribution</h3>
                        <button class="card-action" id="downloadStatusChart">
                            <i class="fas fa-download"></i>
                            Export
                        </button>
                    </div>
                    <div class="chart-container">
                        <canvas id="leadStatusChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Team Performance Section -->
            <div class="team-performance-section">
                <div class="team-performance-header">
                    <h3 class="team-performance-title">Team Performance</h3>
                    <div class="team-performance-tabs">
                        <div class="team-tab active" data-tab="conversion">Conversion Rate</div>
                        <div class="team-tab" data-tab="volume">Lead Volume</div>
                        <div class="team-tab" data-tab="value">Lead Value</div>
                    </div>
                </div>
                
                <div class="team-performance-grid">
                    <!-- Team Card 1 -->
                    <div class="team-card">
                        <div class="team-card-header">
                            <div class="team-avatar">RK</div>
                            <div class="team-info">
                                <div class="team-name">Rajesh Kumar</div>
                                <div class="team-role">Senior Team Lead</div>
                            </div>
                        </div>
                        
                        <div class="team-stats">
                            <div class="team-stat">
                                <div class="team-stat-value">78.2%</div>
                                <div class="team-stat-label">Conversion Rate</div>
                            </div>
                            <div class="team-stat">
                                <div class="team-stat-value">342</div>
                                <div class="team-stat-label">Total Leads</div>
                            </div>
                        </div>
                        
                        <div class="team-progress">
                            <div class="progress-header">
                                <div class="progress-label">Monthly Target</div>
                                <div class="progress-value">78%</div>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 78%;"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Team Card 2 -->
                    <div class="team-card">
                        <div class="team-card-header">
                            <div class="team-avatar">PS</div>
                            <div class="team-info">
                                <div class="team-name">Priya Sharma</div>
                                <div class="team-role">Team Lead</div>
                            </div>
                        </div>
                        
                        <div class="team-stats">
                            <div class="team-stat">
                                <div class="team-stat-value">72.5%</div>
                                <div class="team-stat-label">Conversion Rate</div>
                            </div>
                            <div class="team-stat">
                                <div class="team-stat-value">298</div>
                                <div class="team-stat-label">Total Leads</div>
                            </div>
                        </div>
                        
                        <div class="team-progress">
                            <div class="progress-header">
                                <div class="progress-label">Monthly Target</div>
                                <div class="progress-value">65%</div>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 65%;"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Team Card 3 -->
                    <div class="team-card">
                        <div class="team-card-header">
                            <div class="team-avatar">VS</div>
                            <div class="team-info">
                                <div class="team-name">Vikram Singh</div>
                                <div class="team-role">Team Lead</div>
                            </div>
                        </div>
                        
                        <div class="team-stats">
                            <div class="team-stat">
                                <div class="team-stat-value">68.9%</div>
                                <div class="team-stat-label">Conversion Rate</div>
                            </div>
                            <div class="team-stat">
                                <div class="team-stat-value">276</div>
                                <div class="team-stat-label">Total Leads</div>
                            </div>
                        </div>
                        
                        <div class="team-progress">
                            <div class="progress-header">
                                <div class="progress-label">Monthly Target</div>
                                <div class="progress-value">82%</div>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 82%;"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Team Card 4 -->
                    <div class="team-card">
                        <div class="team-card-header">
                            <div class="team-avatar">NP</div>
                            <div class="team-info">
                                <div class="team-name">Neha Patel</div>
                                <div class="team-role">Team Lead</div>
                            </div>
                        </div>
                        
                        <div class="team-stats">
                            <div class="team-stat">
                                <div class="team-stat-value">64.3%</div>
                                <div class="team-stat-label">Conversion Rate</div>
                            </div>
                            <div class="team-stat">
                                <div class="team-stat-value">368</div>
                                <div class="team-stat-label">Total Leads</div>
                            </div>
                        </div>
                        
                        <div class="team-progress">
                            <div class="progress-header">
                                <div class="progress-label">Monthly Target</div>
                                <div class="progress-value">71%</div>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 71%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Leads Table Section -->
            <div class="leads-table-section">
                <div class="leads-table-header">
                    <h3 class="leads-table-title">Lead Details</h3>
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
                                <th>Name</th>
                                <th>Company</th>
                                <th>Location</th>
                                <th>Lead Amount</th>
                                <th>Success %</th>
                                <th>Expected Month</th>
                                <th>Status</th>
                                <th>Team Lead</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Amit Sharma</td>
                                <td>Tech Solutions Ltd</td>
                                <td>Mumbai</td>
                                <td>₹4,50,000</td>
                                <td>85%</td>
                                <td>June 2023</td>
                                <td><span class="lead-status status-converted">Converted</span></td>
                                <td>Rajesh Kumar</td>
                                <td>
                                    <div class="lead-actions">
                                        <button class="btn-icon btn-view"><i class="fas fa-eye"></i></button>
                                        <button class="btn-icon"><i class="fas fa-edit"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Priya Patel</td>
                                <td>Global Enterprises</td>
                                <td>Delhi</td>
                                <td>₹3,25,000</td>
                                <td>72%</td>
                                <td>July 2023</td>
                                <td><span class="lead-status status-qualified">Qualified</span></td>
                                <td>Priya Sharma</td>
                                <td>
                                    <div class="lead-actions">
                                        <button class="btn-icon btn-view"><i class="fas fa-eye"></i></button>
                                        <button class="btn-icon"><i class="fas fa-edit"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Rahul Verma</td>
                                <td>Innovate Systems</td>
                                <td>Bangalore</td>
                                <td>₹5,80,000</td>
                                <td>65%</td>
                                <td>August 2023</td>
                                <td><span class="lead-status status-contacted">Contacted</span></td>
                                <td>Vikram Singh</td>
                                <td>
                                    <div class="lead-actions">
                                        <button class="btn-icon btn-view"><i class="fas fa-eye"></i></button>
                                        <button class="btn-icon"><i class="fas fa-edit"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Neha Gupta</td>
                                <td>Sunrise Industries</td>
                                <td>Chennai</td>
                                <td>₹2,75,000</td>
                                <td>90%</td>
                                <td>June 2023</td>
                                <td><span class="lead-status status-converted">Converted</span></td>
                                <td>Neha Patel</td>
                                <td>
                                    <div class="lead-actions">
                                        <button class="btn-icon btn-view"><i class="fas fa-eye"></i></button>
                                        <button class="btn-icon"><i class="fas fa-edit"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Vikram Malhotra</td>
                                <td>Prime Solutions</td>
                                <td>Hyderabad</td>
                                <td>₹6,20,000</td>
                                <td>78%</td>
                                <td>July 2023</td>
                                <td><span class="lead-status status-qualified">Qualified</span></td>
                                <td>Rajesh Kumar</td>
                                <td>
                                    <div class="lead-actions">
                                        <button class="btn-icon btn-view"><i class="fas fa-eye"></i></button>
                                        <button class="btn-icon"><i class="fas fa-edit"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Ananya Singh</td>
                                <td>Future Tech</td>
                                <td>Pune</td>
                                <td>₹3,90,000</td>
                                <td>45%</td>
                                <td>September 2023</td>
                                <td><span class="lead-status status-new">New</span></td>
                                <td>Priya Sharma</td>
                                <td>
                                    <div class="lead-actions">
                                        <button class="btn-icon btn-view"><i class="fas fa-eye"></i></button>
                                        <button class="btn-icon"><i class="fas fa-edit"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Rajat Kapoor</td>
                                <td>Stellar Corp</td>
                                <td>Kolkata</td>
                                <td>₹5,40,000</td>
                                <td>25%</td>
                                <td>October 2023</td>
                                <td><span class="lead-status status-closed">Closed</span></td>
                                <td>Vikram Singh</td>
                                <td>
                                    <div class="lead-actions">
                                        <button class="btn-icon btn-view"><i class="fas fa-eye"></i></button>
                                        <button class="btn-icon"><i class="fas fa-edit"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="pagination">
                    <div class="pagination-info">
                        Showing <span>1</span> to <span>7</span> of <span>42</span> entries
                    </div>
                    <div class="pagination-controls">
                        <button class="pagination-button disabled"><i class="fas fa-chevron-left"></i></button>
                        <button class="pagination-button active">1</button>
                        <button class="pagination-button">2</button>
                        <button class="pagination-button">3</button>
                        <button class="pagination-button">4</button>
                        <button class="pagination-button">5</button>
                        <button class="pagination-button"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle filters
            const toggleFiltersBtn = document.getElementById('toggleFilters');
            const filtersContent = document.getElementById('filtersContent');
            
            toggleFiltersBtn.addEventListener('click', function() {
                const isExpanded = filtersContent.style.display === 'none' || filtersContent.style.display === '';
                filtersContent.style.display = isExpanded ? 'grid' : 'none';
                toggleFiltersBtn.querySelector('i').className = isExpanded ? 'fas fa-chevron-up' : 'fas fa-chevron-down';
            });
            
            // Team performance tabs
            const teamTabs = document.querySelectorAll('.team-tab');
            
            teamTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    teamTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    
                    // In a real application, you would update the team performance data here
                    const tabType = this.getAttribute('data-tab');
                    updateTeamPerformance(tabType);
                });
            });
            
            // Initialize charts
            initializeConversionChart();
            initializeStatusChart();
            
            // Animate counters
            animateCounters();
            
            // Initialize filters
            initializeFilters();
        });
        
        // Initialize conversion rate chart
        function initializeConversionChart() {
            const ctx = document.getElementById('conversionRateChart').getContext('2d');
            
            const data = {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'Conversion Rate (%)',
                        data: [62, 59, 65, 61, 68, 71, 75, 72, 69, 74, 76, 78],
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Target Rate (%)',
                        data: [60, 60, 65, 65, 65, 70, 70, 70, 75, 75, 75, 80],
                        borderColor: '#d1d5db',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        tension: 0.3,
                        fill: false
                    }
                ]
            };
            
            const config = {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 6
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#1f2937',
                            bodyColor: '#4b5563',
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 6,
                            usePointStyle: true,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y + '%';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                }
            };
            
            new Chart(ctx, config);
        }
        
        // Initialize lead status chart
        function initializeStatusChart() {
            const ctx = document.getElementById('leadStatusChart').getContext('2d');
            
            const data = {
                labels: ['New', 'Contacted', 'Qualified', 'Converted', 'Closed'],
                datasets: [{
                    data: [15, 25, 20, 30, 10],
                    backgroundColor: [
                        '#0284c7',
                        '#d97706',
                        '#16a34a',
                        '#2563eb',
                        '#dc2626'
                    ],
                    borderWidth: 0
                }]
            };
            
            const config = {
                type: 'doughnut',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 6,
                                padding: 20
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#1f2937',
                            bodyColor: '#4b5563',
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 6,
                            usePointStyle: true,
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((acc, data) => acc + data, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return context.label + ': ' + percentage + '%';
                                }
                            }
                        }
                    },
                    cutout: '65%'
                }
            };
            
            new Chart(ctx, config);
        }
        
        // Animate counters
        function animateCounters() {
            const statValues = document.querySelectorAll('.stat-value');
            
            statValues.forEach(stat => {
                const finalValue = stat.textContent;
                let startValue = 0;
                
                // Check if value is a percentage
                if (finalValue.includes('%')) {
                    const numericValue = parseFloat(finalValue);
                    const duration = 1500;
                    const increment = numericValue / (duration / 16);
                    
                    const timer = setInterval(() => {
                        startValue += increment;
                        if (startValue >= numericValue) {
                            stat.textContent = finalValue;
                            clearInterval(timer);
                        } else {
                            stat.textContent = startValue.toFixed(1) + '%';
                        }
                    }, 16);
                }
                // Check if value is currency
                else if (finalValue.includes('₹')) {
                    const numericValue = parseFloat(finalValue.replace(/[^\d.]/g, ''));
                    const duration = 1500;
                    const increment = numericValue / (duration / 16);
                    
                    const timer = setInterval(() => {
                        startValue += increment;
                        if (startValue >= numericValue) {
                            stat.textContent = finalValue;
                            clearInterval(timer);
                        } else {
                            stat.textContent = '₹' + startValue.toFixed(1) + 'L';
                        }
                    }, 16);
                }
                // Regular number
                else {
                    const numericValue = parseInt(finalValue.replace(/,/g, ''));
                    const duration = 1500;
                    const increment = numericValue / (duration / 16);
                    
                    const timer = setInterval(() => {
                        startValue += increment;
                        if (startValue >= numericValue) {
                            stat.textContent = finalValue;
                            clearInterval(timer);
                        } else {
                            stat.textContent = Math.floor(startValue).toLocaleString();
                        }
                    }, 16);
                }
            });
        }
        
        // Update team performance based on selected tab
        function updateTeamPerformance(tabType) {
            // Sample data for different tabs
            const performanceData = {
                conversion: [
                    { name: 'Rajesh Kumar', value: '78.2%', total: '342', progress: '78%' },
                    { name: 'Priya Sharma', value: '72.5%', total: '298', progress: '65%' },
                    { name: 'Vikram Singh', value: '68.9%', total: '276', progress: '82%' },
                    { name: 'Neha Patel', value: '64.3%', total: '368', progress: '71%' }
                ],
                volume: [
                    { name: 'Rajesh Kumar', value: '342', total: '78.2%', progress: '85%' },
                    { name: 'Priya Sharma', value: '298', total: '72.5%', progress: '76%' },
                    { name: 'Vikram Singh', value: '276', total: '68.9%', progress: '69%' },
                    { name: 'Neha Patel', value: '368', total: '64.3%', progress: '92%' }
                ],
                value: [
                    { name: 'Rajesh Kumar', value: '₹18.5L', total: '342', progress: '92%' },
                    { name: 'Priya Sharma', value: '₹12.8L', total: '298', progress: '80%' },
                    { name: 'Vikram Singh', value: '₹15.2L', total: '276', progress: '76%' },
                    { name: 'Neha Patel', value: '₹16.7L', total: '368', progress: '83%' }
                ]
            };
            
            const data = performanceData[tabType];
            const teamCards = document.querySelectorAll('.team-card');
            
            // Update team cards with new data
            teamCards.forEach((card, index) => {
                if (data[index]) {
                    const statValue = card.querySelector('.team-stat-value');
                    const statLabel = card.querySelector('.team-stat-label');
                    const progressValue = card.querySelector('.progress-value');
                    const progressFill = card.querySelector('.progress-fill');
                    
                    // Animate the transition
                    animateValue(statValue, data[index].value);
                    
                    // Update labels based on tab type
                    if (tabType === 'conversion') {
                        statLabel.textContent = 'Conversion Rate';
                    } else if (tabType === 'volume') {
                        statLabel.textContent = 'Total Leads';
                    } else if (tabType === 'value') {
                        statLabel.textContent = 'Lead Value';
                    }
                    
                    progressValue.textContent = data[index].progress;
                    progressFill.style.width = data[index].progress;
                }
            });
        }
        
        // Animate value change
        function animateValue(element, newValue) {
            const currentValue = element.textContent;
            element.textContent = newValue;
            
            // Add a brief highlight effect
            element.style.color = '#6366f1';
            setTimeout(() => {
                element.style.color = '';
            }, 1000);
        }
        
        // Initialize filters
        function initializeFilters() {
            const applyFiltersBtn = document.getElementById('applyFilters');
            const resetFiltersBtn = document.getElementById('resetFilters');
            const leadsSearch = document.getElementById('leadsSearch');
            
            // Apply filters
            applyFiltersBtn.addEventListener('click', function() {
                // In a real application, you would fetch filtered data from the server
                // For demo purposes, we'll just show a notification
                showNotification('Filters applied successfully', 'success');
            });
            
            // Reset filters
            resetFiltersBtn.addEventListener('click', function() {
                const filterSelects = document.querySelectorAll('.filter-select');
                const filterInputs = document.querySelectorAll('.filter-input');
                
                filterSelects.forEach(select => {
                    select.selectedIndex = 0;
                });
                
                filterInputs.forEach(input => {
                    input.value = '';
                });
                
                showNotification('Filters have been reset', 'info');
            });
            
            // Search leads
            leadsSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const tableRows = document.querySelectorAll('.leads-table tbody tr');
                
                tableRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
            
            // Export buttons
            document.getElementById('downloadConversionChart').addEventListener('click', function() {
                showNotification('Conversion chart exported successfully', 'success');
            });
            
            document.getElementById('downloadStatusChart').addEventListener('click', function() {
                showNotification('Status chart exported successfully', 'success');
            });
            
            document.getElementById('exportLeads').addEventListener('click', function() {
                showNotification('Leads data exported successfully', 'success');
            });
        }
        
        // Show notification
        function showNotification(message, type = 'info') {
            // Check if notification container exists
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
            
            // Create notification
            const notification = document.createElement('div');
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
                transition: opacity 0.3s ease;
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
    </script>
</body>
</html>
