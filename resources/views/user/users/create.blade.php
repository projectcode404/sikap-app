@extends('layouts.app')

@section('content')
<form method="POST" action="{{ isset($user) ? route('user.users.update', $user) : route('user.users.store') }}">
    @csrf
    @if(isset($user))
        @method('PATCH')
    @endif

    @include('user.users._form')
    
    @if(!isset($user))
    <!-- Modal Create User Confirmation -->
    <div class="modal fade" id="confirmCreateModal" tabindex="-1" aria-labelledby="confirmCreateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-primary">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="confirmCreateModalLabel">
                        Create User Confirmation
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to create a new user with the following data?</p>
                    <ul class="mb-2">
                        <li><strong>NIK:</strong> <span id="confirm_nik">-</span></li>
                        <li><strong>Role(s):</strong> <span id="confirm_roles">-</span></li>
                        <li><strong>Password Default:</strong> <code class="text-danger" id="confirm_password">-</code></li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Confirm & Create User
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</form>
@if(isset($user))
<!-- Modal Reset Password Confirmation-->
<div class="modal fade" id="confirmResetModal" tabindex="-1" aria-labelledby="confirmResetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-warning">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="confirmResetModalLabel">
                    Reset Password Confirmation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                Are you sure to <strong>reset this user password</strong> to default? :
                <div class="mt-2 text-danger"><strong>Iapsby{{ $user->employee_id }}</strong></div>
            </div>
            <div class="modal-footer">
                <form action="{{ route('user.reset-password', $user->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Confirm & Reset Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

