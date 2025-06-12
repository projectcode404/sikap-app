@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Carbon;

    $statusColor = [
        'draft'     => 'secondary',
        'submitted' => 'primary',
        'approved'  => 'success',
        'rejected'  => 'danger',
        'realized'  => 'warning',
        'received'  => 'success',
        'canceled'  => 'danger',
    ];
    $color = $statusColor[$outRequest->status] ?? 'secondary';
@endphp
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
                    <div class="row g-2 mb-4">
                        <h5 class="mb-3"><strong>Request Information</strong></h5>
                        <div class="row row-cols-1 row-cols-md-2 g-3">
                            <div>
                                <strong>Created By</strong><br>{{ $outRequest->createdBy->employee->full_name ?? '-' }}
                            </div>
                            <div>
                                <strong>Work Unit</strong><br>{{ $outRequest->workUnit->name ?? '-' }}
                            </div>
                            <div>
                                <strong>Request Date</strong><br>
                                {{ $outRequest->request_date ? Carbon::parse($outRequest->request_date)->translatedFormat('d F Y') : '-' }}
                            </div>
                            <div>
                                <strong>Period</strong><br>
                                {{ $outRequest->period ? Carbon::createFromFormat('Y-m', $outRequest->period)->translatedFormat('F Y') : '-' }}
                            </div>
                            <div>
                                <strong>Request Note</strong><br><span style="white-space: pre-line;">{{ $outRequest->request_note ?? '-' }}</span>
                            </div>
                            <div>
                                <strong>Approval Note</strong><br><span style="white-space: pre-line;">{{ $outRequest->approval_note ?? '-' }}</span>
                            </div>
                            <div>
                                <strong>Status</strong><br>
                                <span class="badge rounded-pill bg-{{ $color }} text-capitalize">
                                    {{ $outRequest->status ?? '-' }}
                                </span>
                                @if($outRequest->approved_by)
                                    <span class="text-muted">by {{ $outRequest->approvedBy->employee->full_name ?? '-' }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row g2">
                        <div class="col-md-12 mb-3 table-responsive">
                            <hr class="my-4">
                            <h5 class="mb-3"><strong>Item Request List</strong></h5>
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table text-center">
                                    <tr>
                                        <th style="width: 30%;">ATK</th>
                                        <th>Units</th>
                                        <th>Remaining Stock</th>
                                        <th>Qty Requested</th>
                                        @if(in_array($outRequest->status, ['approved', 'realized', 'received']))
                                            <th>Qty Approved</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($outRequest->items as $item)
                                    <tr>
                                        <td>{{ $atkItem($item->atk_item_id)->name ?? 'Item Not Found' }}</td>
                                        <td class="text-center">{{ $item->atkItem->unit ?? '' }}</td>
                                        <td class="text-center">{{ $item->current_stock_at_request }}</td>
                                        <td class="text-center">{{ $item->qty }}</td>
                                        @if(in_array($outRequest->status, ['approved', 'realized', 'received']))
                                            <td class="text-center">{{ $item->qty_approved }}</td>
                                        @endif
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No items found.</td>
                                    </tr>
                                    @endforelse
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
                        @if(in_array($outRequest->status, ['approved', 'realized', 'received']))
                        <div class="col d-flex justify-content-end gap-2">
                            <a href="{{ route('atk.out-requests.print', $outRequest->id) }}" target="_blank" class="btn btn-success">
                                <i class="fas fa-print me-1"></i>Print
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
