@extends('layouts.app')

@section('content')
<form method="POST" action="{{ isset($supplier) ? route('master.suppliers.update', $supplier) : route('master.suppliers.store') }}">
    @csrf
    @if(isset($supplier))
        @method('PATCH')
    @endif
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Start Card -->
                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <strong>
                            @if(isset($supplier))
                                <i class="fas fa-pencil me-2"></i>Edit Supplier
                            @else
                                <i class="fas fa-paperclip me-2"></i>Create Supplier
                            @endif
                            </strong>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    <strong>Supplier Name <span class="text-danger">*</span></strong>
                                </label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $supplier->name ?? '') }}" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    <!-- <span class="valid-feedback">{{ $message }}</span> -->
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pic" class="form-label"><strong>Contact Person</strong></label>
                                <input type="text" name="pic" id="pic" class="form-control @error('pic') is-invalid @enderror" value="{{ old('pic', $supplier->pic ?? '') }}">
                                @error('pic')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label"><strong>Phone <span class="text-danger">*</span></strong></label>
                                <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $supplier->phone ?? '') }}" required>
                                @error('phone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label"><strong>Email</strong></label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $supplier->email ?? '') }}">
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="bank_name" class="form-label"><strong>Bank Name</strong></label>
                                <input type="text" name="bank_name" id="bank_name" class="form-control @error('bank_name') is-invalid @enderror" value="{{ old('bank_name', $supplier->bank_name ?? '') }}">
                                @error('bank_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="bank_account" class="form-label"><strong>Bank Account</strong></label>
                                <input type="text" name="bank_account" id="bank_account" class="form-control @error('bank_account') is-invalid @enderror" value="{{ old('bank_account', $supplier->bank_account ?? '') }}">
                                @error('bank_account')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="address" class="form-label"><strong>Address <span class="text-danger">*</span></strong></label>
                                <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" required>{{ old('address', $supplier->address ?? '') }}</textarea>
                                @error('address')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="status" class="form-label"><strong>Status</strong></label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                    <option value="active" {{ (isset($supplier) && $supplier->status == 'active') ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ (isset($supplier) && $supplier->status == 'inactive') ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('master.suppliers.index') }}" class="btn btn-outline-primary">Cancel</a>

                            <div class="d-flex gap-2">
                                @if(!isset($supplier))
                                <button type="submit" id="createBtn" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i> Submit
                                </button>
                                @else
                                <button type="submit" id="updateBtn" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Update
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