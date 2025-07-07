<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Lead Management System</title>
    <link rel="icon" type="image/png" href="{{ asset('logo1.png') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
   @include('TeamLead.Components.css')
    <style>
        :root {
            --primary-color: #3b82f6;
            --secondary-color: #f97316;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --error-color: #ef4444;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --background-light: #f8fafc;
            --background-white: #ffffff;
            --border-color: #e5e7eb;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .dashboard-container {
            padding: 24px;
            background: var(--background-light);
            min-height: calc(100vh - 80px);
        }

        /* Dashboard Filters */
        .dashboard-filters {
            background: var(--background-white);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 32px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
        }

        .filter-section {
            display: flex;
            align-items: end;
            gap: 20px;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-width: 150px;
        }

        .filter-group label {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .filter-select {
            padding: 10px 12px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            background: var(--background-white);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .filter-actions {
            display: flex;
            gap: 12px;
            margin-left: auto;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--background-white);
            border-radius: 16px;
            padding: 24px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: visible; /* Allow dropdowns to overflow */
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }

        .stat-card.team-leads::before {
            background: linear-gradient(90deg, var(--secondary-color), #ea580c);
        }

        .stat-card.tasks::before {
            background: linear-gradient(90deg, var(--success-color), #059669);
        }

        .stat-card.attendance::before {
            background: linear-gradient(90deg, var(--warning-color), #d97706);
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-color), #2563eb);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            box-shadow: var(--shadow-md);
        }

        .stat-menu {
            position: relative;
        }

        .menu-btn {
            width: 32px;
            height: 32px;
            border: none;
            background: rgba(107, 114, 128, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .menu-btn:hover {
            background: rgba(107, 114, 128, 0.2);
            color: var(--text-primary);
        }

        .dropdown-menu1 {
            position: absolute;
            top: 100%;
            right: 0;
            background: var(--background-white);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-shadow: var(--shadow-lg);
            min-width: 150px;
            z-index: 100;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }

        .dropdown-menu1.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-menu1 a {
            display: block;
            padding: 12px 16px;
            color: var(--text-primary);
            text-decoration: none;
            font-size: 14px;
            transition: background 0.3s ease;
        }

        .dropdown-menu1 a:hover {
            background: rgba(59, 130, 246, 0.1);
        }

        .stat-content {
            margin-bottom: 16px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 4px;
            line-height: 1;
        }

        .stat-label {
            font-size: 14px;
            color: var(--text-secondary);
            font-weight: 500;
            margin-bottom: 12px;
        }

        .stat-details {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .detail-item {
            font-size: 12px;
            font-weight: 500;
            padding: 2px 0;
        }

        .detail-item.active, .detail-item.success {
            color: var(--success-color);
        }

        .detail-item.pending, .detail-item.warning {
            color: var(--warning-color);
        }

        .detail-item.inactive, .detail-item.error {
            color: var(--error-color);
        }

        .stat-chart {
            position: absolute;
            bottom: 16px;
            right: 16px;
            opacity: 0.3;
        }

        /* Charts Section */
        .charts-section {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
            margin-bottom: 32px;
        }

        .chart-card {
            background: var(--background-white);
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .chart-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .chart-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.02), rgba(249, 115, 22, 0.02));
        }

        .chart-header h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .chart-controls {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .chart-filter {
            padding: 6px 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            background: var(--background-white);
            color: var(--text-primary);
            cursor: pointer;
        }

        .approval-stats {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .approval-stat {
            text-align: center;
        }

        .stat-number {
            display: block;
            font-size: 24px;
            font-weight: 700;
            color: var(--success-color);
        }

        .stat-text {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .chart-content {
            padding: 24px;
            height: 300px;
        }

        /* Tables Section */
        .tables-section {
            display: flex;
            flex-direction: column;
            gap: 32px;
            margin-bottom: 32px;
        }

        .table-card {
            background: var(--background-white);
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .table-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.02), rgba(249, 115, 22, 0.02));
        }

        .table-header h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .table-actions {
            display: flex;
            gap: 12px;
        }

        .table-content {
            overflow-x: auto;
        }

        .table-wrapper {
            min-width: 100%;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .data-table th {
            background: rgba(59, 130, 246, 0.05);
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
        }

        .data-table td {
            font-size: 14px;
            color: var(--text-primary);
        }

        .data-table tr:hover {
            background: rgba(59, 130, 246, 0.02);
        }

        .employee-info, .customer-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .employee-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }

        .employee-name, .customer-name {
            font-weight: 600;
            color: var(--text-primary);
        }

        .employee-id, .customer-phone {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-badge.approved {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .status-badge.pending {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .status-badge.rejected {
            background: rgba(239, 68, 68, 0.1);
            color: var(--error-color);
        }

        .status-badge.in-progress {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-color);
        }

        .status-badge.overdue {
            background: rgba(239, 68, 68, 0.1);
            color: var(--error-color);
        }

        .status-select {
            padding: 6px 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 12px;
            background: var(--background-white);
            color: var(--text-primary);
            cursor: pointer;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            border: none;
            background: rgba(107, 114, 128, 0.1);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-icon:hover {
            background: var(--primary-color);
            color: white;
        }

        /* Task Management */
        .task-management-section {
            margin-bottom: 32px;
        }

        .task-card {
            background: var(--background-white);
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .task-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.02), rgba(249, 115, 22, 0.02));
        }

        .task-header h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .task-content {
            padding: 24px;
        }

        .task-filters {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .task-filter {
            padding: 8px 16px;
            border: 1px solid var(--border-color);
            background: var(--background-white);
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .task-filter.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .task-filter:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .task-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .task-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .task-item:hover {
            border-color: var(--primary-color);
            box-shadow: var(--shadow-md);
        }

        .task-info {
            flex: 1;
        }

        .task-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .task-meta {
            display: flex;
            gap: 16px;
            font-size: 14px;
            color: var(--text-secondary);
        }

        .task-status {
            display: flex;
            align-items: center;
        }

        .task-actions {
            display: flex;
            gap: 8px;
        }

        /* Team Overview */
        .team-overview-section {
            margin-bottom: 32px;
        }

        .team-card {
            background: var(--background-white);
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .team-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.02), rgba(249, 115, 22, 0.02));
        }

        .team-header h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .team-actions {
            display: flex;
            gap: 12px;
        }

        .team-content {
            padding: 24px;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .member-card {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.02), rgba(249, 115, 22, 0.02));
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .member-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-color);
        }

        .member-avatar {
            position: relative;
            width: 60px;
            height: 60px;
            margin: 0 auto 16px;
        }

        .member-avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .member-status {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 2px solid white;
        }

        .member-status.online {
            background: var(--success-color);
        }

        .member-status.away {
            background: var(--warning-color);
        }

        .member-status.offline {
            background: var(--text-secondary);
        }

        .member-info {
            text-align: center;
        }

        .member-name {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .member-role {
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 16px;
        }

        .member-stats {
            display: flex;
            justify-content: space-between;
            gap: 16px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-label {
            font-size: 12px;
            color: var(--text-secondary);
            margin-bottom: 4px;
        }

        .stat-value {
            font-size: 16px;
            font-weight: 600;
            color: var(--primary-color);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-secondary {
            background: rgba(107, 114, 128, 0.1);
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: rgba(107, 114, 128, 0.2);
            color: var(--text-primary);
        }

        /* Modal Styles */
     .modal-overlay {
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}

.modal-overlay.show {
    display: flex;
}

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: var(--background-white);
            border-radius: 16px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: var(--shadow-xl);
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }

        .modal-overlay.active .modal-content {
            transform: scale(1);
        }

        .modal-content.large {
    background: white;
    border-radius: 8px;
    padding: 20px;
    width: 80%;
    max-height: 90vh;
    overflow-y: auto;
}


        .modal-header {
            padding: 24px 24px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header h2 {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .modal-close {
            width: 32px;
            height: 32px;
            border: none;
            background: rgba(107, 114, 128, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .modal-close:hover {
            background: rgba(107, 114, 128, 0.2);
            color: var(--text-primary);
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            padding: 0 24px 24px;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: var(--background-white);
            color: var(--text-primary);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        /* Employee Details Modal */
        .employee-details {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .employee-profile {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 20px;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), rgba(249, 115, 22, 0.05));
            border-radius: 12px;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid white;
            box-shadow: var(--shadow-md);
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-info h3 {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .profile-info p {
            color: var(--text-secondary);
            margin-bottom: 2px;
        }

        .employee-tabs {
            display: flex;
            border-bottom: 1px solid var(--border-color);
        }

        .tab-btn {
            padding: 12px 24px;
            border: none;
            background: none;
            color: var(--text-secondary);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 2px solid transparent;
        }

        .tab-btn.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }

        .tab-content {
            padding: 24px 0;
        }

        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
        }

        .performance-metrics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .metric-card {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), rgba(249, 115, 22, 0.05));
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
        }

        .metric-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 8px;
        }

        .metric-label {
            font-size: 14px;
            color: var(--text-secondary);
        }

        .attendance-summary, .task-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .summary-card, .task-stat {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), rgba(249, 115, 22, 0.05));
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 16px;
            text-align: center;
        }

        .summary-value, .stat-number {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 4px;
        }

        .summary-label, .stat-label {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .overview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
        }

        .overview-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .overview-item label {
            font-weight: 600;
            color: var(--text-secondary);
        }

        .overview-item span {
            color: var(--text-primary);
        }

        /* AOS Animation */
        [data-aos] {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

         .lead-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-family: Arial, sans-serif;
        font-size: 14px;
    }

    .lead-table th, .lead-table td {
        border: 1px solid #ddd;
        padding: 10px 12px;
        text-align: left;
    }

    .lead-table th {
        background-color: #f4f4f4;
        color: #333;
        text-transform: uppercase;
    }

    .lead-table tbody tr:hover {
        background-color: #f9f9f9;
    }

    .highlight-lead {
        background-color: #fffbe6;
        font-weight: bold;
    }

    .status-badge {
        padding: 4px 8px;
        font-size: 12px;
        border-radius: 4px;
        display: inline-block;
    }

    .status-authorized { background-color: #e6f0ff; color: #0047ab; }
    .status-disbursed { background-color: #e6ffed; color: #087f23; }
    .status-rejected { background-color: #ffe6e6; color: #d32f2f; }
    .status-approved { background-color: #e0f7f4; color: #00695c; }
    .status-default { background-color: #f0f0f0; color: #555; }


        [data-aos].aos-animate {
            opacity: 1;
            transform: translateY(0);
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .charts-section {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 16px;
            }

            .filter-section {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-actions {
                margin-left: 0;
                margin-top: 16px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .charts-section {
                grid-template-columns: 1fr;
            }

            .team-grid {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .employee-profile {
                flex-direction: column;
                text-align: center;
            }

            .employee-tabs {
                flex-wrap: wrap;
            }

            .tab-btn {
                flex: 1;
                min-width: 120px;
            }
        }

        @media (max-width: 480px) {
            .task-filters {
                flex-direction: column;
            }

            .task-filter {
                text-align: center;
            }

            .task-item {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }

            .task-actions {
                justify-content: center;
            }

            .table-actions {
                flex-direction: column;
                gap: 8px;
            }

            .action-buttons {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    @include('TeamLead.Components.sidebar')

    <div class="main-content">
        @include('TeamLead.Components.header', ['title' => 'Team Lead Dashboard', 'subtitle' => 'Manage your team and track performance'])

        <div class="dashboard-container">
            <!-- Dashboard Filters -->
            <div class="dashboard-filters" data-aos="fade-down">
                <div class="filter-section">
                    <div class="filter-group">
                        <label for="dateRange">Date Range</label>
                        <select id="dateRange" class="filter-select">
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month" selected>This Month</option>
                            <option value="quarter">This Quarter</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="teamFilter">Team</label>
                        <select id="teamFilter" class="filter-select">
                            <option value="all">All Teams</option>
                            <option value="sales">Sales Team</option>
                            <option value="marketing">Marketing Team</option>
                            <option value="operations">Operations Team</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="statusFilter">Status</label>
                        <select id="statusFilter" class="filter-select">
                            <option value="all">All Status</option>
                            <option value="active">Active</option>
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>

                    <div class="filter-actions">
                        <button class="btn btn-primary" onclick="applyFilters()">
                            <i class="fas fa-filter"></i>
                            Apply Filters
                        </button>
                        <button class="btn btn-secondary" onclick="resetFilters()">
                            <i class="fas fa-refresh"></i>
                            Reset
                        </button>
                    </div>
                </div>
            </div>


    <!-- Quick Stats Cards -->
<div class="stats-grid">

    <!-- Total Leads -->
    <div class="stat-card blue">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-user-friends"></i></div>
            <button class="export-btn" onclick="exportLeads('total')">
                <i class="fas fa-file-excel"></i> Export
            </button>
        </div>
        <div class="stat-value">{{ $stats['total_leads'] }}</div>
        <div class="stat-label">Total Leads</div>
    </div>
    <!-- Personal Leads -->
<div class="stat-card orange">
    <div class="stat-header">
        <div class="stat-icon"><i class="fas fa-user-tag"></i></div>
        <button class="export-btn" onclick="exportLeads('personal')">
            <i class="fas fa-file-excel"></i> Export
        </button>
    </div>
    <div class="stat-value">{{ $stats['personal_leads'] ?? 0 }}</div>
    <div class="stat-label">Personal Leads</div>
</div>


    <!-- Authorized Leads -->
    <div class="stat-card green">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <button class="export-btn" onclick="exportLeads('authorized')">
                <i class="fas fa-file-excel"></i> Export
            </button>
        </div>
        <div class="stat-value">{{ $stats['authorized_leads'] }}</div>
        <div class="stat-label">Authorized Leads</div>
    </div>

    <!-- Login Leads -->
    <div class="stat-card purple">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-sign-in-alt"></i></div>
            <button class="export-btn" onclick="exportLeads('login')">
                <i class="fas fa-file-excel"></i> Export
            </button>
        </div>
        <div class="stat-value">{{ $stats['login_leads'] }}</div>
        <div class="stat-label">Login Leads</div>
    </div>

    <!-- Approved Leads -->
    <div class="stat-card teal">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-thumbs-up"></i></div>
            <button class="export-btn" onclick="exportLeads('approved')">
                <i class="fas fa-file-excel"></i> Export
            </button>
        </div>
        <div class="stat-value">{{ $stats['approved_leads'] }}</div>
        <div class="stat-label">Approved Leads</div>
    </div>

    <!-- Disbursed Leads -->
    <div class="stat-card yellow">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-hand-holding-usd"></i></div>
            <button class="export-btn" onclick="exportLeads('disbursed')">
                <i class="fas fa-file-excel"></i> Export
            </button>
        </div>
        <div class="stat-value">{{ $stats['disbursed_leads'] }}</div>
        <div class="stat-label">Disbursed Leads</div>
    </div>

    <!-- Rejected Leads -->
    <div class="stat-card dark">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
            <button class="export-btn" onclick="exportLeads('rejected')">
                <i class="fas fa-file-excel"></i> Export
            </button>
        </div>
        <div class="stat-value">{{ $stats['rejected_leads'] }}</div>
        <div class="stat-label">Rejected Leads</div>
    </div>

    <!-- Active Employees -->
    <div class="stat-card grey">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-user"></i></div>
        </div>
        <div class="stat-value">{{ $stats['active_employees'] }}</div>
        <div class="stat-label">Active Employees</div>
    </div>
</div>


            <!-- Charts Section -->
            {{-- <div class="charts-section" data-aos="fade-up" data-aos-delay="200">
                <div class="chart-card performance-chart">
                    <div class="chart-header">
                        <h3>Team Performance Overview</h3>
                        <div class="chart-controls">
                            <select class="chart-filter">
                                <option value="week">Last 7 Days</option>
                                <option value="month" selected>Last 30 Days</option>
                                <option value="quarter">Last 3 Months</option>
                            </select>
                        </div>
                    </div>
                    <div class="chart-content">
                        <canvas id="performanceChart"></canvas>
                    </div>
                </div>

                <div class="chart-card approval-chart">
                    <div class="chart-header">
                        <h3>Lead Approval Success Rate</h3>
                        <div class="approval-stats">
                            <div class="approval-stat">
                                <span class="stat-number">82.5%</span>
                                <span class="stat-text">Success Rate</span>
                            </div>
                        </div>
                    </div>
                    <div class="chart-content">
                        <canvas id="approvalChart"></canvas>
                    </div>
                </div>
            </div> --}}

            <!-- Data Tables Section -->
           <div class="table-card">
    <div class="table-header">
        <h3>Today's Attendance Details</h3>
    </div>
    <div class="table-content">
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Hours</th>
                        <th>Check-In Location</th>
                        <th>Check-Out Location</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                        <tr>
                            <td>EMP{{ str_pad($attendance->employee_id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('h:i A') : '-' }}</td>
                            <td>{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('h:i A') : '-' }}</td>
                            <td>
                                @if($attendance->check_in && $attendance->check_out)
                                    @php
                                        $in = \Carbon\Carbon::parse($attendance->check_in);
                                        $out = \Carbon\Carbon::parse($attendance->check_out);
                                        $duration = $in->diff($out);
                                    @endphp
                                    {{ $duration->h }}h {{ $duration->i }}m
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $attendance->check_in_location ?? '-' }}</td>
                            <td>{{ $attendance->check_out_location ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No attendance found for today.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<form method="GET" action="{{ route('team_lead.reports.index') }}" class="filter-form mb-4">
    <label>Filter by Date:</label>
    <select name="filter" onchange="toggleCustomRange(this.value)">
        <option value="7" {{ request('filter') == 7 ? 'selected' : '' }}>Last 7 Days</option>
        <option value="15" {{ request('filter') == 15 ? 'selected' : '' }}>Last 15 Days</option>
        <option value="30" {{ request('filter') == 30 ? 'selected' : '' }}>Last 30 Days</option>
        <option value="custom" {{ request('filter') == 'custom' ? 'selected' : '' }}>Custom Range</option>
    </select>

    <div id="custom-range" style="display: {{ request('filter') == 'custom' ? 'block' : 'none' }};">
        <label>From:</label>
        <input type="date" name="from" value="{{ request('from') }}">
        <label>To:</label>
        <input type="date" name="to" value="{{ request('to') }}">
    </div>

    <button type="submit" class="btn btn-primary">Apply</button>
</form>


@if($leads->count())
    <table class="lead-table">
        <thead>
            <tr>
                <th>Lead ID</th>
                <th>Customer</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Assigned To</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leads as $lead)
                <tr class="{{ $lead->lead_amount > 100000 ? 'highlight-lead' : '' }}">
                    <td>{{ $lead->id }}</td>
                    <td>{{ $lead->name }}</td>
                    <td>₹{{ number_format($lead->lead_amount, 2) }}</td>
                    <td>
                        <span class="status-badge
                            {{
                                $lead->status === 'authorized' ? 'status-authorized' :
                                ($lead->status === 'disbursed' ? 'status-disbursed' :
                                ($lead->status === 'rejected' ? 'status-rejected' :
                                ($lead->status === 'approved' ? 'status-approved' : 'status-default')))
                            }}">
                            {{ ucfirst($lead->status) }}
                        </span>
                    </td>
                    <td>{{ $lead->created_at->format('d M Y') }}</td>
                    <td>{{ $lead->employee->name ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No leads found for selected filter.</p>
@endif



<!-- Task Management Section -->
<div class="task-management-section" data-aos="fade-up" data-aos-delay="400">
   <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Task Management</h5>
        <button class="btn btn-dark">
            <i class="fas fa-file-export me-1"></i> Export Tasks
        </button>
    </div>

    <div class="card-body">
        <!-- Filter Buttons -->
        <div class="mb-3">
            <button class="btn btn-primary task-filter active" data-filter="all">All Team</button>
            <button class="btn btn-outline-primary task-filter" data-filter="individual">Individual</button>
        </div>

        <!-- Task List -->
        <div id="task-list">
            @foreach ($tasks as $task)
                <div class="card mb-3 task-item" data-type="{{ $task->target_type }}">
                    <div class="card-body">
                        <h6 class="card-title fw-bold">{{ $task->title }}</h6>

                       <p class="card-subtitle mb-1 text-muted">
    Assigned to:
    @if($task->target_type === 'all')
       All Team
    @else
        @php
            $userIds = $task->notifications->pluck('user_id')->unique()->implode(', ');
        @endphp
        {{ $userIds ?: 'N/A' }}
    @endif
    &nbsp; | &nbsp;
    Due: {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
</p>


                        <p class="mb-2">{{ $task->description }}</p>

                        <span class="badge
                            @if($task->status == 'completed') bg-success
                            @elseif($task->status == 'in_progress') bg-info
                            @else bg-warning @endif
                        ">
                            {{ strtoupper($task->status) }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>


</div>





   <!-- Team Members Overview -->
<div class="team-overview-section" data-aos="fade-up" data-aos-delay="500">
    <div class="team-card">
        <div class="team-header">
            <h3>Team Members Overview</h3>
            <div class="team-actions">
                <button class="btn btn-secondary" onclick="viewAllMembers()">
                    <i class="fas fa-users"></i>
                    View All
                </button>
            </div>
        </div>
        <div class="team-content">
            <div class="team-grid">
                @foreach($employees as $employee)
                <div class="member-card" onclick="viewMemberDetails({{ $employee->id }})">
                    <div class="member-avatar">
                        <img id="employeeAvatar" src="{{ $employee->profile_photo ? asset($employee->profile_photo) : asset('images/placeholder.svg') }}" alt="{{ $employee->name }}">

                        <div class="member-status online"></div>
                    </div>
                    <div class="member-info">
                        <div class="member-name">{{ $employee->name }}</div>
                        <div class="member-role">{{ $employee->designation ?? 'N/A' }}</div>
                        <div class="member-stats">
                            <div class="stat-item">
                                <span class="stat-label">Performance</span>
                                <span class="stat-value">{{ $employee->performance_rate ?? '0' }}%</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Attendance</span>
                                <span class="stat-value">{{ $employee->attendance_rate ?? '0' }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Employee Details Modal -->
<div class="modal-overlay" id="employeeModal">
    <div class="modal-content large">
        <div class="modal-header">
            <h2>Employee Details</h2>
            <button class="modal-close" onclick="closeEmployeeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="employee-details">
                <div class="employee-profile">
                    <div class="profile-avatar">
                       <img id="employeeAvatar" src="{{ $employee->profile_photo ? asset($employee->profile_photo) : asset('images/placeholder.svg') }}" alt="{{ $employee->name }}">

                    </div>
                    <div class="profile-info">
                        <h3 id="employeeName">--</h3>
                        <p id="employeeRole">--</p>
                        <p id="employeeId">--</p>
                    </div>
                </div>

                <div class="employee-tabs">
                    <button class="tab-btn active" onclick="showTab('performance')">Performance</button>
                    <button class="tab-btn" onclick="showTab('attendance')">Attendance</button>
                    <button class="tab-btn" onclick="showTab('tasks')">Tasks</button>
                    <button class="tab-btn" onclick="showTab('overview')">Overview</button>
                </div>

                <div class="tab-content">
                    <div id="performance" class="tab-pane active">
                        <div class="performance-metrics">
                            <div class="metric-card">
                                <div class="metric-value" id="overallPerformance">--%</div>
                                <div class="metric-label">Overall Performance</div>
                            </div>
                            <div class="metric-card">
                                <div class="metric-value" id="leadsCompleted">--</div>
                                <div class="metric-label">Leads Completed</div>
                            </div>
                            <div class="metric-card">
                                <div class="metric-value" id="revenueGenerated">--</div>
                                <div class="metric-label">Revenue Generated</div>
                            </div>
                        </div>
                        <canvas id="employeePerformanceChart"></canvas>
                    </div>

                    <div id="attendance" class="tab-pane">
                        <div class="attendance-summary">
                            <div class="summary-card">
                                <div class="summary-value" id="attendanceRate">--%</div>
                                <div class="summary-label">Attendance Rate</div>
                            </div>
                            <div class="summary-card">
                                <div class="summary-value" id="presentDays">--</div>
                                <div class="summary-label">Present Days</div>
                            </div>
                            <div class="summary-card">
                                <div class="summary-value" id="absentDays">--</div>
                                <div class="summary-label">Absent Days</div>
                            </div>
                        </div>
                        <canvas id="employeeAttendanceChart"></canvas>
                    </div>

                    <div id="tasks" class="tab-pane">
                        <div class="task-summary">
                            <div class="task-stat">
                                <span class="stat-number" id="totalTasks">--</span>
                                <span class="stat-label">Total Tasks</span>
                            </div>
                            <div class="task-stat">
                                <span class="stat-number" id="completedTasks">--</span>
                                <span class="stat-label">Completed</span>
                            </div>
                            <div class="task-stat">
                                <span class="stat-number" id="inProgressTasks">--</span>
                                <span class="stat-label">In Progress</span>
                            </div>
                        </div>
                    </div>

                    <div id="overview" class="tab-pane">
                        <div class="overview-grid">
                            <div class="overview-item">
                                <label>Email:</label>
                                <span id="employeeEmail">--</span>
                            </div>
                            <div class="overview-item">
                                <label>Phone:</label>
                                <span id="employeePhone">--</span>
                            </div>
                            <div class="overview-item">
                                <label>Department:</label>
                                <span id="employeeDepartment">--</span>
                            </div>
                            <div class="overview-item">
                                <label>Join Date:</label>
                                <span id="employeeJoinDate">--</span>
                            </div>
                            <div class="overview-item">
                                <label>Manager:</label>
                                <span id="employeeManager">--</span>
                            </div>
                            <div class="overview-item">
                                <label>Location:</label>
                                <span id="employeeLocation">--</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
    </div>
   @include('TeamLead.Components.script');
   <script>
     function toggleCustomRange(value) {
        document.getElementById('custom-range').style.display = (value === 'custom') ? 'block' : 'none';
    }

     document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.task-filter').forEach(button => {
        button.addEventListener('click', function () {
            document.querySelectorAll('.task-filter').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            const filterType = this.getAttribute('data-filter');

            document.querySelectorAll('.task-item').forEach(task => {
                const taskType = task.getAttribute('data-type');
                task.style.display = (filterType === taskType) ? 'block' : 'none';
            });
        });
    });
});


    function viewMemberDetails(userId) {
       fetch(`/team-lead/employee/details/${userId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('employeeName').textContent = data.name;
                document.getElementById('employeeRole').textContent = data.designation;
                document.getElementById('employeeId').textContent = 'EMP' + data.id;
                const avatar = document.getElementById('employeeAvatar');
            if (avatar) {
                  avatar.src = data.profile_photo || '/images/placeholder.svg';
                 }

                document.getElementById('overallPerformance').textContent = data.performance_rate + '%';
                document.getElementById('leadsCompleted').textContent = data.leads_completed;
                document.getElementById('revenueGenerated').textContent = data.revenue_generated;

                document.getElementById('attendanceRate').textContent = data.attendance_rate + '%';
                document.getElementById('presentDays').textContent = data.present_days;
                document.getElementById('absentDays').textContent = data.absent_days;

                document.getElementById('totalTasks').textContent = data.total_tasks;
                document.getElementById('completedTasks').textContent = data.completed_tasks;
                document.getElementById('inProgressTasks').textContent = data.in_progress_tasks;

                document.getElementById('employeeEmail').textContent = data.email;
                document.getElementById('employeePhone').textContent = data.phone;
                document.getElementById('employeeDepartment').textContent = data.department;
                document.getElementById('employeeJoinDate').textContent = data.join_date;
                document.getElementById('employeeManager').textContent = data.manager;
                document.getElementById('employeeLocation').textContent = data.location;

                document.getElementById('employeeModal').classList.add('show');
            });
    }


   </script>
</body>
</html>
