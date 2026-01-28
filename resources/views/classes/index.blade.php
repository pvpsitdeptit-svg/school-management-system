<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classes Management - School Management System</title>
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

        .class-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .class-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        .class-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .class-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 15px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }

        .class-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 5px;
        }

        .class-section {
            font-size: 1.2rem;
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 10px;
        }

        .stats-row {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .stat-label {
            font-size: 0.8rem;
            color: #666;
            text-transform: uppercase;
        }

        .action-buttons .btn {
            padding: 6px 12px;
            font-size: 0.875rem;
            border-radius: 6px;
            margin: 0 2px;
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

        .view-toggle {
            display: flex;
            gap: 10px;
        }

        .view-toggle .btn {
            border-radius: 8px;
            padding: 8px 16px;
        }

        .view-toggle .btn.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-color: var(--primary-color);
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
                    <a class="nav-link active" href="/classes">
                        <i class="fas fa-door-open me-2"></i>
                        Classes
                    </a>
                    <a class="nav-link" href="/subjects">
                        <i class="fas fa-book me-2"></i>
                        Subjects
                    </a>
                    <a class="nav-link" href="/attendance">
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
                            <h1 class="page-title">Classes Management</h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                                    <li class="breadcrumb-item active">Classes</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex gap-2">
                                <div class="view-toggle">
                                    <button class="btn btn-outline-primary active" onclick="showGridView()">
                                        <i class="fas fa-th"></i>
                                    </button>
                                    <button class="btn btn-outline-primary" onclick="showListView()">
                                        <i class="fas fa-list"></i>
                                    </button>
                                </div>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClassModal">
                                    <i class="fas fa-plus me-2"></i>
                                    Add Class
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="class-card">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="class-icon">
                                        <i class="fas fa-door-open"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1">45</h5>
                                    <p class="text-muted mb-0">Total Classes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="class-card">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="class-icon" style="background: var(--success-color);">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1">38</h5>
                                    <p class="text-muted mb-0">Active Classes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="class-card">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="class-icon" style="background: var(--warning-color);">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1">1,234</h5>
                                    <p class="text-muted mb-0">Total Students</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="class-card">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="class-icon" style="background: var(--danger-color);">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1">7</h5>
                                    <p class="text-muted mb-0">Inactive Classes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grid View -->
                <div id="gridView">
                    <div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                            <div class="class-card">
                                <div class="class-icon">
                                    <i class="fas fa-school"></i>
                                </div>
                                <div class="class-number">10</div>
                                <div class="class-section">Section A</div>
                                <span class="badge badge-success">Active</span>
                                <div class="stats-row">
                                    <div class="stat-item">
                                        <div class="stat-value">45</div>
                                        <div class="stat-label">Students</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value">6</div>
                                        <div class="stat-label">Subjects</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value">4</div>
                                        <div class="stat-label">Faculty</div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-outline-primary me-1">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary me-1">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                            <div class="class-card">
                                <div class="class-icon">
                                    <i class="fas fa-school"></i>
                                </div>
                                <div class="class-number">10</div>
                                <div class="class-section">Section B</div>
                                <span class="badge badge-success">Active</span>
                                <div class="stats-row">
                                    <div class="stat-item">
                                        <div class="stat-value">42</div>
                                        <div class="stat-label">Students</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value">6</div>
                                        <div class="stat-label">Subjects</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value">4</div>
                                        <div class="stat-label">Faculty</div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-outline-primary me-1">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary me-1">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                            <div class="class-card">
                                <div class="class-icon">
                                    <i class="fas fa-school"></i>
                                </div>
                                <div class="class-number">11</div>
                                <div class="class-section">Section A</div>
                                <span class="badge badge-warning">Inactive</span>
                                <div class="stats-row">
                                    <div class="stat-item">
                                        <div class="stat-value">38</div>
                                        <div class="stat-label">Students</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value">6</div>
                                        <div class="stat-label">Subjects</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value">4</div>
                                        <div class="stat-label">Faculty</div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-outline-primary me-1">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary me-1">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                            <div class="class-card">
                                <div class="class-icon">
                                    <i class="fas fa-school"></i>
                                </div>
                                <div class="class-number">12</div>
                                <div class="class-section">Section A</div>
                                <span class="badge badge-success">Active</span>
                                <div class="stats-row">
                                    <div class="stat-item">
                                        <div class="stat-value">35</div>
                                        <div class="stat-label">Students</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value">6</div>
                                        <div class="stat-label">Subjects</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value">4</div>
                                        <div class="stat-label">Faculty</div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-outline-primary me-1">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary me-1">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- List View (Hidden by default) -->
                <div id="listView" style="display: none;">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Classes List</h5>
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
                                            <th>
                                                <input type="checkbox" class="form-check-input">
                                            </th>
                                            <th>Class</th>
                                            <th>Section</th>
                                            <th>Students</th>
                                            <th>Subjects</th>
                                            <th>Faculty</th>
                                            <th>Status</th>
                                            <th>Created Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="checkbox" class="form-check-input"></td>
                                            <td>10</td>
                                            <td>A</td>
                                            <td>45</td>
                                            <td>6</td>
                                            <td>4</td>
                                            <td><span class="badge badge-success">Active</span></td>
                                            <td>2024-01-15</td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="btn btn-sm btn-outline-primary" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-secondary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="form-check-input"></td>
                                            <td>10</td>
                                            <td>B</td>
                                            <td>42</td>
                                            <td>6</td>
                                            <td>4</td>
                                            <td><span class="badge badge-success">Active</span></td>
                                            <td>2024-01-15</td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="btn btn-sm btn-outline-primary" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-secondary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
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
            </div>
        </div>
    </div>

    <!-- Add Class Modal -->
    <div class="modal fade" id="addClassModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-door-open me-2"></i>
                        Add New Class
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addClassForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Class Name *</label>
                                <select class="form-select" required>
                                    <option value="">Select Class</option>
                                    <option value="1">Class 1</option>
                                    <option value="2">Class 2</option>
                                    <option value="3">Class 3</option>
                                    <option value="4">Class 4</option>
                                    <option value="5">Class 5</option>
                                    <option value="6">Class 6</option>
                                    <option value="7">Class 7</option>
                                    <option value="8">Class 8</option>
                                    <option value="9">Class 9</option>
                                    <option value="10">Class 10</option>
                                    <option value="11">Class 11</option>
                                    <option value="12">Class 12</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Section *</label>
                                <select class="form-select" required>
                                    <option value="">Select Section</option>
                                    <option value="A">Section A</option>
                                    <option value="B">Section B</option>
                                    <option value="C">Section C</option>
                                    <option value="D">Section D</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Class Teacher</label>
                                <select class="form-select">
                                    <option value="">Select Teacher</option>
                                    <option value="1">Dr. John Davidson</option>
                                    <option value="2">Sarah Mitchell</option>
                                    <option value="3">Robert Johnson</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" rows="3" placeholder="Optional class description"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveClass()">
                        <i class="fas fa-save me-2"></i>
                        Save Class
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function saveClass() {
            alert('Class added successfully!');
            bootstrap.Modal.getInstance(document.getElementById('addClassModal')).hide();
        }

        function showGridView() {
            document.getElementById('gridView').style.display = 'block';
            document.getElementById('listView').style.display = 'none';
            document.querySelectorAll('.view-toggle .btn')[0].classList.add('active');
            document.querySelectorAll('.view-toggle .btn')[1].classList.remove('active');
        }

        function showListView() {
            document.getElementById('gridView').style.display = 'none';
            document.getElementById('listView').style.display = 'block';
            document.querySelectorAll('.view-toggle .btn')[1].classList.add('active');
            document.querySelectorAll('.view-toggle .btn')[0].classList.remove('active');
        }
    </script>
</body>
</html>
