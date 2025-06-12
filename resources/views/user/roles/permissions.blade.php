@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('user.role-permissions.update', $role) }}">
    @csrf @method('PUT')
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card card-outline card-primary mb-4">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-lock me-2"></i>Manage Permissions for Role: {{ $role->name }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row row-cols-2 row-cols-md-3 g-2">
                            @foreach($permissions as $perm)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                        value="{{ $perm->name }}"
                                        id="perm-{{ $perm->id }}"
                                        {{ $role->hasPermissionTo($perm->name) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="perm-{{ $perm->id }}">
                                        {{ $perm->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('user.roles.index') }}" class="btn btn-outline-primary">Cancel</a>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Update Permissions
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection