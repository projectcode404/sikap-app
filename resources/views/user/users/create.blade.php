@extends('layouts.app')

@section('content')
<form method="POST" action="{{ isset($user) ? route('user.users.update', $user) : route('user.users.store') }}">
    @csrf
    @if(isset($user))
        @method('PATCH')
    @endif

    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Start Card -->
                <div class="card shadow-sm card-outline mb-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ isset($user) ? 'Edit User' : 'Create User' }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="employee_id">Employee ID</label>
                            <select id="employee_id" name="employee_id" class="form-select" placeholder="Search NIK ..." @if(isset($user)) disabled @endif></select>
                            @if(isset($user))
                                <input type="hidden" name="employee_id" value="{{ $user->employee_id }}">
                            @endif
                            <input type="hidden" id="preview_password" name="preview_password" value="Iapsby{{ old('employee_id') ?? '' }}">
                        </div>

                        <div class="mb-3">
                            <label for="roles">Roles</label>
                            <select name="roles[]" id="roles" class="form-select" multiple required>
                                @foreach($roles as $role)
                                <option value="{{ $role->name }}" @if(old('roles')) {{ in_array($role->name, old('roles')) ? 'selected' : '' }} @elseif(isset($user)) {{ $user->hasRole($role->name) ? 'selected' : '' }} @endif>
                                    {{ ucfirst($role->name) }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        @if(isset($user))
                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" class="form-select" required>
                                <option value="active" {{ old('status', $user->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $user->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        @endif
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('user.users.index') }}" class="btn btn-outline-primary">Cancel</a>

                            <div class="d-flex gap-2">
                                @if(!isset($user))
                                <button type="submit" id="confirmCreateBtn" class="btn d-none"></button>
                                <button type="button" id="createTriggerBtn" class="btn btn-primary">
                                    <i class="fas fa-user-plus me-1"></i> Create User
                                </button>
                                @else
                                    <button type="button" id="resetPasswordBtn" class="btn btn-outline-warning" data-user-id="{{ $user->id }}">
                                        <i class="fas fa-key me-1"></i> Reset Password Default
                                    </button>
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="fas fa-save me-1"></i> Update User
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>
</form>
@endsection
@push('scripts')

@if(isset($user))
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const userId = resetPasswordBtn.dataset.userId;;
        const employeeSelect = document.querySelector('#employee_id');
        const employeeOption = new Option("{{ $user->employee->employee_id }} - {{ $user->employee->full_name }}", "{{ $user->employee->employee_id }}", true, true);
        employeeSelect.append(employeeOption);

        // SweetAlert untuk Reset Password
        const resetBtn = document.getElementById('resetPasswordBtn');
        if (resetBtn) {
            resetBtn.addEventListener('click', function () {
                Swal.fire({
                    title: 'Reset Password?',
                    html: `The password will be reset to <code class="text-danger">Iapsby${employeeSelect.value}</code>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        confirmButton: 'btn btn-warning me-2',
                        cancelButton: 'btn btn-secondary'
                    },
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        const resetForm = document.createElement('form');
                        resetForm.method = 'POST';
                        resetForm.action = `/user/users/${userId}/reset-password`;
                        const token = document.querySelector('meta[name="csrf-token"]').content;
                        resetForm.innerHTML = `
                            <input type="hidden" name="_token" value="${token}">
                            <input type="hidden" name="_method" value="PATCH">
                        `;

                        document.body.appendChild(resetForm);
                        resetForm.submit();
                    }
                });
            });
        }
     });
</script> 
@endif
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize Tom Select for employee_id with AJAX source
        initTomSelectAjax(
            '#employee_id',
            '{{ route("user.available.employees") }}?select=true',
            'Search NIK...'
        );
        
        const form = document.querySelector('form');
        const employeeSelect = document.querySelector('#employee_id');
        const passwordInput = document.querySelector('#preview_password');
        const rolesSelect = document.querySelector('#roles');
        const tomSelectInstance = employeeSelect?.tomselect;

        if (rolesSelect) {
            new TomSelect('#roles', {
            plugins: ['remove_button'],
            persist: false,
            create: false,
            maxItems: null,
            placeholder: 'Select roles...',
            render: {
                    option: function(data, escape) {
                        return `<div>${escape(data.text)}</div>`;
                    }
                }
            });
        }

        if (employeeSelect?.tomselect && passwordInput) {
            employeeSelect.tomselect.on('change', function(value) {
                passwordInput.value = 'Iapsby' + value;
            });
        }

        // SweetAlert untuk Create
        const confirmCreateBtn = document.getElementById('confirmCreateBtn');
        const createTriggerBtn = document.getElementById('createTriggerBtn');
        if (createTriggerBtn && form) {
            createTriggerBtn.addEventListener('click', function () {
                const selectedEmployeeId = employeeSelect?.value;
                const selectedNIK = employeeSelect?.tomselect?.getItem(employeeSelect.value)?.textContent || '-';
                const selectedRoles = Array.from(rolesSelect.selectedOptions).map(opt => opt.textContent.trim()).join(', ');
                const defaultPassword = passwordInput?.value || '-';

                // Validasi kosong
                if (!selectedEmployeeId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Employee ID has not been selected!',
                        text: 'Please select an employee ID first.',
                        confirmButtonText: 'OK',
                        customClass: { confirmButton: 'btn btn-warning' },
                        buttonsStyling: false
                    });
                    return;
                }

                if (selectedRoles.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Roles has not been selected!',
                        text: 'Please select at least one role.',
                        confirmButtonText: 'OK',
                        customClass: { confirmButton: 'btn btn-warning' },
                        buttonsStyling: false
                    });
                    return;
                }

                Swal.fire({
                    title: 'Create User Confirmation',
                    html: `
                        <p>Are you sure want to create a new user with the following data?</p>
                        <ul class="text-start small">
                            <li><strong>NIK:</strong> ${selectedNIK}</li>
                            <li><strong>Roles:</strong> ${selectedRoles}</li>
                            <li><strong>Password Default:</strong> <code>${defaultPassword}</code></li>
                        </ul>
                    `,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        confirmButton: 'btn btn-primary me-2',
                        cancelButton: 'btn btn-secondary'
                    },
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        confirmCreateBtn.click();
                    }
                });
            });
        }
    });
</script>
@endpush

