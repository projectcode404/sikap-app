@extends('layouts.app')

@section('styles')
    <!-- ✅ Load AG Grid Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community@33.1.1/styles/ag-theme-quartz.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
@endsection

@section('content')
<div class="container">
    <div class="mb-3 pt-3 d-flex justify-content-between align-items-center">
        <h2>Purchase Order List</h2>
        <a href="{{ route('atk.purchase-orders.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Add PO ATK
        </a>
    </div>
    <!-- <span class="badge bg-secondary">Open</span>[Receive] [Edit] [Delete]
    <span class="badge bg-warning text-dark">Partial</span>[Receive] [View]
    <span class="badge bg-info text-dark">Received</span>[View] [Mark Completed]
    <span class="badge bg-success">Completed</span>[View] -->
    <div id="sikapGrid" class="ag-theme-quartz purchase-order-grid" style="height: 500px; width: 100%;"></div>
</div>
@endsection

@section('scripts')
    <!-- ✅ Load AG Grid Library -->
    <script src="https://cdn.jsdelivr.net/npm/ag-grid-community@33.1.1/dist/ag-grid-community.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const purchaseOrders = [
                    { headerName: "PO Number SAP", field: "po_number", minWidth: 250, sortable: true, filter: true },
                    { headerName: "PO Date", field: "po_date", minWidth: 250, sortable: true, filter: true },
                    { headerName: "Schedule Date", field: "schedule_date", minWidth: 250, sortable: true, filter: true },
                    { headerName: "Supplier", field: "supplier_name", minWidth: 250, sortable: true, filter: true },
                    { headerName: "Status", field: "status", minWidth: 250, sortable: true, filter: true },
                    { headerName: "Created By", field: "created_by", minWidth: 250, sortable: true, filter: true },
            ];

            // initializeAGGrid(".purchase-order-grid", purchaseOrders, "{{ route('atk.purchase-orders.api') }}");
            initializeAGGrid('.purchase-order-grid', purchaseOrders, "{{ route('atk.purchase-orders.api') }}", [
                {
                    type: 'view',
                    title: 'View',
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
                    visible: row => row.status == 'received'
                },
                {
                    type: 'receive',
                    title: 'Receive',
                    icon: 'fa-inbox',
                    class: 'btn-outline-success',
                    visible: row => !['received','completed'].includes(row.status),
                    handler: ({ id }) => {
                        window.location.href = `/atk/purchase-orders/${id}/receive`;
                    }
                },
                { 
                    type: 'edit', 
                    title: 'Edit', 
                    icon: 'fa-edit', 
                    class: 'btn-outline-warning',
                    visible: row => row.status == 'open'
                },
                {
                    type: 'delete', 
                    title: 'Delete', 
                    icon: 'fa-trash', 
                    class: 'btn-outline-danger',
                    visible: row => row.status == 'open'
                }
            ]);
        });
    </script>
@endsection
