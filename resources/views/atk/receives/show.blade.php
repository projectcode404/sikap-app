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
                            <i class="fas fa-inbox me-2"></i>Receive Details
                        </strong>
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-6 mb-3">
                            <label for="po_number" class="form-label"><strong>PO Number</strong></label>
                            <div class="form-control-plaintext" id="po_number">
                                {{ $receive->purchaseOrder->po_number }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="received_by" class="form-label"><strong>Received By</strong></label>
                            <div class="form-control-plaintext" id="received_by">
                                {{ optional($receive->receiver?->employee)->full_name ?? '-' }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="received_date" class="form-label"><strong>Received Date</strong></label>
                            <div class="form-control-plaintext" id="received_date">
                                {{ \Carbon\Carbon::parse($receive->receive_date)->format('d M Y') }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="received_note" class="form-label"><strong>Received Note</strong></label>
                            <div class="form-control-plaintext" id="received_note">
                                {{ $receive->note ?? '-' }}
                            </div>
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
                                        <th scope="col">Received</th>
                                    </tr>
                                </thead>
                                <tbody id="poItems">
                                    @foreach($receive->items as $item)
                                    <tr>
                                        <td>
                                            <div class="form-control-plaintext" id="atk_item_name">
                                                {{ $item->item->name ?? '-' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-control-plaintext" id="atk_item_unit">
                                                {{ $item->item->unit ?? '-' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-control-plaintext" id="po_item_qty">
                                                {{ $item['qty'] }}
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
                        @php
                            $backUrl = url()->previous();
                        @endphp
                        <a href="{{ $backUrl }}"
                                class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>
    </div>
</div>
@endsection