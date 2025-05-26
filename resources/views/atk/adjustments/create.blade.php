@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('atk.adjustments.store') }}">
    @csrf
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <h3 class="card-title"><strong><i class="fas fa-sliders-h me-2"></i>Adjustment Form</strong></h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="atk_item_id" class="form-label"><strong>ATK Item <span class="text-danger">*</span></strong></label>
                            <select id="atkItem" name="atk_item" class="atk-select form-control @error('atk_item') is-invalid @enderror" data-unit-target="unit" placeholder="Search Items...">
                                @if(isset($item['atk_item_id']))
                                    <option value="{{ $item['atk_item_id'] }}" selected>
                                        {{ $atkItems->find($item['atk_item_id'])->name }}
                                    </option>
                                @endif
                            </select>
                            <!-- <select id="atk_item_id" name="atk_item_id" class="form-control @error('atk_item_id') is-invalid @enderror">
                                <option value="">Select item...</option>
                                @foreach($atkItems as $item)
                                    <option value="{{ $item->id }}" {{ old('atk_item_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }} ({{ $item->unit }})
                                    </option>
                                @endforeach
                            </select> -->
                            @error('atk_item_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="adjustment_qty" class="form-label"><strong>Adjustment Quantity <span class="text-danger">*</span></strong></label>
                            <input type="number" name="adjustment_qty" id="adjustment_qty" class="form-control @error('adjustment_qty') is-invalid @enderror" value="{{ old('adjustment_qty') }}" required>
                            @error('adjustment_qty')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="reason_type" class="form-label"><strong>Reason Type <span class="text-danger">*</span></strong></label>
                            <select name="reason_type" id="reason_type" class="form-control @error('reason_type') is-invalid @enderror" required>
                                <option value="">Select reason...</option>
                                @foreach(['correction', 'loss', 'expired', 'others'] as $reason)
                                    <option value="{{ $reason }}" {{ old('reason_type') == $reason ? 'selected' : '' }}>
                                        {{ ucfirst($reason) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('reason_type')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label"><strong>Adjustment Date <span class="text-danger">*</span></strong></label>
                            <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', now()->toDateString()) }}" required>
                            @error('date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="note" class="form-label"><strong>Note</strong></label>
                            <textarea name="note" id="note" rows="3" class="form-control @error('note') is-invalid @enderror">{{ old('note') }}</textarea>
                            @error('note')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('atk.adjustments.index') }}" class="btn btn-outline-primary">Cancel</a>

                            <div class="d-flex gap-2">
                                @if(!isset($adjustment))
                                <button type="submit" id="createBtn" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i> Save Adjustment
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
            </div>
        </div>
    </div>
</form>
@endsection
@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        initTomSelectAjax("#atkItem", '{{ route("atk.get-atk-items") }}');
    });
</script>
@endsection
