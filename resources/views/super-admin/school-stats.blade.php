<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Statistics - Super Admin Panel</title>
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
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
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
            padding: 40px;
        }

        .page-header {
            background: white;
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 32px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }

        .page-title {
            font-size: 2rem;
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
            padding: 28px;
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
            width: 64px;
            height: 64px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 20px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }

        .stat-number {
            font-size: 2.5rem;
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

        .school-info {
            background: white;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            border: none;
            margin-bottom: 32px;
        }

        .school-name {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 8px;
        }

        .school-domain {
            font-size: 1.1rem;
            color: #6b7280;
            margin-bottom: 16px;
            font-family: 'Courier New', monospace;
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
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="/super-admin/dashboard">
                <i class="fas fa-crown me-2"></i>
                Super Admin Panel
            </a>
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
                    <a class="nav-link" href="/super-admin/dashboard">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard
                    </a>
                    <a class="nav-link active" href="/super-admin/schools">
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
                    <div class="row align-items-center">
                        <div class="col">
                            <h1 class="page-title">School Statistics</h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">Super Admin</li>
                                    <li class="breadcrumb-item"><a href="/super-admin/schools">Schools</a></li>
                                    <li class="breadcrumb-item active">{{ $school->name }} Statistics</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="col-auto">
                            <a href="/super-admin/schools/{{ $school->id }}/edit" class="btn btn-primary me-2">
                                <i class="fas fa-edit me-2"></i>
                                Edit School
                            </a>
                            <a href="/super-admin/schools" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to Schools
                            </a>
                        </div>
                    </div>
                </div>

                <!-- School Information -->
                <div class="school-info">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-3" style="background: var(--primary-color); width: 60px; height: 60px; font-size: 1.5rem;">
                                    {{ substr($school->name, 0, 2) }}
                                </div>
                                <div>
                                    <div class="school-name">{{ $school->name }}</div>
                                    <div class="school-domain">{{ $school->subdomain }}.yourdomain.com</div>
                                    <div class="d-flex align-items-center mt-2">
                                        @if($school->status === 'active')
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-warning">Suspended</span>
                                        @endif
                                        <span class="text-muted ms-3">Created: {{ $school->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="text-muted mb-2">School ID: #{{ $school->id }}</div>
                            @if($school->email)
                                <div class="text-muted">{{ $school->email }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Statistics Grid -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="stat-number">{{ $stats['total_students'] }}</div>
                        <div class="stat-label">Total Students</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="background: var(--success-color);">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="stat-number">{{ $stats['total_faculty'] }}</div>
                        <div class="stat-label">Total Faculty</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="background: var(--warning-color);">
                            <i class="fas fa-door-open"></i>
                        </div>
                        <div class="stat-number">{{ $stats['total_classes'] }}</div>
                        <div class="stat-label">Total Classes</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="background: var(--danger-color);">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="stat-number">{{ $stats['total_subjects'] }}</div>
                        <div class="stat-label">Total Subjects</div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Recent Activity</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-info-circle text-primary me-3"></i>
                                    <span>No recent activity recorded</span>
                                </div>
                                <small class="text-muted">Activity tracking coming soon</small>
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
