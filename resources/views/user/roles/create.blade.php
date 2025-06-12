@extends('layouts.app')

@section('content')
<form method="POST" action="{{ isset($role) ? route('user.roles.update', $role) : route('user.roles.store') }}">
    @csrf
    @if(isset($role))
        @method('PUT')
    @endif
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-outline card-primary mb-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user-shield me-2"></i>{{ isset($role) ? 'Edit Role' : 'Create Role' }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label"><strong>Role Name</strong></label>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $role->name ?? '') }}"
                                placeholder="Enter role name" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('user.roles.index') }}" class="btn btn-outline-primary">Cancel</a>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> {{ isset($role) ? 'Update' : 'Create' }}
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