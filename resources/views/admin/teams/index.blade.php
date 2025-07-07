<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teams - Lead Management System</title>
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

        .teams-container {
            padding: 32px;
            max-width: 1600px;
            margin: 0 auto;
        }

        .page-header {
            margin-bottom: 32px;
            animation: fadeInDown 0.6s ease-out;
        }

        .page-title {
            font-size: 32px;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 8px;
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

        .teams-layout {
            display: grid;
            grid-template-columns: 400px 1fr;
            gap: 32px;
            animation: slideInUp 0.8s ease-out;
        }

        /* Employee Form Section */
        .employee-form-section {
            background: white;
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            height: fit-content;
            position: sticky;
            top: 32px;
        }

        .form-header {
            margin-bottom: 24px;
            text-align: center;
        }

        .form-title {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .form-subtitle {
            font-size: 14px;
            color: #6b7280;
        }

        .employee-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-label {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .required {
            color: #ef4444;
        }

        .form-input {
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #f9fafb;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            background: white;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 80px;
        }

        .photo-upload {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .photo-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 3px solid #e5e7eb;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f3f4f6;
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .photo-preview:hover {
            border-color: #3b82f6;
            transform: scale(1.05);
        }

        .photo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-placeholder {
            color: #9ca3af;
            font-size: 32px;
        }

        .photo-input {
            display: none;
        }

        .photo-upload-btn {
            padding: 8px 16px;
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 12px;
            color: #4b5563;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .photo-upload-btn:hover {
            background: #e5e7eb;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 8px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            flex: 1;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #4b5563;
            border: 1px solid #d1d5db;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        /* Employees Table Section */
        .employees-table-section {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .table-header {
            padding: 24px 32px;
            border-bottom: 1px solid #e5e7eb;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), rgba(29, 78, 216, 0.05));
        }

        .table-title {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .table-subtitle {
            font-size: 14px;
            color: #6b7280;
        }

        .table-controls {
            padding: 20px 32px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .search-box {
            position: relative;
            flex: 1;
            max-width: 300px;
        }

        .search-input {
            width: 100%;
            padding: 10px 16px 10px 40px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            background: #f9fafb;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #3b82f6;
            background: white;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .table-stats {
            display: flex;
            align-items: center;
            gap: 16px;
            font-size: 14px;
            color: #6b7280;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .stat-value {
            font-weight: 600;
            color: #3b82f6;
        }

        .employees-table-wrapper {
            overflow-x: auto;
        }

        .employees-table {
            width: 100%;
            border-collapse: collapse;
        }

        .employees-table th,
        .employees-table td {
            padding: 16px 20px;
            text-align: left;
            border-bottom: 1px solid #f3f4f6;
        }

        .employees-table th {
            background: #f9fafb;
            font-weight: 600;
            color: #374151;
            font-size: 14px;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .employees-table td {
            font-size: 14px;
            color: #4b5563;
        }

        .employees-table tr:hover {
            background: rgba(59, 130, 246, 0.02);
        }

        .employee-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .employee-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #e5e7eb;
            flex-shrink: 0;
        }

        .employee-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .employee-avatar-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .employee-details {
            flex: 1;
        }

        .employee-name {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 2px;
        }

        .employee-email {
            font-size: 12px;
            color: #6b7280;
        }

        .designation-badge {
            padding: 4px 12px;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(29, 78, 216, 0.1));
            color: #1d4ed8;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .contact-info {
            display: flex;
            align-items: center;
            gap: 4px;
            color: #6b7280;
            font-size: 13px;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .btn-edit {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .btn-edit:hover {
            background: rgba(59, 130, 246, 0.2);
            transform: scale(1.1);
        }

        .btn-delete {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .btn-delete:hover {
            background: rgba(239, 68, 68, 0.2);
            transform: scale(1.1);
        }

        .empty-state {
            padding: 64px 32px;
            text-align: center;
            color: #6b7280;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .empty-state-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .empty-state-description {
            font-size: 14px;
        }

        /* Success Modal */
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
            background: white;
            border-radius: 20px;
            padding: 32px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            transform: scale(0.9);
            transition: transform 0.3s ease;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .modal-overlay.active .modal-content {
            transform: scale(1);
        }

        .modal-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #10b981, #059669);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            color: white;
            font-size: 32px;
        }

        .modal-title {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 12px;
        }

        .modal-message {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 24px;
            line-height: 1.6;
        }

        .modal-close-btn {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            border: none;
            padding: 12px 32px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .modal-close-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }



        .switch {
  position: relative;
  display: inline-block;
  width: 46px;
  height: 24px;
}
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  transition: .3s;
  border-radius: 34px;
}
.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: .3s;
  border-radius: 50%;
}
input:checked + .slider {
  background-color: #28a745;
}
input:checked + .slider:before {
  transform: translateX(22px);
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

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .teams-layout {
                grid-template-columns: 350px 1fr;
                gap: 24px;
            }
        }

        @media (max-width: 968px) {
            .teams-layout {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .employee-form-section {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }

            .teams-container {
                padding: 20px;
            }

            .table-controls {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }

            .search-box {
                max-width: none;
            }

            .employees-table th,
            .employees-table td {
                padding: 12px 16px;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    @include('TeamLead.Components.sidebar')

    <div class="main-content">
        @include('TeamLead.Components.header', ['title' => 'Teams', 'subtitle' => 'Manage your team members and employees'])

        <div class="teams-container">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Team Management</h1>
                <p class="page-subtitle">Add, edit, and manage your team members efficiently</p>
            </div>

            <!-- Main Layout -->
            <div class="teams-layout">
                <!-- Employee Form Section -->
                <div class="employee-form-section">
                    <div class="form-header">
                        <h2 class="form-title" id="formTitle">Add New Employee</h2>
                        <p class="form-subtitle">Fill in the details to add a new team member</p>
                    </div>

                    <form class="employee-form" id="employeeForm" method="POST" action="{{ route('team_lead.employees.store') }}" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" id="employeeId" name="id">

                        <!-- Profile Photo -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-camera"></i>
                                Profile Photo <span class="text-muted">(Optional)</span>
                            </label>
                            <div class="photo-upload">
                                <div class="photo-preview" onclick="document.getElementById('photoInput').click()">
                                    <div class="photo-placeholder" id="photoPlaceholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <img id="photoPreview" style="display: none;" alt="Preview">
                                </div>
                                <input type="file" id="photoInput" class="photo-input" accept="image/*" onchange="handlePhotoUpload(event)">
                                <button type="button" class="photo-upload-btn" onclick="document.getElementById('photoInput').click()">
                                    <i class="fas fa-upload"></i>
                                    Choose Photo
                                </button>
                            </div>
                        </div>

                        <!-- Name -->
                        <div class="form-group">
                            <label for="employeeName" class="form-label">
                                <i class="fas fa-user"></i>
                                Full Name <span class="required">*</span>
                            </label>
                            <input type="text" id="employeeName" name="name" class="form-input" placeholder="Enter full name" required>
                        </div>

                        <!-- Designation -->
                        <div class="form-group">
                            <label for="employeeDesignation" class="form-label">
                                <i class="fas fa-briefcase"></i>
                                Designation <span class="required">*</span>
                            </label>
                            <input type="text" id="employeeDesignation" name="designation" class="form-input" placeholder="Enter designation" required>
                        </div>

  <!-- Employee Role -->
<div class="form-group">
    <label for="employeeRole" class="form-label">
        <i class="fas fa-user-tag"></i>
        Role <span class="required"></span>
    </label>
    <select id="employeeRole" name="employee_role" class="form-input" >
        <option value="">-- Select Role --</option>
        <option value="developer">Developer</option>
        <option value="designer">Designer</option>
        <option value="tester">Tester</option>
        <option value="hr">HR</option>
        <option value="manager">Manager</option>
    </select>
</div>



                        <!-- Email -->
                        <div class="form-group">
                            <label for="employeeEmail" class="form-label">
                                <i class="fas fa-envelope"></i>
                                Email Address <span class="required">*</span>
                            </label>
                            <input type="email" id="employeeEmail" name="email" class="form-input" placeholder="Enter email address" required>
                        </div>

                        <!-- Phone -->
                        <div class="form-group">
                            <label for="employeePhone" class="form-label">
                                <i class="fas fa-phone"></i>
                                Phone Number <span class="required">*</span>
                            </label>
                            <input type="tel" id="employeePhone" name="phone" class="form-input" placeholder="Enter phone number" required>
                        </div>

                        <!-- Address -->
                        <div class="form-group">
                            <label for="employeeAddress" class="form-label">
                                <i class="fas fa-map-marker-alt"></i>
                                Address <span class="text-muted">(Optional)</span>
                            </label>
                            <textarea id="employeeAddress" name="address" class="form-input form-textarea" placeholder="Enter address" rows="3"></textarea>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-plus"></i>
                                Add Employee
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="resetForm()">
    <i class="fas fa-times"></i>
    Cancel
</button>
                        </div>
                    </form>
                </div>

                <!-- Employees Table Section -->
                <div class="employees-table-section">
                    <div class="table-header">
                        <h2 class="table-title">Team Members</h2>
                        <p class="table-subtitle">Manage and view all team members</p>
                    </div>

                    <div class="table-controls">
                        <div class="search-box">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="search-input" placeholder="Search employees..." id="searchInput" oninput="filterEmployees()">
                        </div>
                        <div class="table-stats">
                            <div class="stat-item">
                                <i class="fas fa-users"></i>
                                <span>Total: <span class="stat-value" id="totalEmployees">0</span></span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-user-check"></i>
                                <span>Active: <span class="stat-value" id="activeEmployees">0</span></span>
                            </div>
                        </div>
                    </div>

                    <div class="employees-table-wrapper">
                        <table class="employees-table">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Designation</th>
                                    <th>Contact</th>
                                    <th>Address</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="employeesTableBody">

   @forelse($employees as $employee)
    <tr>
        <!-- Employee Info -->
        <td>
            <div class="employee-info">
                <div class="employee-avatar">
                    @if($employee->profile_photo)
                        <img src="{{ asset('storage/' . $employee->profile_photo) }}" alt="{{ $employee->name }}">
                    @else
                        <div class="employee-avatar-placeholder">
                            {{ strtoupper(substr($employee->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="employee-details">
                    <div class="employee-name">{{ $employee->name }}</div>
                    <div class="employee-email">{{ $employee->email }}</div>
                </div>
            </div>
        </td>

        <!-- Designation -->
        <td>
            <span class="badge bg-primary text-capitalize">{{ $employee->designation }}</span>
        </td>

        <!-- Phone -->
        <td>
            <i class="fas fa-phone"></i> {{ $employee->phone }}
        </td>

        <!-- Address -->
        <td>
            <i class="fas fa-map-marker-alt"></i> {{ $employee->address ?? 'Not provided' }}
        </td>

        <!-- Status Badge -->
        <td>
            @if($employee->deleted_at)
                <span class="badge bg-danger">Inactive</span>
            @else
                <span class="badge bg-success">Active</span>
            @endif
        </td>

      <!-- Actions -->

<td>
    <div class="d-flex align-items-center gap-2">
        <!-- Edit Button -->
        <button type="button" class="btn btn-sm btn-warning" onclick="editEmployee({{ $employee->id }})" title="Edit">
            <i class="fas fa-edit"></i>
        </button>

        <!-- Active/Inactive Toggle -->
        <form action="{{ $employee->deleted_at
            ? route('team_lead.employees.activate', $employee->id)
            : route('team_lead.employees.deactivate', $employee->id)
        }}" method="POST" class="toggle-form m-0 p-0">
            @csrf
            <label class="switch mb-0">
                <input type="checkbox" onchange="this.form.submit()" {{ !$employee->deleted_at ? 'checked' : '' }}>
                <span class="slider round"></span>
            </label>
        </form>
    </div>
</td>


    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center">No employees found.</td>
    </tr>
@endforelse




                            </tbody>
                        </table>

                        <!-- Empty State -->
                        <div class="empty-state" id="emptyState">
                            <div class="empty-state-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="empty-state-title">No employees found</div>
                            <div class="empty-state-description">Start by adding your first team member using the form on the left</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal-overlay" id="successModal">
        <div class="modal-content">
            <div class="modal-icon">
                <i class="fas fa-check"></i>
            </div>
            <h3 class="modal-title">Employee Created Successfully!</h3>
            <p class="modal-message">
                The employee has been added to your team. A welcome email with login credentials has been sent to their registered email address.
            </p>
            <button class="modal-close-btn" onclick="closeSuccessModal()">
                <i class="fas fa-check"></i>
                Got it!
            </button>
        </div>
    </div>

    <script>

       let employees = @json($employees ?? []);
        let filteredEmployees = [...employees];
        let editingEmployeeId = null;
        let nextEmployeeId = 4;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            renderEmployeesTable();
            updateStats();

            // Form submission
            document.getElementById('employeeForm').addEventListener('submit', handleFormSubmit);
        });

        // Handle form submission
        function handleFormSubmit(e) {
            e.preventDefault();

            const formData = new FormData(e.target);
            const employeeData = {
                name: formData.get('name'),
                designation: formData.get('designation'),
                email: formData.get('email'),
                phone: formData.get('phone'),
                address: formData.get('address') || 'Not provided',
                photo: document.getElementById('photoPreview').src || null
            };

            if (editingEmployeeId) {
                // Update existing employee
                updateEmployee(editingEmployeeId, employeeData);
            } else {
                // Create new employee
                createEmployee(employeeData);
            }
        }

        // Create new employee
        function createEmployee(employeeData) {
            const newEmployee = {
                id: nextEmployeeId++,
                ...employeeData,
                createdAt: new Date()
            };

            employees.push(newEmployee);
            filteredEmployees = [...employees];

            renderEmployeesTable();
            updateStats();
            resetForm();
            showSuccessModal();
        }

        // Update existing employee
        function updateEmployee(id, employeeData) {
            const index = employees.findIndex(emp => emp.id === id);
            if (index !== -1) {
                employees[index] = { ...employees[index], ...employeeData };
                filteredEmployees = [...employees];

                renderEmployeesTable();
                updateStats();
                resetForm();
                showNotification('Employee updated successfully!', 'success');
            }
        }

        // Edit employee
        function editEmployee(id) {
            const employee = employees.find(emp => emp.id === id);
            if (!employee) return;

            editingEmployeeId = id;

            // Populate form
            document.getElementById('employeeId').value = employee.id;
            document.getElementById('employeeName').value = employee.name;
            document.getElementById('employeeDesignation').value = employee.designation;
            document.getElementById('employeeEmail').value = employee.email;
            document.getElementById('employeePhone').value = employee.phone;
            document.getElementById('employeeAddress').value = employee.address === 'Not provided' ? '' : employee.address;

            // Update form title and button
            document.getElementById('formTitle').textContent = 'Edit Employee';
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save"></i> Update Employee';

            // Handle photo
            if (employee.photo) {
                document.getElementById('photoPreview').src = employee.photo;
                document.getElementById('photoPreview').style.display = 'block';
                document.getElementById('photoPlaceholder').style.display = 'none';
            }

            // Scroll to form
            document.querySelector('.employee-form-section').scrollIntoView({ behavior: 'smooth' });
        }

        // Delete employee
        function deleteEmployee(id) {
            const employee = employees.find(emp => emp.id === id);
            if (!employee) return;

            if (confirm(`Are you sure you want to delete ${employee.name}? This action cannot be undone.`)) {
                employees = employees.filter(emp => emp.id !== id);
                filteredEmployees = [...employees];

                renderEmployeesTable();
                updateStats();
                showNotification('Employee deleted successfully!', 'success');
            }
        }

        //reset form
function resetForm() {
    console.log('resetForm called'); // For debugging
    editingEmployeeId = null;

    const form = document.getElementById('employeeForm');
    const idField = document.getElementById('employeeId');
    const photoPreview = document.getElementById('photoPreview');
    const photoPlaceholder = document.getElementById('photoPlaceholder');
    const formTitle = document.getElementById('formTitle');
    const submitBtn = document.getElementById('submitBtn');

    if (!form || !idField || !photoPreview || !photoPlaceholder || !formTitle || !submitBtn) {
        console.error('One or more form elements not found');
        showNotification('Error resetting form: Elements not found', 'error');
        return;
    }

    form.reset();
    idField.value = '';
    photoPreview.style.display = 'none';
    photoPreview.src = '';
    photoPlaceholder.style.display = 'flex';
    formTitle.textContent = 'Add New Employee';
    submitBtn.innerHTML = '<i class="fas fa-plus"></i> Add Employee';
}


// Handle photo upload
        function handlePhotoUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            // Validate file type
            if (!file.type.startsWith('image/')) {
                showNotification('Please select a valid image file', 'error');
                return;
            }

            // Validate file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                showNotification('Image size should be less than 5MB', 'error');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photoPreview').src = e.target.result;
                document.getElementById('photoPreview').style.display = 'block';
                document.getElementById('photoPlaceholder').style.display = 'none';
            };
            reader.readAsDataURL(file);
        }

        // Filter employees
        function filterEmployees() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();

            filteredEmployees = employees.filter(employee => {
                return employee.name.toLowerCase().includes(searchTerm) ||
                       employee.designation.toLowerCase().includes(searchTerm) ||
                       employee.email.toLowerCase().includes(searchTerm) ||
                       employee.phone.includes(searchTerm);
            });

            renderEmployeesTable();
        }

        // Render employees table //not needed
        // function renderEmployeesTable() {
        //     const tbody = document.getElementById('employeesTableBody');
        //     const emptyState = document.getElementById('emptyState');

        //     if (filteredEmployees.length === 0) {
        //         tbody.innerHTML = '';
        //         emptyState.style.display = 'block';
        //         return;
        //     }

        //     emptyState.style.display = 'none';

        //     tbody.innerHTML = filteredEmployees.map(employee => {
        //         const initials = employee.name.split(' ').map(n => n[0]).join('').toUpperCase();

        //         return `
        //             <tr>
        //                 <td>
        //                     <div class="employee-info">
        //                         <div class="employee-avatar">
        //                             ${employee.photo ?
        //                                 `<img src="${employee.photo}" alt="${employee.name}">` :
        //                                 `<div class="employee-avatar-placeholder">${initials}</div>`
        //                             }
        //                         </div>
        //                         <div class="employee-details">
        //                             <div class="employee-name">${employee.name}</div>
        //                             <div class="employee-email">${employee.email}</div>
        //                         </div>
        //                     </div>
        //                 </td>
        //                 <td>
        //                     <span class="designation-badge">${employee.designation}</span>
        //                 </td>
        //                 <td>
        //                     <div class="contact-info">
        //                         <i class="fas fa-phone"></i>
        //                         ${employee.phone}
        //                     </div>
        //                 </td>
        //                 <td>
        //                     <div class="contact-info">
        //                         <i class="fas fa-map-marker-alt"></i>
        //                         ${employee.address}
        //                     </div>
        //                 </td>
        //                 <td>
        //                     <div class="action-buttons">
        //                         <button class="action-btn btn-edit" onclick="editEmployee(${employee.id})" title="Edit Employee">
        //                             <i class="fas fa-edit"></i>
        //                         </button>
        //                         <button class="action-btn btn-delete" onclick="deleteEmployee(${employee.id})" title="Delete Employee">
        //                             <i class="fas fa-trash"></i>
        //                         </button>
        //                     </div>
        //                 </td>
        //             </tr>
        //         `;
        //     }).join('');
        // }



        // Update stats
        function updateStats() {
            document.getElementById('totalEmployees').textContent = employees.length;
            document.getElementById('activeEmployees').textContent = employees.length;
        }

        // Show success modal
        function showSuccessModal() {
            document.getElementById('successModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Close success modal
        function closeSuccessModal() {
            document.getElementById('successModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Show notification
        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
                color: white;
                padding: 16px 20px;
                border-radius: 12px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
                z-index: 9999;
                display: flex;
                align-items: center;
                gap: 12px;
                font-weight: 500;
                animation: slideInRight 0.3s ease-out;
                max-width: 400px;
            `;

            const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle';
            notification.innerHTML = `
                <i class="fas fa-${icon}"></i>
                <span>${message}</span>
                <button style="background: none; border: none; color: white; cursor: pointer; margin-left: auto;">
                    <i class="fas fa-times"></i>
                </button>
            `;

            document.body.appendChild(notification);

            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease-out';
                setTimeout(() => {
                    if (notification.parentNode) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 5000);

            // Close button
            notification.querySelector('button').addEventListener('click', () => {
                notification.style.animation = 'slideOutRight 0.3s ease-out';
                setTimeout(() => {
                    if (notification.parentNode) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            });
        }

        // Add CSS animations for notifications
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal-overlay')) {
                closeSuccessModal();
            }
        });

        // Escape key to close modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSuccessModal();
            }
        });
    </script>
</body>
</html>
