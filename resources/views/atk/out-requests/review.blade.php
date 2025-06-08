@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <form method="POST" action="{{ route('atk.out-requests.action', $outRequest) }}">
            @csrf
            @method('PATCH')
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
                                    <strong>Request Date</strong><br>{{ $outRequest->request_date ?? '-' }}
                                </div>
                                <div>
                                    <strong>Period</strong><br>{{ $outRequest->period ?? '-' }}
                                </div>
                                <div>
                                    <strong>Request Note</strong><br><span style="white-space: pre-line;">{{ $outRequest->request_note ?? '-' }}</span>
                                </div>
                                <div>
                                    <strong>Aprroval Note</strong><br>
                                    <textarea name="approval_note" id="approval_note" class="form-control" placeholder="">{{ old('approval_note') }}</textarea>
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
                                            <th style="width: 30%;">ATK Name</th>
                                            <th>Units</th>
                                            <th>Remaining Stock</th>
                                            <th>Qty Requested</th>
                                            <th>Qty Approve</th>
                                            <th>Available Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($outRequest->items ?? [] as $i => $item)
                                        <tr>
                                            <td>
                                                {{ $atkItem($item->atk_item_id)->name ?? 'Item Not Found' }}
                                            </td>
                                            <td class="text-center">
                                                {{ $item->atkItem->unit ?? '' }}
                                            </td>
                                            <td class="text-center">
                                                {{ $item->current_stock_at_request }}
                                            </td>
                                            <td class="text-center">
                                                {{ $item->qty }}
                                            </td>
                                            <td class="text-center">
                                                <input type="number" 
                                                    name="items[{{ $item->id }}][qty_approved]" 
                                                    class="form-control qty-approved" 
                                                    min="0"
                                                    max="{{ $item->atkItem->current_stock ?? '' }}" 
                                                    value="{{ old('items.' . $item->id . '.qty_approved', $item['qty'] ?? '') }}" 
                                                    data-requested="{{ $item->qty }}"
                                                    data-available="{{ $item->atkItem->current_stock ?? 0 }}"
                                                    required>
                                            </td>
                                            <td class="text-center">
                                                {{ $item->atkItem->current_stock ?? '' }}
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
                                <a href="{{ route('atk.out-requests.index') }}" class="btn btn-outline-primary">Cancel</a>
                            </div>
                            <div class="col d-flex justify-content-end gap-2">
                                <button type="submit" name="action" id="rejectBtn" class="btn btn-danger" value="reject">
                                    <i class="fas fa-times me-1"></i> Reject
                                </button>
                                <button type="submit" name="action" id="approveBtn" class="btn btn-success" value="approve">
                                    <i class="fas fa-check me-1"></i>Approve
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const approveBtn = document.getElementById('approveBtn');
        const rejectBtn = document.getElementById('rejectBtn');

        if (approveBtn) {
            approveBtn.addEventListener('click', function (e) {
                e.preventDefault();
                confirmAction('Are you sure you want to approve this request?', function () {
                    const form = approveBtn.closest('form');

                    // Hapus input hidden sebelumnya jika ada
                    form.querySelector('input[name="action"]')?.remove();

                    // Tambahkan input hidden untuk nilai "approve"
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'action';
                    hiddenInput.value = 'approve';
                    form.appendChild(hiddenInput);

                    form.submit();
                });
            });
        }

        if (rejectBtn) {
            rejectBtn.addEventListener('click', function (e) {
                e.preventDefault();
                confirmAction('Are you sure you want to reject this request?', function () {
                    const form = rejectBtn.closest('form');

                    // Hapus input hidden sebelumnya jika ada
                    form.querySelector('input[name="action"]')?.remove();

                    // Tambahkan input hidden untuk nilai "reject"
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'action';
                    hiddenInput.value = 'reject';
                    form.appendChild(hiddenInput);

                    form.submit();
                });
            });
        }

        //qty_approved
        const inputs = document.querySelectorAll('.qty-approved');

        function updateColor(input) {
            const approved = parseInt(input.value) || 0;
            const requested = parseInt(input.dataset.requested) || 0;
            const available = parseInt(input.dataset.available) || 0;

            if (approved <= available) {
                input.classList.add('is-valid');
                input.classList.remove('is-invalid');
            } else {
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
            }
        }

        inputs.forEach(input => {
            updateColor(input); // on page load
            input.addEventListener('input', () => updateColor(input)); // on change
        });

        let errors = @json(session('approval_errors'));

        let listHtml = '<ul style="text-align:left;">';
        errors.forEach(function(msg) {
            listHtml += '<li>' + msg + '</li>';
        });
        listHtml += '</ul>';

        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            html: listHtml,
            confirmButtonText: 'OK'
        });
    });
</script>
@endpush