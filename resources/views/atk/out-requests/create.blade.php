@extends('layouts.app')

@section('content')
<form method="POST" action="{{ !isset($outRequest) ? route('atk.out-requests.store') : route('atk.out-requests.update', $outRequest) }}">
    @csrf
    @if(isset($outRequest))
        @method('PATCH')
    @endif
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card card-outline card-primary mb-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-box me-2"></i>ATK Request
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-2 mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="work_unit_id" class="form-label"><strong>Work Unit</strong></label>
                                <select name="work_unit_id" id="work_unit_id" class="form-select" required>
                                    @if(old('work_unit_id') && old('work_unit_name'))
                                        <option value="{{ old('work_unit_id') }}" selected>{{ old('work_unit_name') }}</option>
                                    @elseif(isset($outRequest))
                                        <option value="{{ $outRequest->workUnit->id }}" selected>{{ $outRequest->workUnit->name }}</option>
                                    @endif
                                </select>
                                <input type="hidden" name="work_unit_name" id="work_unit_name" value="{{ old('work_unit_name', $outRequest->workUnit->name ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="request_date" class="form-label"><strong>Request Date</strong></label>
                                <input type="date" name="request_date" id="request_date" class="form-control @error('request_date') is-invalid @enderror" value="{{ old('request_date', $outRequest->request_date ?? now()->toDateString()) }}" required>
                                @error('request_date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="period" class="form-label"><strong>Period</strong></label>
                                <select name="period" class="form-select" required>
                                    <option value="">-- Select Period --</option>
                                    @foreach($periods as $p)
                                        <option value="{{ $p['value'] }}" {{ old('period', $outRequest->period ?? '') === $p['value'] ? 'selected' : '' }}>
                                            {{ $p['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="request_note" class="form-label"><strong>Request Note</strong></label>
                                <textarea name="request_note" id="request_note" class="form-control" placeholder="Permintaan ATK Stock Point Xxxxxx Periode Juni 2025" required>{{ old('request_note', $outRequest->request_note ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="row g2">
                            <div class="col-md-12 mb-3">
                                <h5>Item Request List</h5>
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>ATK</th>
                                            <th>Units</th>
                                            <th>Remaining Stock</th>
                                            <th>Qty</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody id="requestItems">
                                        @php
                                            $oldItems = old('items', isset($outRequest) ? $outRequest->items->map(function ($item) {
                                                return [
                                                    'atk_item_id' => $item->atk_item_id,
                                                    'unit' => $item->atkItem->unit ?? '',
                                                    'current_stock' => $item->current_stock_at_request,
                                                    'qty' => $item->qty,
                                                ];
                                            })->toArray() : [['atk_item_id' => '', 'qty' => '', 'unit' => '', 'current_stock' => '']]);
                                        @endphp
                                        @foreach($oldItems as $i => $item)
                                        <tr>
                                            <td>
                                                <select name="items[{{ $i }}][atk_item_id]" class="atk-select form-control"
                                                    data-unit-target="#unit-{{ $i }}"
                                                    required>
                                                    @if($item['atk_item_id'])
                                                        <option value="{{ $item['atk_item_id'] }}" selected>
                                                            {{ $atkItem($item['atk_item_id'])->name ?? 'Item Not Found' }}
                                                        </option>
                                                    @endif
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="items[{{ $i }}][unit]" id="unit-{{ $i }}" class="form-control" value="{{ $item['unit'] ?? '' }}" readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="items[{{ $i }}][current_stock]" id="stock-{{ $i }}" class="form-control" min="0" value="{{ $item['current_stock'] ?? '' }}" required>
                                            </td>
                                            <td>
                                                <input type="number" name="items[{{ $i }}][qty]" class="form-control" min="1" value="{{ $item['qty'] ?? '' }}" required>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-link btn-sm remove-row"><i class="fas fa-remove"></i></button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-grid mx-auto">
                                    <button type="button" class="btn btn-outline-secondary" id="addRowItem">
                                        <i class="fas fa-plus me-1"></i> Add Row
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col d-flex justifiy-content-start">
                                <a href="{{ route('atk.out-requests.index') }}" class="btn btn-outline-primary">Cancel</a>
                            </div>
                            <div class="col d-flex justify-content-end gap-2">
                                <button type="submit" name="action" class="btn btn-primary" value="draft">
                                    <i class="fas fa-save me-1"></i>{{ !isset($outRequest) ? 'Save Draft' : 'Update Draft' }}
                                </button>
                                <button type="submit" name="action" id="submitBtn" class="btn btn-primary" value="submit">
                                    <i class="fas fa-paper-plane me-1"></i> Submit Request
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

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        initTomSelectAjax(
            '#work_unit_id',
            '{{ route("master.work-units.select") }}?select=true',
            'Search Work Unit...'
        );

        submitBtn.addEventListener('click', function (e) {
            e.preventDefault();
            confirmAction('Are you sure you want to submit this request?', function () {
                const form = submitBtn.closest('form');

                // Hapus input hidden sebelumnya jika ada
                form.querySelector('input[name="action"]')?.remove();

                // Tambahkan input hidden untuk membawa nilai submit
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'action';
                hiddenInput.value = 'submit';
                form.appendChild(hiddenInput);

                form.submit();
            });
        });

        const workUnitSelect = document.querySelector('#work_unit_id');
        const workUnitNameInput = document.querySelector('#work_unit_name');

        if (workUnitSelect?.tomselect && workUnitNameInput) {
            workUnitSelect.tomselect.on('change', function(value) {
                const label = workUnitSelect.tomselect.getItem(value)?.textContent || '';
                workUnitNameInput.value = label;
            });
        }

        const tableBody = document.getElementById('requestItems');
        let rowCount = {{ count($oldItems) }};

        function attachSelect(selectElement, index) {
            const ts = initTomSelectAjax(selectElement, '{{ route("atk.get-atk-items") }}');
            ts.on('change', (value) => {
                const unitTarget = selectElement.dataset.unitTarget;
                const unitField = document.querySelector(unitTarget);
                if (ts.options[value]) {
                    unitField.value = ts.options[value].unit ?? '-';
                }
            });
        }

        document.querySelectorAll('.atk-select').forEach((select, i) => {
            attachSelect(select, i);
        });

        document.getElementById('addRowItem')?.addEventListener('click', () => {
            const newRow = document.createElement('tr');
            const i = rowCount++;

            newRow.innerHTML = `
                <td>
                    <select name="items[${i}][atk_item_id]" class="atk-select form-control" data-unit-target="#unit-${i}" required></select>
                </td>
                <td>
                    <input type="text" name="items[${i}][unit]" id="unit-${i}" class="form-control" readonly>
                </td>
                <td>
                    <input type="text" name="items[${i}][current_stock]" id="stock-${i}" class="form-control">
                </td>
                <td>
                    <input type="number" name="items[${i}][qty]" class="form-control" min="1" required>
                </td>
                <td>
                    <button type="button" class="btn btn-link btn-sm remove-row"><i class="fas fa-remove"></i></button>
                </td>
            `;

            tableBody.appendChild(newRow);
            attachSelect(newRow.querySelector('.atk-select'), i);
        });

        tableBody.addEventListener('click', function (e) {
            if (e.target.closest('.remove-row')) {
                e.target.closest('tr').remove();
            }
        });
    });
</script>
@endpush
