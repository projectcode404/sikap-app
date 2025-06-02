@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card card-outline card-primary mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-box me-2"></i>ATK Request Detail
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-2 mb-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>Work Unit</strong></label>
                            <div class="form-control-plaintext">
                                {{ $userWorkUnit->name ?? '' }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><strong>Request Date</strong></label>
                            <div class="form-control-plaintext">
                                {{ $outRequest->request_date ?? '' }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><strong>Remarks</strong></label>
                            <div class="form-control-plaintext">
                                {{ $outRequest->remarks ?? '' }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><strong>Created By</strong></label>
                            <div class="form-control-plaintext">
                                {{ $outRequest->createdBy->employee->full_name ?? '' }}
                            </div>
                        </div>
                    </div>

                    <div class="row g2">
                        <div class="col-md-12 mb-3">
                            <h3>Item Request List</h3>
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ATK</th>
                                        <th>Units</th>
                                        <th>Current Stock</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($outRequest->items ?? [] as $i => $item)
                                    <tr>
                                        <td>
                                            <div class="form-control-plaintext">
                                                {{ $atkItem($item->atk_item_id)->name ?? 'Item Not Found' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-control-plaintext">
                                                {{ $item->atkItem->unit ?? '' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-control-plaintext">
                                                {{ $item->current_stock_at_request }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-control-plaintext">
                                                {{ $item->qty }}
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @if(empty($outRequest->items) || count($outRequest->items) === 0)
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No items found.</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col d-flex justifiy-content-start">
                            <a href="{{ route('atk.out-requests.index') }}" class="btn btn-outline-primary">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
