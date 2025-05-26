@extends('layouts.app')

@section('styles')
    <!-- ✅ Load AG Grid Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community@33.1.1/styles/ag-theme-quartz.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
@endsection

@section('content')
<div class="container">
    <div class="mb-3 pt-3 d-flex justify-content-between align-items-center">
        <h2>{{ __('messages.atk_purchase_order_list') }}</h2>
        <a href="{{ route('atk.purchase-orders.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> {{ __('messages.create_atk_purchase_order') }}
        </a>
    </div>
    <div id="sikapGrid" class="ag-theme-quartz purchase-order-grid" style="height: 500px; width: 100%;"></div>
    <!-- <pre>{{ print_r(session()->all(), true) }}</pre> -->
     <div class="modal fade" id="grModal" tabindex="-1" aria-labelledby="grModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="grForm">
                @csrf
                <input type="hidden" name="purchase_order_id" id="gr_po_id">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="grModalLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="gr_number_sap" class="form-label">{{ __('messages.gr_number_sap') }}</label>
                            <input type="text" class="form-control" name="gr_number_sap" id="gr_number_sap" placeholder="{{ __('messages.input_gr_number_sap') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- ✅ Load AG Grid Library -->
    <script src="https://cdn.jsdelivr.net/npm/ag-grid-community@33.1.1/dist/ag-grid-community.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const purchaseOrders = [
                    { headerName: "{{ __('messages.po_number_sap') }}", field: "po_number", minWidth: 100, sortable: true, filter: true },
                    { headerName: "{{ __('messages.po_date') }}", field: "po_date", minWidth: 100, sortable: true, filter: true },
                    { headerName: "{{ __('messages.schedule_date') }}", field: "schedule_date", minWidth: 100, sortable: true, filter: true },
                    { headerName: "{{ __('messages.supplier_name') }}", field: "supplier_name", minWidth: 200, sortable: true, filter: true },
                    { headerName: "{{ __('messages.created_by') }}", field: "created_by", minWidth: 200, sortable: true, filter: true },
                    { headerName: "{{ __('messages.status') }}", field: "status", cellRenderer: (params) => renderStatusBadge(params.value), minWidth: 100, sortable: true, filter: true },
            ];

            initializeAGGrid('.purchase-order-grid', purchaseOrders, "{{ route('atk.purchase-orders.api') }}", [
                {
                    type: 'view',
                    title: `{{ __('messages.view') }}`,
                    icon: 'fa-eye',
                    class: 'btn-outline-primary',
                    handler: ({ id }) => {
                        window.location.href = `/atk/purchase-orders/${id}`;
                    }
                },
                {
                    type: 'gr',
                    title: 'Good Receipt',
                    icon: 'fa-thumbs-up',
                    class: 'btn-outline-success',
                    visible: row => row.status == 'received',
                    handler: ({ id, gridDiv }) => {
                        const modal = new bootstrap.Modal(document.getElementById('grModal'));
                        const row = gridDiv.gridApi.getRowNode(id)?.data;

                        document.getElementById('gr_po_id').value = id;
                        document.getElementById('grModalLabel').textContent = `Good Receive #${row.po_number}`;
                        modal.show();
                    }
                },
                {
                    type: 'receive',
                    title: `{{ __('messages.receive') }}`,
                    icon: 'fa-inbox',
                    class: 'btn-outline-success',
                    visible: row => !['received','completed'].includes(row.status),
                    handler: ({ id }) => {
                        window.location.href = `/atk/purchase-orders/${id}/receive`;
                    }
                },
                { 
                    type: 'edit', 
                    title: `{{ __('messages.edit') }}`, 
                    icon: 'fa-edit', 
                    class: 'btn-outline-warning',
                    visible: row => row.status == 'open'
                },
                {
                    type: 'delete', 
                    title: `{{ __('messages.delete') }}`, 
                    icon: 'fa-trash', 
                    class: 'btn-outline-danger',
                    visible: row => row.status == 'open'
                }
            ],
            "{{ __('messages.action') }}");
        });

        document.getElementById('grForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);
            const gridDiv = document.querySelector('.purchase-order-grid');
            const apiRoute = "{{ route('atk.purchase-orders.api') }}";

            try {
                const response = await fetch(`{{ route('atk.purchase-orders.gr') }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData
                });

                if (!response.ok) throw new Error('Failed to save GR!');

                showSuccessToast('GR saved successfully!');
                bootstrap.Modal.getInstance(document.getElementById('grModal')).hide();
                await fetchData(apiRoute, gridDiv);
            } catch (error) {
                console.error(error);
                showErrorToast('Failed to save GR!');
            }
        });
    </script>
@endsection
