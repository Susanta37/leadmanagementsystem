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
            max-width: 900px;
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
    <!-- Quick Stats Cards -->
<div class="stats-grid" data-aos="fade-up" data-aos-delay="100">
    <!-- All Leads -->
    <div class="stat-card all-leads">
        <div class="stat-header">
            <div class="stat-icon">
                <i class="fas fa-database"></i>
            </div>
            <div class="stat-menu">
                <button class="menu-btn" onclick="toggleMenu('allLeadsMenu')">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <div class="dropdown-menu1" id="allLeadsMenu">
                    <a href="#" onclick="viewAllLeads()">View All</a>
                    <a href="#" onclick="exportLeads()">Export</a>
                </div>
            </div>
        </div>
        <div class="stat-content">
            <div class="stat-value" data-target="{{ $allLeads ?? 0 }}">{{ $allLeads ?? 0 }}</div>
            <div class="stat-label">All Leads</div>
        </div>
    </div>

    <!-- New Leads -->
    <div class="stat-card new-leads">
        <div class="stat-header">
            <div class="stat-icon">
                <i class="fas fa-plus-circle"></i>
            </div>
            <div class="stat-menu">
                <button class="menu-btn" onclick="toggleMenu('newLeadsMenu')">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <div class="dropdown-menu1" id="newLeadsMenu">
                    <a href="#" onclick="viewNewLeads()">View New</a>
                    <a href="#" onclick="exportNewLeads()">Export</a>
                </div>
            </div>
        </div>
        <div class="stat-content">
            <div class="stat-value" data-target="{{ $newLeads ?? 0 }}">{{ $newLeads ?? 0 }}</div>
            <div class="stat-label">New Leads</div>
        </div>
    </div>

    <!-- Approved Leads -->
    <div class="stat-card approved-leads">
        <div class="stat-header">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-menu">
                <button class="menu-btn" onclick="toggleMenu('approvedLeadsMenu')">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <div class="dropdown-menu1" id="approvedLeadsMenu">
                    <a href="#" onclick="viewApprovedLeads()">View Approved</a>
                    <a href="#" onclick="exportApprovedLeads()">Export</a>
                </div>
            </div>
        </div>
        <div class="stat-content">
            <div class="stat-value" data-target="{{ $approvedLeads ?? 0 }}">{{ $approvedLeads ?? 0 }}</div>
            <div class="stat-label">Approved Leads</div>
        </div>
    </div>

    <!-- Pending Leads -->
    <div class="stat-card pending-leads">
        <div class="stat-header">
            <div class="stat-icon">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="stat-menu">
                <button class="menu-btn" onclick="toggleMenu('pendingLeadsMenu')">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <div class="dropdown-menu1" id="pendingLeadsMenu">
                    <a href="#" onclick="viewPendingLeads()">View Pending</a>
                    <a href="#" onclick="exportPendingLeads()">Export</a>
                </div>
            </div>
        </div>
        <div class="stat-content">
            <div class="stat-value" data-target="{{ $pendingLeads ?? 0 }}">{{ $pendingLeads ?? 0 }}</div>
            <div class="stat-label">Pending Leads</div>
        </div>
    </div>

    <!-- Rejected Leads -->
    <div class="stat-card rejected-leads">
        <div class="stat-header">
            <div class="stat-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-menu">
                <button class="menu-btn" onclick="toggleMenu('rejectedLeadsMenu')">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <div class="dropdown-menu1" id="rejectedLeadsMenu">
                    <a href="#" onclick="viewRejectedLeads()">View Rejected</a>
                    <a href="#" onclick="exportRejectedLeads()">Export</a>
                </div>
            </div>
        </div>
        <div class="stat-content">
            <div class="stat-value" data-target="{{ $rejectedLeads ?? 0 }}">{{ $rejectedLeads ?? 0 }}</div>
            <div class="stat-label">Rejected Leads</div>
        </div>
    </div>

    <!-- Team Members -->
    <div class="stat-card team-members">
        <div class="stat-header">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-menu">
                <button class="menu-btn" onclick="toggleMenu('teamMembersMenu')">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <div class="dropdown-menu1" id="teamMembersMenu">
                    <a href="#" onclick="viewAllTeamMembers()">View All</a>
                    <a href="#" onclick="addNewMember()">Add Member</a>
                    <a href="#" onclick="exportTeamData()">Export</a>
                </div>
            </div>
        </div>
        <div class="stat-content">
            <div class="stat-value" data-target="{{ $teamMembers ?? 0 }}">{{ $teamMembers ?? 0 }}</div>
            <div class="stat-label">Team Members</div>
            <div class="stat-details">
                <span class="detail-item active">{{ $activeMembers ?? 0 }} Active</span>
                <span class="detail-item pending">{{ $onLeaveMembers ?? 0 }} On Leave</span>
                <span class="detail-item inactive">{{ $inactiveMembers ?? 0 }} Inactive</span>
            </div>
        </div>
        <div class="stat-chart">
            <canvas id="teamMembersChart" width="80" height="40"></canvas>
        </div>
    </div>
</div>

            <!-- Charts Section -->
            <div class="charts-section" data-aos="fade-up" data-aos-delay="200">
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
            </div>

            <!-- Data Tables Section -->
            <div class="tables-section" data-aos="fade-up" data-aos-delay="300">
                <!-- Team Present Details Table -->
                <div class="table-card">
                    <div class="table-header">
                        <h3>Today's Attendance Details</h3>
                        <div class="table-actions">
                            <button class="btn btn-primary" onclick="exportAttendance()">
                                <i class="fas fa-download"></i>
                                Export
                            </button>
                            <button class="btn btn-secondary" onclick="refreshAttendance()">
                                <i class="fas fa-refresh"></i>
                                Refresh
                            </button>
                        </div>
                    </div>
                    <div class="table-content">
                        <div class="table-wrapper">
                            <table class="data-table" id="attendanceTable">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Check In</th>
                                        <th>Check Out</th>
                                        <th>Hours</th>
                                        <th>Status</th>
                                        <th>Location</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="employee-info">
                                                <img src="/placeholder.svg?height=32&width=32" alt="Employee" class="employee-avatar">
                                                <div>
                                                    <div class="employee-name">John Doe</div>
                                                    <div class="employee-id">EMP001</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>09:15 AM</td>
                                        <td>06:30 PM</td>
                                        <td>9h 15m</td>
                                        <td><span class="status-badge approved">Approved</span></td>
                                        <td>Office</td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn-icon" onclick="viewEmployee('EMP001')" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn-icon" onclick="editAttendance('EMP001')" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="employee-info">
                                                <img src="/placeholder.svg?height=32&width=32" alt="Employee" class="employee-avatar">
                                                <div>
                                                    <div class="employee-name">Sarah Wilson</div>
                                                    <div class="employee-id">EMP002</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>09:30 AM</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td><span class="status-badge pending">Pending</span></td>
                                        <td>Remote</td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn-icon" onclick="viewEmployee('EMP002')" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn-icon" onclick="approveAttendance('EMP002')" title="Approve">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn-icon" onclick="rejectAttendance('EMP002')" title="Reject">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="employee-info">
                                                <img src="/placeholder.svg?height=32&width=32" alt="Employee" class="employee-avatar">
                                                <div>
                                                    <div class="employee-name">Mike Johnson</div>
                                                    <div class="employee-id">EMP003</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>10:45 AM</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td><span class="status-badge rejected">Rejected</span></td>
                                        <td>Office</td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn-icon" onclick="viewEmployee('EMP003')" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn-icon" onclick="viewRemarks('EMP003')" title="View Remarks">
                                                    <i class="fas fa-comment"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Leads Management Table -->
                <div class="table-card">
                    <div class="table-header">
                        <h3>Leads Management</h3>
                        <div class="table-actions">
                           <button class="btn btn-primary" onclick="openLeadModal()">
    <i class="fas fa-plus"></i>
    New Lead
</button>
                            <button class="btn btn-secondary" onclick="bulkActions()">
                                <i class="fas fa-tasks"></i>
                                Bulk Actions
                            </button>
                        </div>
                    </div>
                    <div class="table-content">
                        <div class="table-wrapper">
                            <table class="data-table" id="leadsTable">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                        </th>
                                        <th>Lead ID</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Assigned To</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="checkbox" class="row-select"></td>
                                        <td>LD001</td>
                                        <td>
                                            <div class="customer-info">
                                                <div class="customer-name">Rajesh Kumar</div>
                                                <div class="customer-phone">+91 98765 43210</div>
                                            </div>
                                        </td>
                                        <td>₹5,00,000</td>
                                        <td>
                                            <select class="status-select" onchange="updateLeadStatus('LD001', this.value)">
                                                <option value="pending">Pending</option>
                                                <option value="approved" selected>Approved</option>
                                                <option value="rejected">Rejected</option>
                                                <option value="processing">Processing</option>
                                            </select>
                                        </td>
                                        <td>John Doe</td>
                                        <td>2024-01-15</td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn-icon" onclick="viewLead('LD001')" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn-icon" onclick="editLead('LD001')" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn-icon" onclick="addRemarks('LD001')" title="Add Remarks">
                                                    <i class="fas fa-comment-plus"></i>
                                                </button>
                                                <button class="btn-icon" onclick="sendToOperations('LD001')" title="Send to Operations">
                                                    <i class="fas fa-paper-plane"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="row-select"></td>
                                        <td>LD002</td>
                                        <td>
                                            <div class="customer-info">
                                                <div class="customer-name">Priya Sharma</div>
                                                <div class="customer-phone">+91 87654 32109</div>
                                            </div>
                                        </td>
                                        <td>₹3,50,000</td>
                                        <td>
                                            <select class="status-select" onchange="updateLeadStatus('LD002', this.value)">
                                                <option value="pending" selected>Pending</option>
                                                <option value="approved">Approved</option>
                                                <option value="rejected">Rejected</option>
                                                <option value="processing">Processing</option>
                                            </select>
                                        </td>
                                        <td>Sarah Wilson</td>
                                        <td>2024-01-16</td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn-icon" onclick="viewLead('LD002')" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn-icon" onclick="editLead('LD002')" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn-icon" onclick="approveLead('LD002')" title="Approve">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn-icon" onclick="rejectLead('LD002')" title="Reject">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Task Management Section -->
            <div class="task-management-section" data-aos="fade-up" data-aos-delay="400">
                <div class="task-card">
                    <div class="task-header">
                        <h3>Task Management</h3>
                        <button class="btn btn-primary" onclick="openTaskModal()">
                            <i class="fas fa-plus"></i>
                            Create Task
                        </button>
                    </div>
                    <div class="task-content">
                        <div class="task-filters">
                            <button class="task-filter active" data-filter="all">All Tasks</button>
                            <button class="task-filter" data-filter="individual">Individual</button>
                            <button class="task-filter" data-filter="team">Team Tasks</button>
                            <button class="task-filter" data-filter="overdue">Overdue</button>
                        </div>
                        
                        <div class="task-list">
                            <div class="task-item" data-type="individual">
                                <div class="task-info">
                                    <div class="task-title">Complete loan documentation review</div>
                                    <div class="task-meta">
                                        <span class="task-assignee">Assigned to: John Doe</span>
                                        <span class="task-due">Due: Jan 20, 2024</span>
                                    </div>
                                </div>
                                <div class="task-status">
                                    <span class="status-badge in-progress">In Progress</span>
                                </div>
                                <div class="task-actions">
                                    <button class="btn-icon" onclick="editTask('TSK001')" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-icon" onclick="deleteTask('TSK001')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="task-item" data-type="team">
                                <div class="task-info">
                                    <div class="task-title">Monthly sales target achievement</div>
                                    <div class="task-meta">
                                        <span class="task-assignee">Assigned to: Sales Team</span>
                                        <span class="task-due">Due: Jan 31, 2024</span>
                                    </div>
                                </div>
                                <div class="task-status">
                                    <span class="status-badge pending">Pending</span>
                                </div>
                                <div class="task-actions">
                                    <button class="btn-icon" onclick="editTask('TSK002')" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-icon" onclick="deleteTask('TSK002')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="task-item" data-type="individual">
                                <div class="task-info">
                                    <div class="task-title">Customer follow-up calls</div>
                                    <div class="task-meta">
                                        <span class="task-assignee">Assigned to: Sarah Wilson</span>
                                        <span class="task-due">Due: Jan 18, 2024</span>
                                    </div>
                                </div>
                                <div class="task-status">
                                    <span class="status-badge overdue">Overdue</span>
                                </div>
                                <div class="task-actions">
                                    <button class="btn-icon" onclick="editTask('TSK003')" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-icon" onclick="deleteTask('TSK003')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
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
                            <button class="btn btn-primary" onclick="addNewEmployee()">
                                <i class="fas fa-user-plus"></i>
                                Add Employee
                            </button>
                            <button class="btn btn-secondary" onclick="viewAllMembers()">
                                <i class="fas fa-users"></i>
                                View All
                            </button>
                        </div>
                    </div>
                    <div class="team-content">
                        <div class="team-grid">
                            <div class="member-card" onclick="viewMemberDetails('EMP001')">
                                <div class="member-avatar">
                                    <img src="/placeholder.svg?height=60&width=60" alt="John Doe">
                                    <div class="member-status online"></div>
                                </div>
                                <div class="member-info">
                                    <div class="member-name">John Doe</div>
                                    <div class="member-role">Senior Sales Executive</div>
                                    <div class="member-stats">
                                        <div class="stat-item">
                                            <span class="stat-label">Performance</span>
                                            <span class="stat-value">92%</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-label">Attendance</span>
                                            <span class="stat-value">95%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="member-card" onclick="viewMemberDetails('EMP002')">
                                <div class="member-avatar">
                                    <img src="/placeholder.svg?height=60&width=60" alt="Sarah Wilson">
                                    <div class="member-status online"></div>
                                </div>
                                <div class="member-info">
                                    <div class="member-name">Sarah Wilson</div>
                                    <div class="member-role">Marketing Specialist</div>
                                    <div class="member-stats">
                                        <div class="stat-item">
                                            <span class="stat-label">Performance</span>
                                            <span class="stat-value">88%</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-label">Attendance</span>
                                            <span class="stat-value">92%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="member-card" onclick="viewMemberDetails('EMP003')">
                                <div class="member-avatar">
                                    <img src="/placeholder.svg?height=60&width=60" alt="Mike Johnson">
                                    <div class="member-status away"></div>
                                </div>
                                <div class="member-info">
                                    <div class="member-name">Mike Johnson</div>
                                    <div class="member-role">Operations Executive</div>
                                    <div class="member-stats">
                                        <div class="stat-item">
                                            <span class="stat-label">Performance</span>
                                            <span class="stat-value">85%</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-label">Attendance</span>
                                            <span class="stat-value">89%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="member-card" onclick="viewMemberDetails('EMP004')">
                                <div class="member-avatar">
                                    <img src="/placeholder.svg?height=60&width=60" alt="Emma Davis">
                                    <div class="member-status offline"></div>
                                </div>
                                <div class="member-info">
                                    <div class="member-name">Emma Davis</div>
                                    <div class="member-role">Customer Support</div>
                                    <div class="member-stats">
                                        <div class="stat-item">
                                            <span class="stat-label">Performance</span>
                                            <span class="stat-value">90%</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-label">Attendance</span>
                                            <span class="stat-value">94%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Task Creation Modal -->
        <div class="modal-overlay" id="taskModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Create New Task</h2>
                    <button class="modal-close" onclick="closeTaskModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="taskForm">
                        <div class="form-group">
                            <label for="taskTitle">Task Title</label>
                            <input type="text" id="taskTitle" class="form-control" placeholder="Enter task title" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="taskDescription">Description</label>
                            <textarea id="taskDescription" class="form-control" rows="3" placeholder="Enter task description"></textarea>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="taskType">Assignment Type</label>
                                <select id="taskType" class="form-control" onchange="toggleAssignmentOptions()">
                                    <option value="individual">Individual</option>
                                    <option value="team">Whole Team</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="taskPriority">Priority</label>
                                <select id="taskPriority" class="form-control">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group" id="individualAssignment">
                            <label for="assignedTo">Assign To</label>
                            <select id="assignedTo" class="form-control">
                                <option value="">Select Employee</option>
                                <option value="EMP001">John Doe</option>
                                <option value="EMP002">Sarah Wilson</option>
                                <option value="EMP003">Mike Johnson</option>
                                <option value="EMP004">Emma Davis</option>
                            </select>
                        </div>
                        
                        <div class="form-group" id="teamAssignment" style="display: none;">
                            <label for="assignedTeam">Assign To Team</label>
                            <select id="assignedTeam" class="form-control">
                                <option value="">Select Team</option>
                                <option value="sales">Sales Team</option>
                                <option value="marketing">Marketing Team</option>
                                <option value="operations">Operations Team</option>
                            </select>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="taskDueDate">Due Date</label>
                                <input type="date" id="taskDueDate" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="taskDueTime">Due Time</label>
                                <input type="time" id="taskDueTime" class="form-control">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeTaskModal()">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="createTask()">Create Task</button>
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
                                <img src="/placeholder.svg?height=100&width=100" alt="Employee" id="employeeAvatar">
                            </div>
                            <div class="profile-info">
                                <h3 id="employeeName">John Doe</h3>
                                <p id="employeeRole">Senior Sales Executive</p>
                                <p id="employeeId">EMP001</p>
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
                                        <div class="metric-value">92%</div>
                                        <div class="metric-label">Overall Performance</div>
                                    </div>
                                    <div class="metric-card">
                                        <div class="metric-value">156</div>
                                        <div class="metric-label">Leads Converted</div>
                                    </div>
                                    <div class="metric-card">
                                        <div class="metric-value">₹25L</div>
                                        <div class="metric-label">Revenue Generated</div>
                                    </div>
                                </div>
                                <canvas id="employeePerformanceChart"></canvas>
                            </div>
                            
                            <div id="attendance" class="tab-pane">
                                <div class="attendance-summary">
                                    <div class="summary-card">
                                        <div class="summary-value">95%</div>
                                        <div class="summary-label">Attendance Rate</div>
                                    </div>
                                    <div class="summary-card">
                                        <div class="summary-value">22</div>
                                        <div class="summary-label">Present Days</div>
                                    </div>
                                    <div class="summary-card">
                                        <div class="summary-value">2</div>
                                        <div class="summary-label">Absent Days</div>
                                    </div>
                                </div>
                                <canvas id="employeeAttendanceChart"></canvas>
                            </div>
                            
                            <div id="tasks" class="tab-pane">
                                <div class="task-summary">
                                    <div class="task-stat">
                                        <span class="stat-number">15</span>
                                        <span class="stat-label">Total Tasks</span>
                                    </div>
                                    <div class="task-stat">
                                        <span class="stat-number">12</span>
                                        <span class="stat-label">Completed</span>
                                    </div>
                                    <div class="task-stat">
                                        <span class="stat-number">3</span>
                                        <span class="stat-label">In Progress</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="overview" class="tab-pane">
                                <div class="overview-grid">
                                    <div class="overview-item">
                                        <label>Email:</label>
                                        <span>john.doe@kredipal.com</span>
                                    </div>
                                    <div class="overview-item">
                                        <label>Phone:</label>
                                        <span>+91 98765 43210</span>
                                    </div>
                                    <div class="overview-item">
                                        <label>Department:</label>
                                        <span>Sales</span>
                                    </div>
                                    <div class="overview-item">
                                        <label>Join Date:</label>
                                        <span>Jan 15, 2023</span>
                                    </div>
                                    <div class="overview-item">
                                        <label>Manager:</label>
                                        <span>Team Lead</span>
                                    </div>
                                    <div class="overview-item">
                                        <label>Location:</label>
                                        <span>Mumbai Office</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Lead Modal -->
<div class="modal-overlay" id="leadModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Create New Lead</h2>
            <button class="modal-close" onclick="closeLeadModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="leadForm">
            <div class="modal-body">
                <div class="form-group">
                    <label for="employee_id">Employee</label>
                    <input type="text" id="employee_id" class="form-control" placeholder="Employee ID">
                </div>
                <div class="form-group">
                    <label for="team_lead_id">Team Lead</label>
                    <input type="text" id="team_lead_id" class="form-control" placeholder="Team Lead ID">
                </div>
                <div class="form-group">
                    <label for="name">Lead Name</label>
                    <input type="text" id="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Email (optional)</label>
                    <input type="email" id="email" class="form-control">
                </div>
                <div class="form-group">
                    <label for="dob">Date of Birth (optional)</label>
                    <input type="date" id="dob" class="form-control">
                </div>
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="company_name">Company Name (optional)</label>
                    <input type="text" id="company_name" class="form-control">
                </div>
                <div class="form-group">
                    <label for="lead_amount">Lead Amount</label>
                    <input type="number" step="0.01" id="lead_amount" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="salary">Salary (optional)</label>
                    <input type="number" step="0.01" id="salary" class="form-control">
                </div>
                <div class="form-group">
                    <label for="success_percentage">Success Percentage</label>
                    <input type="number" id="success_percentage" class="form-control" min="0" max="100" required>
                </div>
                <div class="form-group">
                    <label for="expected_month">Expected Month</label>
                    <input type="text" id="expected_month" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="remarks">Remarks (optional)</label>
                    <textarea id="remarks" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" class="form-control" required>
                        <option value="pending" selected>Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeLeadModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Lead</button>
            </div>
        </form>
    </div>
</div>
    </div>
   @include('TeamLead.Components.script');
</body>
</html>