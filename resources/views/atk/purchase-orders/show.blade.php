@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Start Card -->
            <div class="card card-primary card-outline mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <strong>
                            <i class="fas fa-inbox me-2"></i>{{ __('messages.atk_purchase_order_detail') }}
                        </strong>
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-6 mb-3">
                            <label for="supplier_name" class="form-label">
                                <strong>{{ __('messages.supplier_name') }}</strong>
                            </label>
                            <div class="form-control-plaintext" id="supplier_name">
                                {{ $purchaseOrder->supplier->name }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="po_number" class="form-label"><strong>{{ __('messages.po_number') }}</strong></label>
                            <div class="form-control-plaintext" id="po_number">
                                {{ $purchaseOrder->po_number }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="po_date" class="form-label"><strong>{{ __('messages.po_date') }}</strong></label>
                            <div class="form-control-plaintext" id="date">
                                {{ $purchaseOrder->po_date }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="schedule_date" class="form-label"><strong>{{ __('messages.schedule_date') }}</strong></label>
                            <div class="form-control-plaintext" id="schedule_date">
                                {{ $purchaseOrder->schedule_date }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="note" class="form-label"><strong>{{ __('messages.po_note') }}</strong></label>
                            <div class="form-control-plaintext" id="note">
                                {{ $purchaseOrder->note }}
                            </div>
                        </div>
                        @if($purchaseOrder->status === 'completed')
                        <div class="col-md-6 mb-3">
                            <label for="receipt_number" class="form-label"><strong>{{ __('messages.gr_number_sap') }}</strong></label>
                            <div class="form-control-plaintext" id="note">
                                {{ $purchaseOrder->receipt_number }}
                            </div>
                        </div>
                        @endif
                    </div>
                    @if($purchaseOrder->receives->isNotEmpty())
                    <div class="row g-2">
                        <div class="col-md-12 mb-3">
                            <h2>{{ __('messages.received_history') }}</h2>
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ __('messages.receive_id') }}</th>
                                        <th scope="col">{{ __('messages.received_date') }}</th>
                                        <th scope="col">{{ __('messages.received_by') }}</th>
                                        <th scope="col">{{ __('messages.receipt_file') }}</th>
                                        <th scope="col">{{ __('messages.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="poItems">
                                    @foreach($purchaseOrder->receives as $index => $receive)
                                    <tr>
                                        <td>
                                            <div class="form-control-plaintext" id="receive_id">
                                                {{ $receive->id }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-control-plaintext" id="receive_date">
                                                {{ \Carbon\Carbon::parse($receive->receive_date)->format('d M Y') }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-control-plaintext" id="received_by">
                                                {{ optional($receive->receiver?->employee)->full_name ?? '-' }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($receive->receipt_file)
                                                <a href="{{ asset('storage/' . $receive->receipt_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-file-pdf"></i> {{ __('messages.view_file') }}
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('atk.receive.show', $receive->id) }}" class="btn btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                    <div class="row g-2">
                        <div class="col-md-12 mb-3">
                            <h2>Items</h2>
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ __('messages.item_name') }}</th>
                                        <th scope="col">{{ __('messages.item_unit') }}</th>
                                        <th scope="col">{{ __('messages.ordered') }}</th>
                                        <th scope="col">{{ __('messages.received') }}</th>
                                        <th scope="col">{{ __('messages.outstanding') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="poItems">
                                    @foreach($purchaseOrder->items as $index => $item)
                                    <tr>
                                        <td>
                                            <div class="form-control-plaintext" id="atk_item_name">
                                                {{ $atkItems->find($item['atk_item_id'])->name }}
                                            </div>
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
                        <a href="{{ route('atk.purchase-orders.index') }}" class="btn btn-outline-primary">{{ __('messages.back') }}</a>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>
    </div>
</div>
@endsection