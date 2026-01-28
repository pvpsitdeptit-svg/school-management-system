@extends('layouts.app')

@section('title', 'Add New Parent')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Add New Parent</h5>
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

                    <!-- Form -->
                    <form action="{{ route('parents.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <!-- Parent Information -->
                            <div class="col-md-6">
                                <h6 class="mb-3">Parent Information</h6>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password *</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="relationship" class="form-label">Relationship *</label>
                                    <select class="form-select @error('relationship') is-invalid @enderror" 
                                            id="relationship" name="relationship" required>
                                        <option value="">Select Relationship</option>
                                        <option value="father" {{ old('relationship') == 'father' ? 'selected' : '' }}>Father</option>
                                        <option value="mother" {{ old('relationship') == 'mother' ? 'selected' : '' }}>Mother</option>
                                        <option value="guardian" {{ old('relationship') == 'guardian' ? 'selected' : '' }}>Guardian</option>
                                    </select>
                                    @error('relationship')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="occupation" class="form-label">Occupation</label>
                                    <input type="text" class="form-control @error('occupation') is-invalid @enderror" 
                                           id="occupation" name="occupation" value="{{ old('occupation') }}">
                                    @error('occupation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" name="address" rows="3">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Student Assignment -->
                            <div class="col-md-6">
                                <h6 class="mb-3">Student Assignment</h6>
                                
                                <div class="mb-3">
                                    <label class="form-label">Select Students *</label>
                                    <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                        @forelse($students as $student)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input @error('students') is-invalid @enderror" 
                                                       type="checkbox" name="students[]" 
                                                       value="{{ $student->id }}" id="student_{{ $student->id }}"
                                                       {{ in_array($student->id, old('students', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="student_{{ $student->id }}">
                                                    <strong>{{ $student->user->name }}</strong> 
                                                    <span class="text-muted">({{ $student->admission_no }})</span>
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ $student->schoolClass->name }} - {{ $student->schoolClass->section }}
                                                    </small>
                                                </label>
                                            </div>
                                        @empty
                                            <p class="text-muted">No students available. Please add students first.</p>
                                        @endforelse
                                    </div>
                                    @error('students')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="primary_contact_student" class="form-label">Primary Contact Student</label>
                                    <select class="form-select @error('primary_contact_student') is-invalid @enderror" 
                                            id="primary_contact_student" name="primary_contact_student">
                                        <option value="">Select Primary Contact (Optional)</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}" 
                                                    {{ old('primary_contact_student') == $student->id ? 'selected' : '' }}>
                                                {{ $student->user->name }} ({{ $student->admission_no }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">
                                        This student will be marked as the primary contact for notifications.
                                    </small>
                                    @error('primary_contact_student')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('parents.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Back to Parents
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Create Parent
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle student selection
    const studentCheckboxes = document.querySelectorAll('input[name="students[]"]');
    const primaryContactSelect = document.getElementById('primary_contact_student');
    
    studentCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const option = primaryContactSelect.querySelector(`option[value="${this.value}"]`);
            if (option) {
                option.style.display = this.checked ? 'block' : 'none';
                if (!this.checked && primaryContactSelect.value === this.value) {
                    primaryContactSelect.value = '';
                }
            }
        });
        
        // Initialize visibility
        const option = primaryContactSelect.querySelector(`option[value="${checkbox.value}"]`);
        if (option) {
            option.style.display = checkbox.checked ? 'block' : 'none';
        }
    });
});
</script>
@endsection
@endsection
