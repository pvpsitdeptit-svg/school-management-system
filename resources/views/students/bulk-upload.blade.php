<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Upload Students - School Management System</title>
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

        .upload-card {
            background: white;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: 2px dashed #e0e0e0;
            text-align: center;
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }

        .upload-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .upload-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            margin: 0 auto 20px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(21, 101, 192, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success-color) 0%, #66BB6A 100%);
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(21, 101, 192, 0.3);
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #e0e0e0;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(21, 101, 192, 0.1);
        }

        .info-box {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-left: 4px solid var(--primary-color);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .info-box h6 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 10px;
        }

        .template-preview {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
        }

        .template-preview table {
            font-size: 0.9rem;
        }

        .template-preview th {
            background: var(--primary-color);
            color: white;
            padding: 8px;
        }

        .template-preview td {
            padding: 8px;
            border: 1px solid #dee2e6;
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
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-graduation-cap me-2"></i>
                School Management System
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <div class="user-avatar me-2">{{ substr(Auth::user()->name, 0, 1) }}</div>
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
                <nav class="nav flex-column mt-3">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard
                    </a>
                    <a class="nav-link active" href="{{ route('students.index') }}">
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
                            <h1 class="page-title">Bulk Upload Students</h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Students</a></li>
                                    <li class="breadcrumb-item active">Bulk Upload</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('students.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to Students
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="info-box">
                    <h6><i class="fas fa-info-circle me-2"></i>How to Bulk Upload Students</h6>
                    <ol class="mb-0">
                        <li>Download the Excel template using the button below</li>
                        <li>Fill in student details in the template (required fields: first_name, last_name, email)</li>
                        <li>Select the class for all students in this upload</li>
                        <li>Upload the completed file</li>
                        <li>Review the results and fix any errors if needed</li>
                    </ol>
                </div>

                <!-- Upload Form -->
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('students.process-bulk-upload') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Select Class *</label>
                                    <select name="class_id" class="form-select" required>
                                        <option value="">Select Class</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}{{ $class->section ? ' - Section ' . $class->section : '' }}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">All students in this upload will be assigned to this class</div>
                                </div>
                                
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Upload File *</label>
                                    <input type="file" name="file" class="form-control" accept=".csv" required>
                                    <div class="form-text">Supported format: CSV (.csv) only</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="upload-card">
                                        <div class="upload-icon">
                                            <i class="fas fa-file-excel"></i>
                                        </div>
                                        <h4>Drop your CSV file here or click to browse</h4>
                                        <p class="text-muted mb-3">Maximum file size: 10MB</p>
                                        <button type="button" class="btn btn-outline-primary" onclick="document.querySelector('input[type=file]').click()">
                                            <i class="fas fa-folder-open me-2"></i>
                                            Choose CSV File
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <a href="{{ route('students.download-template') }}" class="btn btn-success">
                                                <i class="fas fa-download me-2"></i>
                                                Download Template
                                            </a>
                                        </div>
                                        <div>
                                            <a href="{{ route('students.index') }}" class="btn btn-outline-secondary me-2">
                                                <i class="fas fa-times me-2"></i>
                                                Cancel
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-upload me-2"></i>
                                                Upload Students
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Template Preview -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-table me-2"></i>
                            Template Format Preview
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">The CSV template should contain the following columns:</p>
                        
                        <div class="template-preview">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>first_name *</th>
                                        <th>last_name *</th>
                                        <th>email *</th>
                                        <th>phone</th>
                                        <th>date_of_birth</th>
                                        <th>gender</th>
                                        <th>address</th>
                                        <th>password</th>
                                        <th>status</th>
                                        <th>admission_no</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>John</td>
                                        <td>Doe</td>
                                        <td>john.doe@school.com</td>
                                        <td>+1234567890</td>
                                        <td>2005-05-15</td>
                                        <td>male</td>
                                        <td>123 Main Street</td>
                                        <td>student123</td>
                                        <td>active</td>
                                        <td>STU20240001</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Notes:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Fields marked with * are required</li>
                                <li>If admission_no is not provided, it will be generated automatically</li>
                                <li>If password is not provided, default password "password123" will be used</li>
                                <li>Gender options: male, female, other</li>
                                <li>Status options: active, inactive, graduated</li>
                                <li>Date format: YYYY-MM-DD (e.g., 2005-05-15)</li>
                            </ul>
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
    <script>
        // File upload preview
        document.querySelector('input[type=file]').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                document.querySelector('.upload-card h4').textContent = 'Selected file: ' + fileName;
                document.querySelector('.upload-card p').textContent = 'Click "Upload Students" to process';
            }
        });

        // Drag and drop functionality
        const uploadCard = document.querySelector('.upload-card');
        
        uploadCard.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadCard.style.borderColor = 'var(--primary-color)';
            uploadCard.style.backgroundColor = '#f8f9ff';
        });

        uploadCard.addEventListener('dragleave', (e) => {
            e.preventDefault();
            uploadCard.style.borderColor = '#e0e0e0';
            uploadCard.style.backgroundColor = 'transparent';
        });

        uploadCard.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadCard.style.borderColor = '#e0e0e0';
            uploadCard.style.backgroundColor = 'transparent';
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                document.querySelector('input[type=file]').files = files;
                const event = new Event('change', { bubbles: true });
                document.querySelector('input[type=file]').dispatchEvent(event);
            }
        });
    </script>
</body>
</html>
