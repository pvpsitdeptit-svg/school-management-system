<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management System - Dashboard</title>
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
            box-shadow: 0 1px 5px rgba(0,0,0,0.1);
            padding: 8px 0;
            min-height: 50px;
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.2rem;
        }

        .horizontal-nav {
            background: white;
            border-bottom: 2px solid #e0e0e0;
            padding: 0;
            margin-bottom: 20px;
            border-radius: 0;
        }

        .horizontal-nav .nav-tabs {
            border-bottom: none;
        }

        .horizontal-nav .nav-link {
            color: var(--dark-color);
            padding: 12px 20px;
            border-radius: 0;
            margin: 0;
            transition: all 0.3s ease;
            font-weight: 500;
            border-bottom: 3px solid transparent;
            background: none;
        }

        .horizontal-nav .nav-link:hover {
            color: var(--primary-color);
            background: rgba(21, 101, 192, 0.05);
            border-bottom-color: rgba(21, 101, 192, 0.2);
        }

        .horizontal-nav .nav-link.active {
            color: var(--primary-color);
            background: none;
            border-bottom: 3px solid var(--primary-color);
        }

        .sidebar {
            display: none;
        }

        .main-content {
            padding: 20px;
            max-width: 100%;
            margin: 0 auto;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 12px;
        }

        .stat-icon.primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-color) 100%);
            color: white;
        }

        .stat-icon.success {
            background: linear-gradient(135deg, var(--success-color) 0%, #66BB6A 100%);
            color: white;
        }

        .stat-icon.warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #FFB74D 100%);
            color: white;
        }

        .stat-icon.danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, #EF5350 100%);
            color: white;
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .page-header {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .page-title {
            font-size: 1.5rem;
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

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .footer {
            background: white;
            border-top: 1px solid #e0e0e0;
            padding: 20px 0;
            margin-top: 40px;
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
        <div class="container-fluid px-3">
            <a class="navbar-brand" href="#">
                <i class="fas fa-graduation-cap me-2"></i>
                SMS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
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
                            <li><a class="dropdown-item" href="#" onclick="document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <!-- Horizontal Navigation Tabs -->
        <div class="horizontal-nav">
            <ul class="nav nav-tabs" id="mainTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" href="#dashboard" role="tab" aria-controls="dashboard" aria-selected="true">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="students-tab" data-bs-toggle="tab" href="#students" role="tab" aria-controls="students" aria-selected="false">
                        <i class="fas fa-users me-2"></i>
                        Students
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="faculty-tab" data-bs-toggle="tab" href="#faculty" role="tab" aria-controls="faculty" aria-selected="false">
                        <i class="fas fa-chalkboard-teacher me-2"></i>
                        Faculty
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="classes-tab" data-bs-toggle="tab" href="#classes" role="tab" aria-controls="classes" aria-selected="false">
                        <i class="fas fa-door-open me-2"></i>
                        Classes
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="subjects-tab" data-bs-toggle="tab" href="#subjects" role="tab" aria-controls="subjects" aria-selected="false">
                        <i class="fas fa-book me-2"></i>
                        Subjects
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="attendance-tab" data-bs-toggle="tab" href="#attendance" role="tab" aria-controls="attendance" aria-selected="false">
                        <i class="fas fa-calendar-check me-2"></i>
                        Attendance
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="marks-tab" data-bs-toggle="tab" href="#marks" role="tab" aria-controls="marks" aria-selected="false">
                        <i class="fas fa-chart-line me-2"></i>
                        Marks
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="exams-tab" data-bs-toggle="tab" href="#exams" role="tab" aria-controls="exams" aria-selected="false">
                        <i class="fas fa-file-alt me-2"></i>
                        Exams
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="fees-tab" data-bs-toggle="tab" href="#fees" role="tab" aria-controls="fees" aria-selected="false">
                        <i class="fas fa-dollar-sign me-2"></i>
                        Fees
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="settings-tab" data-bs-toggle="tab" href="#settings" role="tab" aria-controls="settings" aria-selected="false">
                        <i class="fas fa-cog me-2"></i>
                        Settings
                    </a>
                </li>
            </ul>
        </div>

        <!-- Tab Content -->
        <div class="tab-content" id="mainTabContent">
            <!-- Dashboard Tab -->
            <div class="tab-pane fade show active" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
                <!-- Page Header -->
                <div class="page-header">
                    <h1 class="page-title">Dashboard</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </nav>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stat-card">
                            <div class="stat-icon primary">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="stat-number">{{ $stats['total_students'] }}</div>
                            <div class="stat-label">Total Students</div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stat-card">
                            <div class="stat-icon success">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div class="stat-number">{{ $stats['total_faculty'] }}</div>
                            <div class="stat-label">Total Faculty</div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stat-card">
                            <div class="stat-icon warning">
                                <i class="fas fa-door-open"></i>
                            </div>
                            <div class="stat-number">{{ $stats['total_classes'] }}</div>
                            <div class="stat-label">Total Classes</div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stat-card">
                            <div class="stat-icon danger">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="stat-number">{{ $stats['total_subjects'] }}</div>
                            <div class="stat-label">Total Subjects</div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities & Quick Actions -->
                <div class="row">
                    <div class="col-lg-8 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Recent Activities</h5>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-user-plus text-success me-3"></i>
                                            <span>New student admitted: John Doe</span>
                                        </div>
                                        <small class="text-muted">2 hours ago</small>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-calendar-check text-primary me-3"></i>
                                            <span>Attendance marked for Class 10-A</span>
                                        </div>
                                        <small class="text-muted">3 hours ago</small>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-file-alt text-warning me-3"></i>
                                            <span>Exam results published</span>
                                        </div>
                                        <small class="text-muted">5 hours ago</small>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-dollar-sign text-success me-3"></i>
                                            <span>Fee payment received: Sarah Smith</span>
                                        </div>
                                        <small class="text-muted">1 day ago</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Quick Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary">
                                        <i class="fas fa-user-plus me-2"></i>
                                        Admit New Student
                                    </button>
                                    <button class="btn btn-outline-primary">
                                        <i class="fas fa-calendar-plus me-2"></i>
                                        Mark Attendance
                                    </button>
                                    <button class="btn btn-outline-primary">
                                        <i class="fas fa-file-plus me-2"></i>
                                        Create Exam
                                    </button>
                                    <button class="btn btn-outline-primary">
                                        <i class="fas fa-receipt me-2"></i>
                                        Generate Fee Bill
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students Tab -->
            <div class="tab-pane fade" id="students" role="tabpanel" aria-labelledby="students-tab">
                <div class="page-header">
                    <h1 class="page-title">Students Management</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active">Students</li>
                        </ol>
                    </nav>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Students management functionality will be available here.
                </div>
            </div>

            <!-- Faculty Tab -->
            <div class="tab-pane fade" id="faculty" role="tabpanel" aria-labelledby="faculty-tab">
                <div class="page-header">
                    <h1 class="page-title">Faculty Management</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active">Faculty</li>
                        </ol>
                    </nav>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Faculty management functionality will be available here.
                </div>
            </div>

            <!-- Classes Tab -->
            <div class="tab-pane fade" id="classes" role="tabpanel" aria-labelledby="classes-tab">
                <div class="page-header">
                    <h1 class="page-title">Classes Management</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active">Classes</li>
                        </ol>
                    </nav>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Classes management functionality will be available here.
                </div>
            </div>

            <!-- Subjects Tab -->
            <div class="tab-pane fade" id="subjects" role="tabpanel" aria-labelledby="subjects-tab">
                <div class="page-header">
                    <h1 class="page-title">Subjects Management</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active">Subjects</li>
                        </ol>
                    </nav>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Subjects management functionality will be available here.
                </div>
            </div>

            <!-- Attendance Tab -->
            <div class="tab-pane fade" id="attendance" role="tabpanel" aria-labelledby="attendance-tab">
                <div class="page-header">
                    <h1 class="page-title">Attendance Management</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active">Attendance</li>
                        </ol>
                    </nav>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Attendance management functionality will be available here.
                </div>
            </div>

            <!-- Marks Tab -->
            <div class="tab-pane fade" id="marks" role="tabpanel" aria-labelledby="marks-tab">
                <div class="page-header">
                    <h1 class="page-title">Marks Management</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active">Marks</li>
                        </ol>
                    </nav>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Marks management functionality will be available here.
                </div>
            </div>

            <!-- Exams Tab -->
            <div class="tab-pane fade" id="exams" role="tabpanel" aria-labelledby="exams-tab">
                <div class="page-header">
                    <h1 class="page-title">Exams Management</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active">Exams</li>
                        </ol>
                    </nav>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Exams management functionality will be available here.
                </div>
            </div>

            <!-- Fees Tab -->
            <div class="tab-pane fade" id="fees" role="tabpanel" aria-labelledby="fees-tab">
                <div class="page-header">
                    <h1 class="page-title">Fees Management</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active">Fees</li>
                        </ol>
                    </nav>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Fees management functionality will be available here.
                </div>
            </div>

            <!-- Settings Tab -->
            <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                <div class="page-header">
                    <h1 class="page-title">Settings</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active">Settings</li>
                        </ol>
                    </nav>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Settings functionality will be available here.
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; 2024 School Management System. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">Version 1.0.0 | Developed with <i class="fas fa-heart text-danger"></i> by SMS Team</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Hidden Logout Form -->
    <form id="logout-form" action="/logout" method="POST" style="display: none;">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
