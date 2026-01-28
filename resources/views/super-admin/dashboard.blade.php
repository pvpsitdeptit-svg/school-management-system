<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard - School Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6366F1;
            --secondary-color: #8B5CF6;
            --accent-color: #EC4899;
            --dark-color: #1F2937;
            --light-bg: #F9FAFB;
            --success-color: #10B981;
            --warning-color: #F59E0B;
            --danger-color: #EF4444;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
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
            font-weight: 700;
            font-size: 1.2rem;
        }

        .super-admin-badge {
            background: var(--accent-color);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sidebar {
            background: white;
            min-height: calc(100vh - 70px);
            box-shadow: 4px 0 20px rgba(0,0,0,0.05);
            border-right: 1px solid #e5e7eb;
        }

        .sidebar .nav-link {
            color: var(--dark-color);
            padding: 16px 24px;
            border-radius: 12px;
            margin: 4px 12px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .sidebar .nav-link:hover {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            transform: translateX(8px);
        }

        .sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }

        .main-content {
            padding: 20px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .page-header {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0;
        }

        .breadcrumb {
            background: none;
            padding: 0;
            margin: 12px 0 0 0;
        }

        .breadcrumb-item {
            color: #6b7280;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
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
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 12px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--dark-color);
            margin-bottom: 8px;
            line-height: 1;
        }

        .stat-label {
            font-size: 1rem;
            color: #6b7280;
            font-weight: 500;
        }

        .stat-change {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-top: 12px;
        }

        .stat-change.positive {
            background: #10b98120;
            color: var(--success-color);
        }

        .stat-change.negative {
            background: #ef444420;
            color: var(--danger-color);
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }

        .card-header {
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            border-bottom: 1px solid #e5e7eb;
            border-radius: 16px 16px 0 0 !important;
            font-weight: 600;
            padding: 20px 28px;
        }

        .table {
            border-radius: 12px;
            overflow: hidden;
        }

        .table thead th {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.5px;
            padding: 16px;
        }

        .badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
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

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            border-radius: 25px;
            padding-left: 50px;
            border: 2px solid #e5e7eb;
            font-size: 0.95rem;
        }

        .search-box input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.1);
        }

        .search-box i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
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
            font-weight: 700;
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 70px;
                left: -280px;
                width: 280px;
                height: calc(100vh - 70px);
                z-index: 1000;
                transition: left 0.3s ease;
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-3">
            <a class="navbar-brand" href="/super-admin/dashboard">
                <i class="fas fa-crown me-2"></i>
                Super Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="super-admin-badge me-3">Super Admin</span>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <div class="user-avatar me-2">SA</div>
                            {{ Auth::user()->name }}
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
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <nav class="nav flex-column mt-4">
                    <a class="nav-link active" href="/super-admin/dashboard">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard
                    </a>
                    <a class="nav-link" href="/super-admin/schools">
                        <i class="fas fa-school me-2"></i>
                        Schools
                    </a>
                    <a class="nav-link" href="/super-admin/schools/create">
                        <i class="fas fa-plus-circle me-2"></i>
                        Add School
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Page Header -->
                <div class="page-header">
                    <h1 class="page-title">Super Admin Dashboard</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">Super Admin</li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </nav>
                </div>

                <!-- Statistics Grid -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-school"></i>
                        </div>
                        <div class="stat-number">{{ $stats['total_schools'] }}</div>
                        <div class="stat-label">Total Schools</div>
                        <div class="stat-change {{ $stats['schools_change'] > 0 ? 'positive' : 'negative' }}">
                            <i class="fas fa-arrow-{{ $stats['schools_change'] > 0 ? 'up' : 'down' }} me-1"></i>
                            {{ $stats['schools_change'] > 0 ? '+' : '' }}{{ abs($stats['schools_change']) }} this month
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="background: var(--success-color);">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-number">{{ $stats['active_schools'] }}</div>
                        <div class="stat-label">Active Schools</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up me-1"></i>
                            {{ $stats['active_percentage'] }}% active
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="background: var(--warning-color);">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="stat-number">{{ $stats['total_students'] }}</div>
                        <div class="stat-label">Total Students</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up me-1"></i>
                            +{{ $stats['students_change'] }} this week
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="background: var(--danger-color);">
                            <i class="fas fa-user-times"></i>
                        </div>
                        <div class="stat-number">{{ $stats['suspended_schools'] }}</div>
                        <div class="stat-label">Suspended Schools</div>
                        <div class="stat-change {{ $stats['suspended_change'] > 0 ? 'negative' : 'positive' }}">
                            <i class="fas fa-arrow-{{ $stats['suspended_change'] > 0 ? 'down' : 'up' }} me-1"></i>
                            {{ $stats['suspended_change'] > 0 ? '+' : '' }}{{ abs($stats['suspended_change']) }} this month
                        </div>
                    </div>
                </div>

                <!-- Recent Schools & Quick Actions -->
                <div class="row">
                    <div class="col-xl-8 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Recently Added Schools</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>School Name</th>
                                                <th>Domain</th>
                                                <th>Status</th>
                                                <th>Students</th>
                                                <th>Added Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($stats['recent_schools'] as $school)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="user-avatar me-3" style="background: var(--primary-color);">
                                                            {{ substr($school->name, 0, 2) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold">{{ $school->name }}</div>
                                                            <small class="text-muted">{{ $school->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <code>{{ $school->subdomain }}</code>
                                                </td>
                                                <td>
                                                    @if($school->status === 'active')
                                                        <span class="badge badge-success">Active</span>
                                                    @else
                                                        <span class="badge badge-warning">Suspended</span>
                                                    @endif
                                                </td>
                                                <td>{{ $school->students_count ?? 0 }}</td>
                                                <td>{{ $school->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="/super-admin/schools/{{ $school->id }}/stats" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-chart-line"></i>
                                                        </a>
                                                        <a href="/super-admin/schools/{{ $school->id }}/edit" class="btn btn-sm btn-outline-secondary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Quick Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-3">
                                    <a href="/super-admin/schools/create" class="btn btn-primary">
                                        <i class="fas fa-plus-circle me-2"></i>
                                        Add New School
                                    </a>
                                    <a href="/super-admin/schools" class="btn btn-outline-primary">
                                        <i class="fas fa-school me-2"></i>
                                        Manage Schools
                                    </a>
                                    <a href="/super-admin/export" class="btn btn-outline-primary">
                                        <i class="fas fa-download me-2"></i>
                                        Export Reports
                                    </a>
                                    <a href="/super-admin/settings" class="btn btn-outline-primary">
                                        <i class="fas fa-cog me-2"></i>
                                        Platform Settings
                                    </a>
                                </div>

                                <hr class="my-4">

                                <div class="text-center">
                                    <h6 class="text-muted mb-3">System Health</h6>
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="p-3 bg-success bg-opacity-10 rounded">
                                                <i class="fas fa-server text-success fs-4 mb-2"></i>
                                                <div class="fw-bold">Healthy</div>
                                                <small class="text-muted">Server</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-3 bg-success bg-opacity-10 rounded">
                                                <i class="fas fa-database text-success fs-4 mb-2"></i>
                                                <div class="fw-bold">Normal</div>
                                                <small class="text-muted">Database</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Logout Form -->
    <form id="logout-form" action="/logout" method="POST" style="display: none;">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
