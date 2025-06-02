@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('atk.adjustments.store') }}">
    @csrf
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <strong><i class="fas fa-wrench me-2"></i>Stock Adjustment Details</strong>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-md-6 mb-3">
                                <label for="date" class="form-label">
                                    <strong>{{ __('messages.date') }}</strong>
                                </label>
                                <div class="form-control-plaintext" id="date">
                                    {{ $adjustment->date }}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="date" class="form-label">
                                    <strong>{{ __('messages.note') }}</strong>
                                </label>
                                <div class="form-control-plaintext" id="date">
                                    {{ $adjustment->note }}
                                </div>
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
                                        </tr>
                                    </thead>
                                    <tbody id="adjustmentItems">
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
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
