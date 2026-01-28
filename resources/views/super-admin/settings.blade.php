<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platform Settings - Super Admin Panel</title>
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

        .settings-card {
            background: white;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            border: none;
            margin-bottom: 24px;
        }

        .settings-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e5e7eb;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e5e7eb;
            padding: 12px 16px;
            font-size: 0.95rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.1);
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 8px;
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

        .btn-outline-secondary {
            border: 2px solid #e5e7eb;
            color: #6b7280;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background: #6b7280;
            color: white;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: var(--danger-color);
            border: none;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
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

        .info-box {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-left: 4px solid var(--primary-color);
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 24px;
        }

        .info-box.warning {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border-left: 4px solid var(--warning-color);
        }

        .info-box.danger {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border-left: 4px solid var(--danger-color);
        }

        .info-box h6 {
            font-weight: 600;
            margin-bottom: 8px;
        }

        .info-box p {
            margin: 0;
            font-size: 0.9rem;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
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
            transition: .4s;
            border-radius: 24px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: var(--success-color);
        }

        input:checked + .slider:before {
            transform: translateX(26px);
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
                    <a class="nav-link" href="/super-admin/dashboard">
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
                    <a class="nav-link" href="/super-admin/export">
                        <i class="fas fa-download me-2"></i>
                        Export Reports
                    </a>
                    <a class="nav-link active" href="/super-admin/settings">
                        <i class="fas fa-cog me-2"></i>
                        Platform Settings
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Page Header -->
                <div class="page-header">
                    <h1 class="page-title">Platform Settings</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">Super Admin</li>
                            <li class="breadcrumb-item active">Platform Settings</li>
                        </ol>
                    </nav>
                </div>

                <!-- General Settings -->
                <div class="settings-card">
                    <h5 class="settings-title">
                        <i class="fas fa-cog me-2"></i>
                        General Settings
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="platform_name" class="form-label">Platform Name</label>
                            <input type="text" class="form-control" id="platform_name" value="School Management System">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="admin_email" class="form-label">Admin Email</label>
                            <input type="email" class="form-control" id="admin_email" value="admin@sms.com">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="default_timezone" class="form-label">Default Timezone</label>
                            <select class="form-select" id="default_timezone">
                                <option value="UTC">UTC</option>
                                <option value="America/New_York">America/New_York</option>
                                <option value="Europe/London">Europe/London</option>
                                <option value="Asia/Kolkata" selected>Asia/Kolkata</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="default_currency" class="form-label">Default Currency</label>
                            <select class="form-select" id="default_currency">
                                <option value="USD" selected>USD</option>
                                <option value="EUR">EUR</option>
                                <option value="GBP">GBP</option>
                                <option value="INR">INR</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="platform_description" class="form-label">Platform Description</label>
                            <textarea class="form-control" id="platform_description" rows="3">Comprehensive school management system for educational institutions.</textarea>
                        </div>
                    </div>
                </div>

                <!-- Email Settings -->
                <div class="settings-card">
                    <h5 class="settings-title">
                        <i class="fas fa-envelope me-2"></i>
                        Email Settings
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="mail_driver" class="form-label">Mail Driver</label>
                            <select class="form-select" id="mail_driver">
                                <option value="smtp" selected>SMTP</option>
                                <option value="mail">Mail</option>
                                <option value="sendmail">Sendmail</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="mail_host" class="form-label">Mail Host</label>
                            <input type="text" class="form-control" id="mail_host" value="smtp.gmail.com">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="mail_port" class="form-label">Mail Port</label>
                            <input type="number" class="form-control" id="mail_port" value="587">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="mail_encryption" class="form-label">Encryption</label>
                            <select class="form-select" id="mail_encryption">
                                <option value="tls" selected>TLS</option>
                                <option value="ssl">SSL</option>
                                <option value="">None</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="mail_username" class="form-label">Mail Username</label>
                            <input type="email" class="form-control" id="mail_username" placeholder="your-email@gmail.com">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="mail_password" class="form-label">Mail Password</label>
                            <input type="password" class="form-control" id="mail_password" placeholder="App password">
                        </div>
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="settings-card">
                    <h5 class="settings-title">
                        <i class="fas fa-shield-alt me-2"></i>
                        Security Settings
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Two-Factor Authentication</label>
                            <div class="d-flex align-items-center">
                                <label class="switch">
                                    <input type="checkbox" checked>
                                    <span class="slider"></span>
                                </label>
                                <span class="ms-3">Enable 2FA for admin accounts</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Session Timeout (minutes)</label>
                            <input type="number" class="form-control" id="session_timeout" value="120" min="15" max="480">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password Policy</label>
                            <select class="form-select" id="password_policy">
                                <option value="medium" selected>Medium (8 chars)</option>
                                <option value="strong">Strong (12 chars)</option>
                                <option value="weak">Weak (6 chars)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Login Attempts</label>
                            <input type="number" class="form-control" id="login_attempts" value="5" min="3" max="10">
                        </div>
                    </div>
                </div>

                <!-- Backup Settings -->
                <div class="settings-card">
                    <h5 class="settings-title">
                        <i class="fas fa-database me-2"></i>
                        Backup Settings
                    </h5>
                    
                    <div class="info-box">
                        <h6><i class="fas fa-info-circle me-2"></i>Backup Information</h6>
                        <p>Configure automatic database backups to ensure data safety and recovery options.</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Auto Backup</label>
                            <div class="d-flex align-items-center">
                                <label class="switch">
                                    <input type="checkbox" checked>
                                    <span class="slider"></span>
                                </label>
                                <span class="ms-3">Enable automatic backups</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="backup_frequency" class="form-label">Backup Frequency</label>
                            <select class="form-select" id="backup_frequency">
                                <option value="daily" selected>Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="backup_retention" class="form-label">Retention Period (days)</label>
                            <input type="number" class="form-control" id="backup_retention" value="30" min="7" max="365">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="backup_location" class="form-label">Backup Location</label>
                            <input type="text" class="form-control" id="backup_location" value="/storage/backups">
                        </div>
                    </div>

                    <div class="mt-3">
                        <button class="btn btn-primary me-2">
                            <i class="fas fa-save me-2"></i>
                            Save Settings
                        </button>
                        <button class="btn btn-outline-secondary">
                            <i class="fas fa-download me-2"></i>
                            Download Backup
                        </button>
                        <button class="btn btn-danger">
                            <i class="fas fa-history me-2"></i>
                            Restore Backup
                        </button>
                    </div>
                </div>

                <!-- Maintenance Mode -->
                <div class="settings-card">
                    <h5 class="settings-title">
                        <i class="fas fa-tools me-2"></i>
                        Maintenance Mode
                    </h5>
                    
                    <div class="info-box danger">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Warning</h6>
                        <p>Maintenance mode will disable user access to the platform. Only Super Admins will be able to access the system.</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Maintenance Mode</label>
                            <div class="d-flex align-items-center">
                                <label class="switch">
                                    <input type="checkbox">
                                    <span class="slider"></span>
                                </label>
                                <span class="ms-3">Enable maintenance mode</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="maintenance_message" class="form-label">Maintenance Message</label>
                            <input type="text" class="form-control" id="maintenance_message" value="System is under maintenance. Please try again later.">
                        </div>
                    </div>

                    <div class="mt-3">
                        <button class="btn btn-danger">
                            <i class="fas fa-power-off me-2"></i>
                            Enable Maintenance Mode
                        </button>
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
    <script>
        // Form submission handler
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show success message
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show';
            alertDiv.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>
                Settings saved successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.querySelector('.main-content').insertBefore(alertDiv, document.querySelector('.main-content').firstChild);
            
            // Auto-hide after 3 seconds
            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
        });
    </script>
</body>
</html>
