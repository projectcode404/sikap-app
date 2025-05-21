@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('atk.po-receive.store', $purchaseOrder) }}" enctype="multipart/form-data">
    @csrf
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Start Card -->
                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <strong>
                                <i class="fas fa-inbox me-2"></i>Purchase Order Receive
                            </strong>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-md-6 mb-3">
                                <label for="supplier_name" class="form-label">
                                    <strong>Supplier Name</strong>
                                </label>
                                <div class="form-control-plaintext" id="supplier_name">
                                    {{ $purchaseOrder->supplier->name }}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="po_number" class="form-label"><strong>PO Number</strong></label>
                                <div class="form-control-plaintext" id="po_number">
                                    {{ $purchaseOrder->po_number }}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="po_date" class="form-label"><strong>PO Date</strong></label>
                                <div class="form-control-plaintext" id="date">
                                    {{ $purchaseOrder->po_date }}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="schedule_date" class="form-label"><strong>Schedule Date</strong></label>
                                <div class="form-control-plaintext" id="schedule_date">
                                    {{ $purchaseOrder->schedule_date }}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="note" class="form-label"><strong>PO Note </strong></label>
                                <div class="form-control-plaintext" id="note">
                                    {{ $purchaseOrder->note }}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="receipt_file" class="form-label"><strong>Receipt File .pdf </strong></label>
                                <input type="file" accept=".pdf" name="receipt_file" id="receipt_file" class="form-control @error('receipt_file') is-invalid @enderror">
                                @error('receipt_file')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-5">
                                <label for="note" class="form-label"><strong>Receive Note </strong></label>
                                <textarea name="receive_note" id="receive_note" class="form-control @error('receive_note') is-invalid @enderror">{{ old('receive_note') }}</textarea>
                                @error('receive_note')
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
                                            <th scope="col">Unit</th>
                                            <th scope="col">Ordered</th>
                                            <th scope="col">Received</th>
                                            <th scope="col">Receive</th>
                                            <th scope="col">Remaining</th>
                                        </tr>
                                    </thead>
                                    <tbody id="poItems">
                                        @foreach($purchaseOrder->items as $index => $item)
                                        <tr>
                                            <td>
                                                <div class="form-control-plaintext" id="atk_item_name">
                                                    {{ $atkItems->find($item['atk_item_id'])->name }}
                                                </div>
                                                <input type="hidden" name="items[{{ $index }}][atk_purchase_order_item_id]" value="{{ $item['id'] }}">
                                                <input type="hidden" name="items[{{ $index }}][atk_item_id]" value="{{ $item['atk_item_id'] }}">
                                            </td>
                                            <td>
                                                <div class="form-control-plaintext" id="atk_item_unit">
                                                    {{ $item['unit'] }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-control-plaintext" id="po_item_qty">
                                                    {{ $item['qty'] }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-control-plaintext" id="received_qty">
                                                    {{ $item['received_qty'] }}
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" name="items[{{ $index }}][qty]" class="form-control" min="0" max="{{ $item['qty'] - $item['received_qty'] }}" value="{{ old('items.' . $index . '.qty') }}">
                                            </td>
                                            <td>
                                                <div class="form-control-plaintext" id="remaining_qty">
                                                    {{ $item['qty'] - $item['received_qty'] }}
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('atk.purchase-orders.index') }}" class="btn btn-outline-primary">Cancel</a>

                            <div class="d-flex gap-2">
                                <button type="submit" id="updateBtn" class="btn btn-primary">
                                    <i class="fas fa-inbox me-1"></i> Receive Items
                                </button>
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