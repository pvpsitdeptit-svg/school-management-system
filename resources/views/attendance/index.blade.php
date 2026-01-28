<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management - School Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1565C0;
            --secondary-color: #00897B;
            --accent-color: #FFB300;
            --dark-color: #212121;
            --light-bg: #F5F5F5;
            --success-color: #4CAF50;
            --warning-color: #FF9800;
            --danger-color: #D32F2F;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            color: var(--dark-color);
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.4rem;
        }

        .sidebar {
            background: white;
            min-height: calc(100vh - 56px);
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            border-right: 1px solid #e0e0e0;
        }

        .sidebar .nav-link {
            color: var(--dark-color);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 10px;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover {
            background-color: var(--light-bg);
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }

        .main-content {
            padding: 30px;
        }

        .page-header {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--dark-color);
            margin: 0;
        }

        .breadcrumb {
            background: none;
            padding: 0;
            margin: 10px 0 0 0;
        }

        .breadcrumb-item {
            color: #666;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(21, 101, 192, 0.3);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid #e0e0e0;
            border-radius: 12px 12px 0 0 !important;
            font-weight: 600;
        }

        .attendance-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .attendance-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .attendance-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        .attendance-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .class-info {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .class-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-right: 15px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }

        .class-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .attendance-stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding: 15px;
            background: var(--light-bg);
            border-radius: 8px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
        }

        .stat-label {
            font-size: 0.8rem;
            color: #666;
            text-transform: uppercase;
        }

        .present { color: var(--success-color); }
        .absent { color: var(--danger-color); }
        .leave { color: var(--warning-color); }

        .quick-actions {
            display: flex;
            gap: 10px;
        }

        .quick-actions .btn {
            flex: 1;
            padding: 8px;
            font-size: 0.9rem;
            border-radius: 8px;
        }

        .table {
            border-radius: 8px;
            overflow: hidden;
        }

        .table thead th {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
        }

        .badge-success {
            background-color: var(--success-color);
        }

        .badge-warning {
            background-color: var(--warning-color);
        }

        .badge-danger {
            background-color: var(--danger-color);
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .modal-content {
            border-radius: 15px;
            border: none;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 15px 15px 0 0;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #e0e0e0;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(21, 101, 192, 0.1);
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 56px;
                left: -250px;
                width: 250px;
                height: calc(100vh - 56px);
                z-index: 1000;
                transition: left 0.3s ease;
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="/dashboard">
                <i class="fas fa-graduation-cap me-2"></i>
                School Management System
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <div class="user-avatar me-2">A</div>
                            Admin User
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <nav class="nav flex-column mt-3">
                    <a class="nav-link" href="/dashboard">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard
                    </a>
                    <a class="nav-link" href="/students">
                        <i class="fas fa-users me-2"></i>
                        Students
                    </a>
                    <a class="nav-link" href="/faculty">
                        <i class="fas fa-chalkboard-teacher me-2"></i>
                        Faculty
                    </a>
                    <a class="nav-link" href="/classes">
                        <i class="fas fa-door-open me-2"></i>
                        Classes
                    </a>
                    <a class="nav-link" href="/subjects">
                        <i class="fas fa-book me-2"></i>
                        Subjects
                    </a>
                    <a class="nav-link active" href="/attendance">
                        <i class="fas fa-calendar-check me-2"></i>
                        Attendance
                    </a>
                    <a class="nav-link" href="/marks">
                        <i class="fas fa-chart-line me-2"></i>
                        Marks
                    </a>
                    <a class="nav-link" href="/exams">
                        <i class="fas fa-file-alt me-2"></i>
                        Exams
                    </a>
                    <a class="nav-link" href="/fees">
                        <i class="fas fa-dollar-sign me-2"></i>
                        Fees
                    </a>
                    <a class="nav-link" href="/settings">
                        <i class="fas fa-cog me-2"></i>
                        Settings
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h1 class="page-title">Attendance Management</h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                                    <li class="breadcrumb-item active">Attendance</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#markAttendanceModal">
                                <i class="fas fa-calendar-plus me-2"></i>
                                Mark Attendance
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="attendance-card">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="class-icon" style="background: var(--success-color);">
                                        <i class="fas fa-user-check"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1">1,156</h5>
                                    <p class="text-muted mb-0">Present Today</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="attendance-card">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="class-icon" style="background: var(--danger-color);">
                                        <i class="fas fa-user-times"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1">78</h5>
                                    <p class="text-muted mb-0">Absent Today</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="attendance-card">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="class-icon" style="background: var(--warning-color);">
                                        <i class="fas fa-calendar-minus"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1">23</h5>
                                    <p class="text-muted mb-0">On Leave</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="attendance-card">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="class-icon">
                                        <i class="fas fa-percentage"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1">93.7%</h5>
                                    <p class="text-muted mb-0">Attendance Rate</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Class-wise Attendance Overview -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Today's Attendance Overview</h5>
                    </div>
                    <div class="card-body">
                        <div class="attendance-grid">
                            <div class="attendance-card">
                                <div class="class-info">
                                    <div class="class-icon">
                                        <i class="fas fa-school"></i>
                                    </div>
                                    <div>
                                        <div class="class-name">Class 10-A</div>
                                        <small class="text-muted">45 Students</small>
                                    </div>
                                </div>
                                <div class="attendance-stats">
                                    <div class="stat-item">
                                        <div class="stat-value present">42</div>
                                        <div class="stat-label">Present</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value absent">3</div>
                                        <div class="stat-label">Absent</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value leave">0</div>
                                        <div class="stat-label">Leave</div>
                                    </div>
                                </div>
                                <div class="quick-actions">
                                    <button class="btn btn-success btn-sm">
                                        <i class="fas fa-check me-1"></i> Mark All Present
                                    </button>
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit me-1"></i> Mark Individual
                                    </button>
                                </div>
                            </div>

                            <div class="attendance-card">
                                <div class="class-info">
                                    <div class="class-icon">
                                        <i class="fas fa-school"></i>
                                    </div>
                                    <div>
                                        <div class="class-name">Class 10-B</div>
                                        <small class="text-muted">42 Students</small>
                                    </div>
                                </div>
                                <div class="attendance-stats">
                                    <div class="stat-item">
                                        <div class="stat-value present">38</div>
                                        <div class="stat-label">Present</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value absent">2</div>
                                        <div class="stat-label">Absent</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value leave">2</div>
                                        <div class="stat-label">Leave</div>
                                    </div>
                                </div>
                                <div class="quick-actions">
                                    <button class="btn btn-success btn-sm">
                                        <i class="fas fa-check me-1"></i> Mark All Present
                                    </button>
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit me-1"></i> Mark Individual
                                    </button>
                                </div>
                            </div>

                            <div class="attendance-card">
                                <div class="class-info">
                                    <div class="class-icon">
                                        <i class="fas fa-school"></i>
                                    </div>
                                    <div>
                                        <div class="class-name">Class 11-A</div>
                                        <small class="text-muted">38 Students</small>
                                    </div>
                                </div>
                                <div class="attendance-stats">
                                    <div class="stat-item">
                                        <div class="stat-value present">35</div>
                                        <div class="stat-label">Present</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value absent">1</div>
                                        <div class="stat-label">Absent</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value leave">2</div>
                                        <div class="stat-label">Leave</div>
                                    </div>
                                </div>
                                <div class="quick-actions">
                                    <button class="btn btn-success btn-sm">
                                        <i class="fas fa-check me-1"></i> Mark All Present
                                    </button>
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit me-1"></i> Mark Individual
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Attendance Records -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Attendance Records</h5>
                        <div>
                            <button class="btn btn-sm btn-outline-primary me-2">
                                <i class="fas fa-download me-1"></i>
                                Export
                            </button>
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-print me-1"></i>
                                Print
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Class</th>
                                        <th>Present</th>
                                        <th>Absent</th>
                                        <th>Leave</th>
                                        <th>Total</th>
                                        <th>Percentage</th>
                                        <th>Marked By</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>2024-01-25</td>
                                        <td>10-A</td>
                                        <td><span class="badge badge-success">42</span></td>
                                        <td><span class="badge badge-danger">3</span></td>
                                        <td><span class="badge badge-warning">0</span></td>
                                        <td>45</td>
                                        <td>93.3%</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-2">JD</div>
                                                <span>John Davidson</span>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2024-01-25</td>
                                        <td>10-B</td>
                                        <td><span class="badge badge-success">38</span></td>
                                        <td><span class="badge badge-danger">2</span></td>
                                        <td><span class="badge badge-warning">2</span></td>
                                        <td>42</td>
                                        <td>90.5%</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-2">SM</div>
                                                <span>Sarah Mitchell</span>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2024-01-24</td>
                                        <td>11-A</td>
                                        <td><span class="badge badge-success">36</span></td>
                                        <td><span class="badge badge-danger">1</span></td>
                                        <td><span class="badge badge-warning">1</span></td>
                                        <td>38</td>
                                        <td>94.7%</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-2">RJ</div>
                                                <span>Robert Johnson</span>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mark Attendance Modal -->
    <div class="modal fade" id="markAttendanceModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-calendar-plus me-2"></i>
                        Mark Attendance
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="markAttendanceForm">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Class *</label>
                                <select class="form-select" required>
                                    <option value="">Select Class</option>
                                    <option value="1">Class 10-A</option>
                                    <option value="2">Class 10-B</option>
                                    <option value="3">Class 11-A</option>
                                    <option value="4">Class 12-A</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Date *</label>
                                <input type="date" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Subject</label>
                                <select class="form-select">
                                    <option value="">All Subjects</option>
                                    <option value="math">Mathematics</option>
                                    <option value="physics">Physics</option>
                                    <option value="chemistry">Chemistry</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Quick Actions</label>
                            <div class="btn-group w-100" role="group">
                                <button type="button" class="btn btn-success" onclick="markAllPresent()">
                                    <i class="fas fa-check me-1"></i> Mark All Present
                                </button>
                                <button type="button" class="btn btn-danger" onclick="markAllAbsent()">
                                    <i class="fas fa-times me-1"></i> Mark All Absent
                                </button>
                                <button type="button" class="btn btn-warning" onclick="markAllLeave()">
                                    <i class="fas fa-minus me-1"></i> Mark All Leave
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Present</th>
                                        <th>Absent</th>
                                        <th>Leave</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>STU001</td>
                                        <td>John Doe</td>
                                        <td><input type="radio" name="attendance_1" value="present" checked></td>
                                        <td><input type="radio" name="attendance_1" value="absent"></td>
                                        <td><input type="radio" name="attendance_1" value="leave"></td>
                                        <td><input type="text" class="form-control form-control-sm" placeholder="Remarks"></td>
                                    </tr>
                                    <tr>
                                        <td>STU002</td>
                                        <td>Jane Smith</td>
                                        <td><input type="radio" name="attendance_2" value="present" checked></td>
                                        <td><input type="radio" name="attendance_2" value="absent"></td>
                                        <td><input type="radio" name="attendance_2" value="leave"></td>
                                        <td><input type="text" class="form-control form-control-sm" placeholder="Remarks"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveAttendance()">
                        <i class="fas fa-save me-2"></i>
                        Save Attendance
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function saveAttendance() {
            alert('Attendance marked successfully!');
            bootstrap.Modal.getInstance(document.getElementById('markAttendanceModal')).hide();
        }

        function markAllPresent() {
            const radios = document.querySelectorAll('input[type="radio"][value="present"]');
            radios.forEach(radio => radio.checked = true);
        }

        function markAllAbsent() {
            const radios = document.querySelectorAll('input[type="radio"][value="absent"]');
            radios.forEach(radio => radio.checked = true);
        }

        function markAllLeave() {
            const radios = document.querySelectorAll('input[type="radio"][value="leave"]');
            radios.forEach(radio => radio.checked = true);
        }

        // Set today's date as default
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.querySelector('input[type="date"]');
            if (dateInput) {
                dateInput.value = new Date().toISOString().split('T')[0];
            }
        });
    </script>
</body>
</html>
