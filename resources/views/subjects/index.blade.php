<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjects Management - School Management System</title>
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

        .subject-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-bottom: 20px;
            cursor: pointer;
        }

        .subject-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        .subject-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .subject-icon {
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

        .subject-name {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 5px;
        }

        .subject-code {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 15px;
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

        .search-box {
            position: relative;
        }

        .search-box input {
            border-radius: 25px;
            padding-left: 45px;
            border: 2px solid #e0e0e0;
        }

        .search-box input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(21, 101, 192, 0.1);
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
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
                    <a class="nav-link active" href="/subjects">
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
                            <h1 class="page-title">Subjects Management</h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                                    <li class="breadcrumb-item active">Subjects</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                                <i class="fas fa-plus me-2"></i>
                                Add Subject
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="subject-card">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="subject-icon">
                                        <i class="fas fa-book"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1">28</h5>
                                    <p class="text-muted mb-0">Total Subjects</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="subject-card">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="subject-icon" style="background: var(--success-color);">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1">156</h5>
                                    <p class="text-muted mb-0">Faculty Assignments</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="subject-card">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="subject-icon" style="background: var(--warning-color);">
                                        <i class="fas fa-school"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1">45</h5>
                                    <p class="text-muted mb-0">Classes Using</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="subject-card">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="subject-icon" style="background: var(--danger-color);">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1">6</h5>
                                    <p class="text-muted mb-0">Inactive Subjects</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="search-box">
                                    <i class="fas fa-search"></i>
                                    <input type="text" class="form-control" placeholder="Search subjects..." id="searchInput">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="departmentFilter">
                                    <option value="">All Departments</option>
                                    <option value="science">Science</option>
                                    <option value="mathematics">Mathematics</option>
                                    <option value="english">English</option>
                                    <option value="social">Social Studies</option>
                                    <option value="languages">Languages</option>
                                    <option value="arts">Arts</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="statusFilter">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subjects Grid -->
                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                        <div class="subject-card">
                            <div class="subject-icon">
                                <i class="fas fa-calculator"></i>
                            </div>
                            <div class="subject-name">Mathematics</div>
                            <div class="subject-code">MATH101</div>
                            <span class="badge badge-success">Active</span>
                            <div class="stats-row">
                                <div class="stat-item">
                                    <div class="stat-value">12</div>
                                    <div class="stat-label">Classes</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">8</div>
                                    <div class="stat-label">Faculty</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">156</div>
                                    <div class="stat-label">Students</div>
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
                        <div class="subject-card">
                            <div class="subject-icon" style="background: var(--success-color);">
                                <i class="fas fa-flask"></i>
                            </div>
                            <div class="subject-name">Physics</div>
                            <div class="subject-code">PHY101</div>
                            <span class="badge badge-success">Active</span>
                            <div class="stats-row">
                                <div class="stat-item">
                                    <div class="stat-value">8</div>
                                    <div class="stat-label">Classes</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">5</div>
                                    <div class="stat-label">Faculty</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">98</div>
                                    <div class="stat-label">Students</div>
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
                        <div class="subject-card">
                            <div class="subject-icon" style="background: var(--warning-color);">
                                <i class="fas fa-vial"></i>
                            </div>
                            <div class="subject-name">Chemistry</div>
                            <div class="subject-code">CHEM101</div>
                            <span class="badge badge-success">Active</span>
                            <div class="stats-row">
                                <div class="stat-item">
                                    <div class="stat-value">8</div>
                                    <div class="stat-label">Classes</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">4</div>
                                    <div class="stat-label">Faculty</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">92</div>
                                    <div class="stat-label">Students</div>
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
                        <div class="subject-card">
                            <div class="subject-icon" style="background: var(--danger-color);">
                                <i class="fas fa-dna"></i>
                            </div>
                            <div class="subject-name">Biology</div>
                            <div class="subject-code">BIO101</div>
                            <span class="badge badge-success">Active</span>
                            <div class="stats-row">
                                <div class="stat-item">
                                    <div class="stat-value">6</div>
                                    <div class="stat-label">Classes</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">3</div>
                                    <div class="stat-label">Faculty</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">76</div>
                                    <div class="stat-label">Students</div>
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
                        <div class="subject-card">
                            <div class="subject-icon" style="background: var(--info-color);">
                                <i class="fas fa-language"></i>
                            </div>
                            <div class="subject-name">English</div>
                            <div class="subject-code">ENG101</div>
                            <span class="badge badge-success">Active</span>
                            <div class="stats-row">
                                <div class="stat-item">
                                    <div class="stat-value">15</div>
                                    <div class="stat-label">Classes</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">10</div>
                                    <div class="stat-label">Faculty</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">234</div>
                                    <div class="stat-label">Students</div>
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
                        <div class="subject-card">
                            <div class="subject-icon" style="background: var(--secondary-color);">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div class="subject-name">History</div>
                            <div class="subject-code">HIST101</div>
                            <span class="badge badge-warning">Inactive</span>
                            <div class="stats-row">
                                <div class="stat-item">
                                    <div class="stat-value">4</div>
                                    <div class="stat-label">Classes</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">2</div>
                                    <div class="stat-label">Faculty</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">45</div>
                                    <div class="stat-label">Students</div>
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
        </div>
    </div>

    <!-- Add Subject Modal -->
    <div class="modal fade" id="addSubjectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-book me-2"></i>
                        Add New Subject
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addSubjectForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Subject Name *</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Subject Code *</label>
                                <input type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Department *</label>
                                <select class="form-select" required>
                                    <option value="">Select Department</option>
                                    <option value="science">Science</option>
                                    <option value="mathematics">Mathematics</option>
                                    <option value="english">English</option>
                                    <option value="social">Social Studies</option>
                                    <option value="languages">Languages</option>
                                    <option value="arts">Arts</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Type</label>
                                <select class="form-select">
                                    <option value="core">Core Subject</option>
                                    <option value="elective">Elective</option>
                                    <option value="optional">Optional</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Credits</label>
                                <input type="number" class="form-control" min="1" max="10">
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
                            <textarea class="form-control" rows="3" placeholder="Subject description and objectives"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveSubject()">
                        <i class="fas fa-save me-2"></i>
                        Save Subject
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function saveSubject() {
            alert('Subject added successfully!');
            bootstrap.Modal.getInstance(document.getElementById('addSubjectModal')).hide();
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.subject-card');
            
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.parentElement.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Filter functionality
        function applyFilters() {
            const departmentFilter = document.getElementById('departmentFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            
            const cards = document.querySelectorAll('.subject-card');
            
            cards.forEach(card => {
                let show = true;
                
                // This is a simplified filter - in real implementation, you'd need to store department/status as data attributes
                if (departmentFilter || statusFilter) {
                    // Apply filtering logic here
                }
                
                card.parentElement.style.display = show ? '' : 'none';
            });
        }

        // Add event listeners to filters
        document.getElementById('departmentFilter').addEventListener('change', applyFilters);
        document.getElementById('statusFilter').addEventListener('change', applyFilters);
    </script>
</body>
</html>
