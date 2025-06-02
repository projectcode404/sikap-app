@extends('layouts.app')

@section('content')
<form method="POST" action="{{ !isset($adjustment) ? route('atk.adjustments.store') : route('atk.adjustments.update', $adjustment) }}">
    @csrf
    @if(isset($adjustment))
        @method('PATCH')
    @endif
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <strong><i class="fas fa-wrench me-2"></i>Stock Adjustment</strong>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            @if(!isset($adjustment))
                            <div class="col-md-6 mb-3">
                                <label for="date" class="form-label"><strong>Date <span class="text-danger">*</span></strong></label>
                                <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', now()->toDateString()) }}" required>
                                @error('date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            @else
                            <div class="col-md-6 mb-3">
                                <label for="date" class="form-label">
                                    <strong>{{ __('messages.adjustment_date') }}</strong>
                                </label>
                                <div class="form-control-plaintext" id="date">
                                    {{ $adjustment->date }}
                                </div>
                            </div>
                            @endif
                            <div class="col-md-6 mb-3">
                                <label for="note" class="form-label"><strong>Note <span class="text-danger">*</span></strong></label>
                                <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror" required>{{ old('note', $adjustment->note ?? '') }}</textarea>
                                @error('note')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-12 mb-3">
                                <h4>Adjustment Items</h4>
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ATK</th>
                                            <th>Unit</th>
                                            <th>Qty (+/-)</th>
                                            <th>Reason</th>
                                            @if(!isset($adjustment)) 
                                            <th>#</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody id="adjustmentItems">
                                        @php
                                            $oldItems = old('items', [['atk_item_id' => '', 'unit' => '', 'adjustment_qty' => '', 'reason_type' => 'correction']]);
                                        @endphp
                                        @if(!isset($adjustment))
                                            @foreach($oldItems as $i => $item)
                                            <tr>
                                                <td>
                                                    <select name="items[{{ $i }}][atk_item_id]" class="atk-select form-control" data-unit-target="#unit-{{ $i }}" required>
                                                        @if($item['atk_item_id'])
                                                            <option value="{{ $item['atk_item_id'] }}" selected>
                                                                {{ $atkItem($item['atk_item_id'])->name ?? 'Item Not Found' }}
                                                            </option>
                                                        @endif
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" id="unit-{{ $i }}" name="items[{{ $i }}][unit]" class="form-control unit-field" value="{{ $item['unit'] }}" readonly>
                                                </td>
                                                <td>
                                                    <input type="number" name="items[{{ $i }}][adjustment_qty]" class="form-control" value="{{ $item['adjustment_qty'] }}" required>
                                                </td>
                                                <td>
                                                    <select name="items[{{ $i }}][reason_type]" class="form-control" required>
                                                        <option value="correction" @selected($item['reason_type'] == 'correction')>Correction</option>
                                                        <option value="loss" @selected($item['reason_type'] == 'loss')>Loss</option>
                                                        <option value="expired" @selected($item['reason_type'] == 'expired')>Expired</option>
                                                        <option value="others" @selected($item['reason_type'] == 'others')>Others</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-link btn-sm remove-row">
                                                        <i class="fas fa-remove"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                            @foreach($adjustment->items as $item)
                                            <tr>
                                                <td>
                                                    <div class="form-control-plaintext" id="atkItem">
                                                        {{ $item->atkItem->name ?? '-' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-control-plaintext" id="unit">
                                                        {{ $item->atkItem->unit ?? '-' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-control-plaintext" id="adjustment_qty">
                                                        {{ $item->adjustment_qty }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-control-plaintext" id="adjustment_qty">
                                                        {{ ucfirst($item->reason_type) }}
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                @if(!isset($adjustment))
                                <div class="d-grid mx-auto">
                                    <button type="button" id="addAdjustmentRow" class="btn btn-outline-secondary">
                                        <i class="fas fa-plus me-1"></i> Add Item
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('atk.adjustments.index') }}" class="btn btn-outline-primary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> {{ !isset($adjustment) ?  'Create Adjustment' : 'Update Adjustment' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tableBody = document.getElementById('adjustmentItems');
        let rowCount = {{ count($oldItems) }};

        function attachSelect(selectElement, index) {
            const ts = initTomSelectAjax(selectElement, '{{ route("atk.get-atk-items") }}');
            ts.on('change', (value) => {
                const unitTarget = selectElement.dataset.unitTarget;
                const unitField = document.querySelector(unitTarget);
                if (ts.options[value]) {
                    unitField.value = ts.options[value].unit;
                }
            });
        }

        document.querySelectorAll('.atk-select').forEach((select, i) => {
            attachSelect(select, i);
        });

        document.getElementById('addAdjustmentRow').addEventListener('click', () => {
            const newRow = document.createElement('tr');
            const i = rowCount++;

            newRow.innerHTML = `
                <td><select name="items[${i}][atk_item_id]" class="atk-select form-control" data-unit-target="#unit-${i}" required></select></td>
                <td><input type="text" id="unit-${i}" name="items[${i}][unit]" class="form-control unit-field" readonly></td>
                <td><input type="number" name="items[${i}][adjustment_qty]" class="form-control" required></td>
                <td>
                    <select name="items[${i}][reason_type]" class="form-control" required>
                        <option value="correction">Correction</option>
                        <option value="loss">Loss</option>
                        <option value="expired">Expired</option>
                        <option value="others">Others</option>
                    </select>
                </td>
                <td><button type="button" class="btn btn-link btn-sm remove-row"><i class="fas fa-remove"></i></button></td>
            `;

            tableBody.appendChild(newRow);
            attachSelect(newRow.querySelector('.atk-select'), i);
        });

        tableBody.addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
                e.target.closest('tr').remove();
            }
        });
    });
</script>
@endpush
