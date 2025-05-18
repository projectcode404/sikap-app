@extends('layouts.app')

@section('content')
<form method="POST" action="{{ isset($purchaseOrder) ? route('atk.purchase-orders.update', $purchaseOrder) : route('atk.purchase-orders.store') }}">
    @csrf
    @if(isset($purchaseOrder))
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
                            @if(isset($purchaseOrder))
                                <i class="fas fa-pencil me-2"></i>Edit Purchase Order
                            @else
                                <i class="fas fa-paperclip me-2"></i>Create Purchase Order
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
                                <select id="supplier_id" name="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror">
                                    @if(isset($purchaseOrder))
                                        <option value="{{ $purchaseOrder->supplier_id }}" selected>
                                            {{ $purchaseOrder->supplier->name }}
                                        </option>
                                    @endif
                                </select>
                                @error('supplier_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    <!-- <span class="valid-feedback">{{ $message }}</span> -->
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="po_number" class="form-label"><strong>PO Number <span class="text-danger">*</span></strong></label>
                                <input type="text" name="po_number" id="po_number" class="form-control @error('po_number') is-invalid @enderror" value="{{ old('po_number', $purchaseOrder->po_number ?? '') }}" required>
                                @error('po_number')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="po_date" class="form-label"><strong>PO Date <span class="text-danger">*</span></strong></label>
                                <input type="date" name="po_date" id="po_date" class="form-control @error('po_date') is-invalid @enderror" value="{{ old('po_date', $purchaseOrder->po_date ?? '') }}" required>
                                @error('po_date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="schedule_date" class="form-label"><strong>Schedule Date <span class="text-danger">*</span></strong></label>
                                <input type="date" name="schedule_date" id="schedule_date" class="form-control @error('schedule_date') is-invalid @enderror" value="{{ old('schedule_date', $purchaseOrder->schedule_date ?? '') }}">
                                @error('schedule_date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-5">
                                <label for="note" class="form-label"><strong>Note </strong></label>
                                <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror">{{ old('note', $purchaseOrder->note ?? '') }}</textarea>
                                @error('note')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-12 mb-3">
                                <h2>Items</h2>
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col">ATK</th>
                                            <th scope="col">Units</th>
                                            <th scope="col">Qty</th>
                                            <th scope="col">#</th>
                                        </tr>
                                    </thead>
                                    <tbody id="poItems">
                                        @foreach(old('items', isset($purchaseOrder) ? $purchaseOrder->items : [[]]) as $index => $item)
                                        <tr>
                                            <td>
                                                <select name="items[{{ $index }}][atk_item_id]" class="atk-select form-control @error('items.{{ $index }}.atk_item_id') is-invalid @enderror" data-unit-target="#unit-{{ $index }}" placeholder="Search Items...">
                                                    @if(isset($item['atk_item_id']))
                                                        <option value="{{ $item['atk_item_id'] }}" selected>
                                                            {{ $atkItems->find($item['atk_item_id'])->name }}
                                                        </option>
                                                    @endif
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" id="unit-{{ $index }}" name="items[{{ $index }}][unit]" class="form-control unit-field" value="{{ $item['unit'] ?? '' }}" readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="items[{{ $index }}][qty]" class="form-control" min="1" value="{{ $item['qty'] ?? '' }}" required>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-link btn-sm remove-row">
                                                    <i class="fas fa-remove"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-grid mx-auto">
                                    <button type="button" id="addRowItem" class="btn btn-outline-secondary">
                                        <i class="fas fa-plus me-1"></i> Add Row Item
                                    </button>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('atk.purchase-orders.index') }}" class="btn btn-outline-primary">Cancel</a>

                            <div class="d-flex gap-2">
                                @if(!isset($purchaseOrder))
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
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const selectElement = document.getElementById('supplier_id');
        if (selectElement) {
            const ts = initTomSelectAjax(selectElement, '{{ route("atk.get-suppliers") }}');
            attachUnitUpdateListener(ts, selectElement);
        }

        // PO Items
        const poItems = document.getElementById('poItems');
        let rowCount = document.querySelectorAll('#poItems tr').length;
        
        // Inisialisasi untuk row yang sudah ada
        document.querySelectorAll('.atk-select').forEach(selectElement => {
            const ts = initTomSelectAjax(selectElement, '{{ route("atk.get-atk-items") }}');
            attachUnitUpdateListener(ts, selectElement);
        });

        // Fungsi tambahan untuk update unit
        function attachUnitUpdateListener(tsInstance, selectElement) {
            tsInstance.on('change', (value) => {
                const unitTarget = selectElement.dataset.unitTarget;
                const unitField = document.querySelector(unitTarget);
                if (tsInstance.options[value]) {
                    unitField.value = tsInstance.options[value].unit;
                }
            });
        }

        // Tambah baris baru
        document.getElementById('addRowItem').addEventListener('click', () => {
            const newRow = document.createElement('tr');
            const newIndex = rowCount++;
            
            newRow.innerHTML = `
                <td>
                    <select name="items[${newIndex}][atk_item_id]" 
                            class="atk-select form-control @error('items.{{ $index }}.atk_item_id') is-invalid @enderror" 
                            data-unit-target="#unit-${newIndex}"
                            placeholder="Search Items...">
                    </select>
                </td>
                <td>
                    <input type="text" id="unit-${newIndex}" 
                        name="items[${newIndex}][unit]" 
                        class="form-control unit-field" 
                        readonly>
                </td>
                <td>
                    <input type="number" 
                        name="items[${newIndex}][qty]" 
                        class="form-control" 
                        min="1" 
                        required>
                </td>
                <td>
                    <button type="button" class="btn btn-link btn-sm remove-row">
                        <i class="fas fa-remove"></i>
                    </button>
                </td>
            `;

            const selectElement = newRow.querySelector('.atk-select');
            const tsInstance = initTomSelectAjax(selectElement, '{{ route("atk.get-atk-items") }}');
            attachUnitUpdateListener(tsInstance, selectElement);
            
            poItems.appendChild(newRow);
        });

        // Hapus baris dengan event delegation
        poItems.addEventListener('click', (e) => {
            if (e.target.closest('.remove-row')) {
                e.target.closest('tr').remove();
            }
        });
    });
</script>
@endpush