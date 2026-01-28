<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New School - Super Admin Panel</title>
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

        .form-container {
            background: white;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 20px;
            padding-bottom: 10px;
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

        .info-box {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-left: 4px solid var(--primary-color);
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 24px;
        }

        .info-box h6 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 8px;
        }

        .info-box p {
            color: #6b7280;
            margin: 0;
            font-size: 0.9rem;
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
                    <a class="nav-link" href="/super-admin/schools">
                        <i class="fas fa-school me-2"></i>
                        Schools
                    </a>
                    <a class="nav-link active" href="/super-admin/schools/create">
                        <i class="fas fa-plus-circle me-2"></i>
                        Add School
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Page Header -->
                <div class="page-header">
                    <h1 class="page-title">Add New School</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">Super Admin</li>
                            <li class="breadcrumb-item"><a href="/super-admin/schools">Schools</a></li>
                            <li class="breadcrumb-item active">Add New School</li>
                        </ol>
                    </nav>
                </div>

                <!-- Form Container -->
                <div class="form-container">
                    <form method="POST" action="/super-admin/schools">
                        @csrf
                        
                        <!-- School Information -->
                        <div class="mb-5">
                            <h5 class="section-title">
                                <i class="fas fa-school me-2"></i>
                                School Information
                            </h5>
                            
                            <div class="info-box">
                                <h6><i class="fas fa-info-circle me-2"></i>Important</h6>
                                <p>Fill in the basic information about the school. The domain will be used for the school's unique subdomain.</p>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">School Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" required 
                                           placeholder="e.g., Springfield High School">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="domain" class="form-label">Domain *</label>
                                    <input type="text" class="form-control" id="domain" name="domain" required 
                                           placeholder="e.g., springfield" pattern="[a-z0-9-]+" title="Only lowercase letters, numbers, and hyphens">
                                    <div class="form-text">This will be used as: springfield.yourdomain.com</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">School Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" required 
                                           placeholder="contact@springfield.com">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           placeholder="+1 (555) 123-4567">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3" 
                                          placeholder="123 Main Street, Springfield, IL 62701"></textarea>
                            </div>
                        </div>

                        <!-- School Admin Account -->
                        <div class="mb-5">
                            <h5 class="section-title">
                                <i class="fas fa-user-shield me-2"></i>
                                School Admin Account
                            </h5>
                            
                            <div class="info-box">
                                <h6><i class="fas fa-info-circle me-2"></i>School Admin</h6>
                                <p>Create the first administrator account for this school. They will have full access to manage their school's data.</p>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="admin_name" class="form-label">Admin Name *</label>
                                    <input type="text" class="form-control" id="admin_name" name="admin_name" required 
                                           placeholder="John Smith">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="admin_email" class="form-label">Admin Email *</label>
                                    <input type="email" class="form-control" id="admin_email" name="admin_email" required 
                                           placeholder="admin@springfield.com">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="admin_password" class="form-label">Admin Password *</label>
                                    <input type="password" class="form-control" id="admin_password" name="admin_password" required 
                                           minlength="6" placeholder="••••••">
                                    <div class="form-text">Minimum 6 characters</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="admin_password_confirmation" class="form-label">Confirm Password *</label>
                                    <input type="password" class="form-control" id="admin_password_confirmation" name="admin_password_confirmation" required 
                                           minlength="6" placeholder="••••••">
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="/super-admin/schools" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Create School
                            </button>
                        </div>
                    </form>
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
        // Auto-generate domain from school name
        document.getElementById('name').addEventListener('input', function() {
            const schoolName = this.value.toLowerCase();
            const domain = schoolName.replace(/[^a-z0-9\s]/g, '').replace(/\s+/g, '-');
            document.getElementById('domain').value = domain;
        });

        // Password confirmation validation
        document.getElementById('admin_password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('admin_password').value;
            const confirmation = this.value;
            
            if (password !== confirmation) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });

        document.getElementById('admin_password').addEventListener('input', function() {
            const confirmation = document.getElementById('admin_password_confirmation');
            if (confirmation.value) {
                if (this.value !== confirmation.value) {
                    confirmation.setCustomValidity('Passwords do not match');
                } else {
                    confirmation.setCustomValidity('');
                }
            }
        });
    </script>
</body>
</html>
