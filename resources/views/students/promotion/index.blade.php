@extends('layouts.app')

@section('title', 'Student Promotion')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Student Promotion</h5>
                    <div>
                        <a href="{{ route('students.promotion.history') }}" class="btn btn-outline-info">
                            <i class="fas fa-history"></i> Promotion History
                        </a>
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Students
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Promotion Form -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Select Class</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="from_class" class="form-label">Current Class</label>
                                        <select class="form-select" id="from_class" name="from_class">
                                            <option value="">Select Class</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->id }}">{{ $class->name }} - {{ $class->section }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <button type="button" id="loadStudents" class="btn btn-primary w-100" disabled>
                                        <i class="fas fa-users"></i> Load Students
                                    </button>
                                </div>
                            </div>

                            <!-- Bulk Promotion -->
                            <div class="card mt-3" id="bulkPromotionCard" style="display: none;">
                                <div class="card-header">
                                    <h6 class="mb-0">Bulk Promotion</h6>
                                </div>
                                <div class="card-body">
                                    <form id="bulkPromotionForm" method="POST" action="{{ route('students.promotion.bulk-promote') }}">
                                        @csrf
                                        <input type="hidden" name="from_class_id" id="bulk_from_class_id">
                                        
                                        <div class="mb-3">
                                            <label for="bulk_to_class" class="form-label">Promote To Class</label>
                                            <select class="form-select" name="to_class_id" id="bulk_to_class" required>
                                                <option value="">Select Target Class</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="bulk_academic_year" class="form-label">Academic Year</label>
                                            <input type="text" class="form-control" name="academic_year" 
                                                   value="{{ $academicYear }}" readonly>
                                        </div>

                                        <div class="mb-3">
                                            <label for="bulk_from_date" class="form-label">Promotion Date</label>
                                            <input type="date" class="form-control" name="from_date" 
                                                   value="{{ date('Y-m-d') }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="bulk_remarks" class="form-label">Remarks</label>
                                            <textarea class="form-control" name="remarks" rows="2" 
                                                      placeholder="Optional remarks..."></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="fas fa-arrow-up"></i> Promote All Students
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card" id="studentsCard" style="display: none;">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Students to Promote</h6>
                                    <span class="badge bg-primary" id="studentCount">0 students</span>
                                </div>
                                <div class="card-body">
                                    <div id="studentsList"></div>
                                    
                                    <div class="mt-3" id="individualPromotionSection" style="display: none;">
                                        <button type="button" id="promoteSelected" class="btn btn-primary">
                                            <i class="fas fa-arrow-up"></i> Promote Selected Students
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Loading State -->
                            <div class="card" id="loadingCard" style="display: none;">
                                <div class="card-body text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2 mb-0">Loading students...</p>
                                </div>
                            </div>

                            <!-- Empty State -->
                            <div class="card" id="emptyCard" style="display: none;">
                                <div class="card-body text-center py-5">
                                    <i class="fas fa-school fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Select a Class</h5>
                                    <p class="text-muted">Choose a class to view students for promotion.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fromClassSelect = document.getElementById('from_class');
    const loadStudentsBtn = document.getElementById('loadStudents');
    const studentsCard = document.getElementById('studentsCard');
    const loadingCard = document.getElementById('loadingCard');
    const emptyCard = document.getElementById('emptyCard');
    const bulkPromotionCard = document.getElementById('bulkPromotionCard');
    const studentsList = document.getElementById('studentsList');
    const studentCount = document.getElementById('studentCount');
    const promoteSelectedBtn = document.getElementById('promoteSelected');
    const individualPromotionSection = document.getElementById('individualPromotionSection');

    // Show empty state initially
    emptyCard.style.display = 'block';

    // Enable/disable load students button
    fromClassSelect.addEventListener('change', function() {
        loadStudentsBtn.disabled = !this.value;
    });

    // Load students
    loadStudentsBtn.addEventListener('click', function() {
        const classId = fromClassSelect.value;
        if (!classId) return;

        // Show loading
        emptyCard.style.display = 'none';
        studentsCard.style.display = 'none';
        loadingCard.style.display = 'block';

        fetch(`{{ route('students.promotion.get-students') }}?class_id=${classId}`)
            .then(response => response.json())
            .then(data => {
                loadingCard.style.display = 'none';
                
                if (data.students.length === 0) {
                    emptyCard.style.display = 'block';
                    emptyCard.querySelector('h5').textContent = 'No Students Found';
                    emptyCard.querySelector('p').textContent = 'There are no active students in this class.';
                    return;
                }

                // Show students card
                studentsCard.style.display = 'block';
                studentCount.textContent = `${data.students.length} students`;

                // Show bulk promotion card
                bulkPromotionCard.style.display = 'block';
                document.getElementById('bulk_from_class_id').value = classId;

                // Populate target class options
                const bulkToClassSelect = document.getElementById('bulk_to_class');
                bulkToClassSelect.innerHTML = '<option value="">Select Target Class</option>';
                data.availableClasses.forEach(cls => {
                    bulkToClassSelect.innerHTML += `<option value="${cls.id}">${cls.name} - ${cls.section}</option>`;
                });

                // Populate students list
                studentsList.innerHTML = `
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    <th>Student</th>
                                    <th>Admission No</th>
                                    <th>Current Class</th>
                                    <th>Promote To</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.students.map(student => `
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="form-check-input student-checkbox" 
                                                   value="${student.id}" data-student='${JSON.stringify(student)}'>
                                        </td>
                                        <td>
                                            <strong>${student.user.name}</strong>
                                        </td>
                                        <td>${student.admission_no}</td>
                                        <td>${student.school_class.name} - ${student.school_class.section}</td>
                                        <td>
                                            <select class="form-select promotion-target" data-student-id="${student.id}">
                                                <option value="">Select Class</option>
                                                ${data.availableClasses.map(cls => `
                                                    <option value="${cls.id}">${cls.name} - ${cls.section}</option>
                                                `).join('')}
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control promotion-remarks" 
                                                   placeholder="Optional..." data-student-id="${student.id}">
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;

                // Show individual promotion section
                individualPromotionSection.style.display = 'block';
            })
            .catch(error => {
                loadingCard.style.display = 'none';
                console.error('Error:', error);
                alert('Error loading students. Please try again.');
            });
    });

    // Select all functionality
    document.addEventListener('change', function(e) {
        if (e.target.id === 'selectAll') {
            const checkboxes = document.querySelectorAll('.student-checkbox');
            checkboxes.forEach(cb => cb.checked = e.target.checked);
        }
    });

    // Promote selected students
    promoteSelectedBtn.addEventListener('click', function() {
        const selectedCheckboxes = document.querySelectorAll('.student-checkbox:checked');
        if (selectedCheckboxes.length === 0) {
            alert('Please select at least one student to promote.');
            return;
        }

        const promotions = [];
        let hasErrors = false;

        selectedCheckboxes.forEach(checkbox => {
            const student = JSON.parse(checkbox.dataset.student);
            const targetClass = document.querySelector(`.promotion-target[data-student-id="${student.id}"]`).value;
            const remarks = document.querySelector(`.promotion-remarks[data-student-id="${student.id}"]`).value;

            if (!targetClass) {
                hasErrors = true;
                return;
            }

            promotions.push({
                student_id: student.id,
                to_class_id: targetClass,
                academic_year: '{{ $academicYear }}',
                from_date: '{{ date('Y-m-d') }}',
                remarks: remarks
            });
        });

        if (hasErrors) {
            alert('Please select target class for all selected students.');
            return;
        }

        // Send promotion request
        fetch('{{ route('students.promotion.promote') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ promotions: promotions })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error promoting students. Please try again.');
        });
    });
});
</script>
@endsection
@endsection
