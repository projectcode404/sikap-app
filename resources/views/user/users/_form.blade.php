<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Start Card -->
            <div class="card card-primary card-outline mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ isset($user) ? 'Edit User' : 'Create User' }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="employee_id">NIK</label>
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
                        <a href="{{ route('user.users.index') }}" class="btn btn-secondary">Cancel</a>

                        <div class="d-flex gap-2">
                            @if(isset($user))
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#confirmResetModal">
                                    <i class="fas fa-key me-1"></i> Reset Password Default
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update User
                                </button>
                            @else
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmCreateModal">
                                    <i class="fas fa-user-plus me-1"></i> Create User
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
@push('scripts')
    @if(!isset($user) && !isset($user->employee))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Initialize Tom Select for employee_id with AJAX source
            initTomSelectAjax(
                '#employee_id',
                '{{ route("user.available.employees") }}?select=true',
                'Search NIK...'
            );

            const confirmNik = document.getElementById('confirm_nik');
            const confirmRole = document.getElementById('confirm_role');
            const confirmPass = document.getElementById('confirm_password');
            const roleSelect = document.querySelector('#roles');
            const confirmRoles = document.getElementById('confirm_roles');
            const passwordInput = document.getElementById('preview_password');
            const employeeSelect = document.querySelector('#employee_id');
            const tomSelectInstance = employeeSelect?.tomselect;

            if (document.querySelector('#roles')) {
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

            const modalTrigger = document.querySelector('[data-bs-target="#confirmCreateModal"]');
            if (modalTrigger) {
                modalTrigger.addEventListener('click', function () {
                    // Get selected text from Tom Select
                    const selectedNik = tomSelectInstance?.getItem(employeeSelect.value)?.textContent || '-';
                    confirmNik.innerText = selectedNik;

                    // Get selected role
                    const selected = Array.from(roleSelect.selectedOptions).map(opt => opt.textContent.trim());
                    confirmRoles.innerText = selected.length ? selected.join(', ') : '-';

                    // Get password preview
                    confirmPass.innerText = passwordInput?.value || '-';
                });
            }
        });
    </script>
    @else
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const employeeIdSelect = document.getElementById('employee_id');
            const employeeIdOption = new Option("{{ $user->employee->employee_id }} - {{ $user->employee->full_name }}", "{{ $user->employee->employee_id }}", true, true);
            employeeIdSelect.append(employeeIdOption);

            if (document.querySelector('#roles')) {
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
        });
    </script>
    @endif
@endpush