@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar">
            <nav class="nav flex-column mt-4">
                <a class="nav-link active" href="/dashboard">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
                <a class="nav-link" href="/students">
                    <i class="fas fa-user-graduate me-2"></i>
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
                <h1 class="page-title">Dashboard</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </nav>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Students
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $stats['total_students'] }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Total Faculty
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $stats['total_faculty'] }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Total Classes
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $stats['total_classes'] }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-door-open fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Total Subjects
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $stats['total_subjects'] }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-book fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history me-2"></i>
                        Recent Activities
                    </h6>
                </div>
                <div class="card-body">
                    @if(count($recentActivities) > 0)
                        @foreach($recentActivities as $activity)
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <div class="icon-circle bg-{{ $activity['color'] }} text-white">
                                        <i class="fas fa-{{ $activity['icon'] }}"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="small text-gray-500">{{ $activity['message'] }}</div>
                                    <div class="text-xs text-gray-400">{{ $activity['time'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-gray-500 py-3">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>No recent activities found.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>
                        Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="/students/create" class="btn btn-primary btn-block">
                                <i class="fas fa-user-plus me-2"></i>
                                Add Student
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="/faculty/create" class="btn btn-success btn-block">
                                <i class="fas fa-user-tie me-2"></i>
                                Add Faculty
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="/attendance/mark" class="btn btn-info btn-block">
                                <i class="fas fa-calendar-check me-2"></i>
                                Mark Attendance
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="/exams/create" class="btn btn-warning btn-block">
                                <i class="fas fa-file-alt me-2"></i>
                                Create Exam
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.icon-circle {
    height: 2.5rem;
    width: 2.5rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-success {
    background-color: #1cc88a !important;
}

.bg-info {
    background-color: #36b9cc !important;
}

.bg-warning {
    background-color: #f6c23e !important;
}

.bg-primary {
    background-color: #4e73df !important;
}
</style>
@endsection
