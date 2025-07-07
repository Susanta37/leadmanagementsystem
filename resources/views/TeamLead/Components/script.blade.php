 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // AOS Animation Library (simplified)
        function initAOS() {
            const elements = document.querySelectorAll('[data-aos]');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const delay = entry.target.getAttribute('data-aos-delay') || 0;
                        setTimeout(() => {
                            entry.target.classList.add('aos-animate');
                        }, delay);
                    }
                });
            }, { threshold: 0.1 });

            elements.forEach(el => observer.observe(el));
        }

        // Initialize everything when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initAOS();
            initializeCharts();
            initializeCounters();
            initializeEventListeners();
        });

        // Counter Animation
   function initializeCounters() {
    const statValues = document.querySelectorAll('.stat-value');

    statValues.forEach(stat => {
        const target = stat.getAttribute('data-target');

        if (!target) return; // 🔒 Skip if data-target is missing

        const numericValue = parseInt(target.replace(/[^0-9]/g, ''));

        if (!isNaN(numericValue)) {
            let currentValue = 0;
            const increment = numericValue / 50;
            const timer = setInterval(() => {
                currentValue += increment;
                if (currentValue >= numericValue) {
                    stat.textContent = target;
                    clearInterval(timer);
                } else {
                    stat.textContent = Math.floor(currentValue);
                }
            }, 30);
        }
    });
}

        // Initialize Charts
        function initializeCharts() {
            // Mini charts for stat cards
            const chartConfigs = [
                { id: 'teamMembersChart', data: [18, 20, 22, 24, 23, 24, 24], color: '#3b82f6' },
                { id: 'teamLeadsChart', data: [6, 7, 8, 8, 7, 8, 8], color: '#f97316' },
                { id: 'tasksChart', data: [35, 42, 45, 47, 44, 46, 47], color: '#10b981' },
                { id: 'attendanceChart', data: [18, 20, 19, 22, 21, 22, 22], color: '#f59e0b' }
            ];

            chartConfigs.forEach(config => {
                const ctx = document.getElementById(config.id);
                if (ctx) {
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ['', '', '', '', '', '', ''],
                            datasets: [{
                                data: config.data,
                                borderColor: config.color,
                                backgroundColor: config.color + '20',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                x: { display: false },
                                y: { display: false }
                            }
                        }
                    });
                }
            });

            // Performance Chart
            const performanceCtx = document.getElementById('performanceChart');
            if (performanceCtx) {
                new Chart(performanceCtx, {
                    type: 'line',
                    data: {
                        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                        datasets: [{
                            label: 'Team Performance',
                            data: [85, 88, 92, 89],
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        }, {
                            label: 'Target',
                            data: [90, 90, 90, 90],
                            borderColor: '#f97316',
                            backgroundColor: 'rgba(249, 115, 22, 0.1)',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            // Approval Chart (Pie Chart)
            const approvalCtx = document.getElementById('approvalChart');
            if (approvalCtx) {
                new Chart(approvalCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Approved', 'Pending', 'Rejected'],
                        datasets: [{
                            data: [156, 23, 12],
                            backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                            borderWidth: 0,
                            cutout: '70%'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20
                                }
                            }
                        }
                    }
                });
            }
        }

        // Event Listeners
        function initializeEventListeners() {
            // Task filter functionality
            const taskFilters = document.querySelectorAll('.task-filter');
            taskFilters.forEach(filter => {
                filter.addEventListener('click', function() {
                    taskFilters.forEach(f => f.classList.remove('active'));
                    this.classList.add('active');

                    const filterType = this.getAttribute('data-filter');
                    filterTasks(filterType);
                });
            });

            // Close modals when clicking outside
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('modal-overlay')) {
                    closeAllModals();
                }
            });

            // Escape key to close modals
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeAllModals();
                }
            });
        }

        // Filter Functions
        function applyFilters() {
            const dateRange = document.getElementById('dateRange').value;
            const teamFilter = document.getElementById('teamFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;

            console.log('Applying filters:', { dateRange, teamFilter, statusFilter });
            // Implement filter logic here
            showNotification('Filters applied successfully!', 'success');
        }

        function resetFilters() {
            document.getElementById('dateRange').value = 'month';
            document.getElementById('teamFilter').value = 'all';
            document.getElementById('statusFilter').value = 'all';

            showNotification('Filters reset successfully!', 'info');
        }

        function filterTasks(type) {
            const taskItems = document.querySelectorAll('.task-item');

            taskItems.forEach(item => {
                if (type === 'all') {
                    item.style.display = 'flex';
                } else {
                    const itemType = item.getAttribute('data-type');
                    if (itemType === type || (type === 'overdue' && item.querySelector('.status-badge.overdue'))) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                }
            });
        }

        // Menu Functions
        function toggleMenu(menuId) {
            const menu = document.getElementById(menuId);
            const allMenus = document.querySelectorAll('.dropdown-menu1');

            allMenus.forEach(m => {
                if (m.id !== menuId) {
                    m.classList.remove('active');
                }
            });

            menu.classList.toggle('active');
        }

        // Modal Functions
        function openTaskModal() {
            document.getElementById('taskModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeTaskModal() {
            document.getElementById('taskModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function openEmployeeModal() {
            document.getElementById('employeeModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeEmployeeModal() {
            document.getElementById('employeeModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function closeAllModals() {
            const modals = document.querySelectorAll('.modal-overlay');
            modals.forEach(modal => {
                modal.classList.remove('active');
            });
            document.body.style.overflow = 'auto';
        }

        // Task Functions
        function toggleAssignmentOptions() {
            const taskType = document.getElementById('taskType').value;
            const individualAssignment = document.getElementById('individualAssignment');
            const teamAssignment = document.getElementById('teamAssignment');

            if (taskType === 'individual') {
                individualAssignment.style.display = 'block';
                teamAssignment.style.display = 'none';
            } else {
                individualAssignment.style.display = 'none';
                teamAssignment.style.display = 'block';
            }
        }

        function createTask() {
            const form = document.getElementById('taskForm');
            const formData = new FormData(form);

            // Simulate task creation
            console.log('Creating task with data:', Object.fromEntries(formData));
            showNotification('Task created successfully!', 'success');
            closeTaskModal();
            form.reset();
        }

        function editTask(taskId) {
            console.log('Editing task:', taskId);
            showNotification('Opening task editor...', 'info');
        }

        function deleteTask(taskId) {
            if (confirm('Are you sure you want to delete this task?')) {
                console.log('Deleting task:', taskId);
                showNotification('Task deleted successfully!', 'success');
            }
        }

        // Employee Functions
        function viewMemberDetails(employeeId) {
            console.log('Viewing member details:', employeeId);
            openEmployeeModal();
            loadEmployeeData(employeeId);
        }

        function loadEmployeeData(employeeId) {
            // Simulate loading employee data
            const employees = {
                'EMP001': {
                    name: 'John Doe',
                    role: 'Senior Sales Executive',
                    avatar: '/placeholder.svg?height=100&width=100'
                },
                'EMP002': {
                    name: 'Sarah Wilson',
                    role: 'Marketing Specialist',
                    avatar: '/placeholder.svg?height=100&width=100'
                }
            };

            const employee = employees[employeeId] || employees['EMP001'];

            document.getElementById('employeeName').textContent = employee.name;
            document.getElementById('employeeRole').textContent = employee.role;
            document.getElementById('employeeId').textContent = employeeId;
            document.getElementById('employeeAvatar').src = employee.avatar;
        }

      function showTab(tabName) {
    const tabs = document.querySelectorAll('.tab-btn');
    const panes = document.querySelectorAll('.tab-pane');

    tabs.forEach(tab => {
        tab.classList.toggle('active', tab.dataset.tab === tabName);
    });

    panes.forEach(pane => {
        pane.classList.toggle('active', pane.id === tabName);
    });
}


        function addNewEmployee() {
            console.log('Adding new employee');
            showNotification('Opening employee creation form...', 'info');
        }

        function viewAllMembers() {
            console.log('Viewing all team members');
            showNotification('Loading all team members...', 'info');
        }

        // Lead Functions
        function updateLeadStatus(leadId, status) {
            console.log('Updating lead status:', leadId, status);
            showNotification(`Lead ${leadId} status updated to ${status}`, 'success');
        }

        function approveLead(leadId) {
            console.log('Approving lead:', leadId);
            showNotification(`Lead ${leadId} approved successfully!`, 'success');
        }

        function rejectLead(leadId) {
            const reason = prompt('Please provide a reason for rejection:');
            if (reason) {
                console.log('Rejecting lead:', leadId, 'Reason:', reason);
                showNotification(`Lead ${leadId} rejected with reason: ${reason}`, 'warning');
            }
        }

        function sendToOperations(leadId) {
            console.log('Sending lead to operations:', leadId);
            showNotification(`Lead ${leadId} sent to operations department!`, 'success');
        }

        function addRemarks(leadId) {
            const remarks = prompt('Add remarks for this lead:');
            if (remarks) {
                console.log('Adding remarks to lead:', leadId, 'Remarks:', remarks);
                showNotification('Remarks added successfully!', 'success');
            }
        }

        // Attendance Functions
        function approveAttendance(employeeId) {
            console.log('Approving attendance for:', employeeId);
            showNotification(`Attendance approved for ${employeeId}`, 'success');
        }

        function rejectAttendance(employeeId) {
            const reason = prompt('Please provide a reason for rejection:');
            if (reason) {
                console.log('Rejecting attendance for:', employeeId, 'Reason:', reason);
                showNotification(`Attendance rejected for ${employeeId}`, 'warning');
            }
        }

        function viewRemarks(employeeId) {
            console.log('Viewing remarks for:', employeeId);
            alert('Remarks: Late arrival due to traffic. Please ensure punctuality.');
        }

        // Utility Functions
        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const rowSelects = document.querySelectorAll('.row-select');

            rowSelects.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        }

        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
                <span>${message}</span>
            `;

            // Add notification styles if not already present
            if (!document.querySelector('.notification-styles')) {
                const styles = document.createElement('style');
                styles.className = 'notification-styles';
                styles.textContent = `
                    .notification {
                        position: fixed;
                        top: 20px;
                        right: 20px;
                        padding: 16px 20px;
                        border-radius: 8px;
                        color: white;
                        font-weight: 500;
                        z-index: 10000;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                        animation: slideInRight 0.3s ease;
                    }
                    .notification.success { background: #10b981; }
                    .notification.warning { background: #f59e0b; }
                    .notification.info { background: #3b82f6; }
                    .notification.error { background: #ef4444; }
                    @keyframes slideInRight {
                        from { transform: translateX(100%); opacity: 0; }
                        to { transform: translateX(0); opacity: 1; }
                    }
                `;
                document.head.appendChild(styles);
            }

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideInRight 0.3s ease reverse';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.stat-menu')) {
                document.querySelectorAll('.dropdown-menu1').forEach(menu => {
                    menu.classList.remove('active');
                });
            }
        });


function openLeadModal() {
    document.getElementById('leadModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeLeadModal() {
    document.getElementById('leadModal').classList.remove('active');
    document.body.style.overflow = 'auto';
}

// Optional: Close modal on overlay click
document.addEventListener('click', function(e) {
    if (e.target.id === 'leadModal') {
        closeLeadModal();
    }
});
    </script>
