@extends('layouts.app')

@section('styles')
    <!-- ✅ Load AG Grid Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community@33.1.1/styles/ag-theme-quartz.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
@endsection

@section('content')
<div class="container">
    <div class="mb-3 pt-3 d-flex justify-content-between align-items-center">
        <h2>ATK Request List</h2>
        <a href="{{ route('atk.out-requests.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> ATK Request
        </a>
    </div>

    <div id="sikapGrid" class="ag-theme-quartz out-request-grid" style="height: 500px; width: 100%;"></div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/ag-grid-community@33.1.1/dist/ag-grid-community.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const columns = [
                { headerName: "Work Unit", field: "work_unit", minWidth: 180, sortable: true, filter: true },
                { headerName: "Employee Name", field: "employee_name", minWidth: 200, sortable: true, filter: true },
                { headerName: "Position", field: "position_name", minWidth: 150, sortable: true, filter: true },
                { headerName: "Request Date", field: "request_date", minWidth: 130, sortable: true, filter: true },
                { headerName: "Period", field: "period", minWidth: 100, sortable: true, filter: true },
                { headerName: "Status", field: "status", minWidth: 120, cellRenderer: params => renderStatusBadge(params.value), sortable: true, filter: true },
                { headerName: "Created By", field: "created_by", minWidth: 180, sortable: true, filter: true },
            ];

            initializeAGGrid('.out-request-grid', columns, "{{ route('atk.out-requests.api') }}", [
                {
                    type: 'view',
                    title: 'Lihat Detail',
                    icon: 'fa-eye',
                    class: 'btn-outline-primary',
                    handler: ({ id }) => {
                        window.location.href = `/atk/out-requests/${id}`;
                    }
                },
                { 
                    type: 'edit', 
                    title: `{{ __('messages.edit') }}`, 
                    icon: 'fa-edit', 
                    class: 'btn-outline-warning',
                    visible: row => row.status == 'draft'
                },
                {
                    type: 'delete', 
                    title: `{{ __('messages.delete') }}`, 
                    icon: 'fa-trash', 
                    class: 'btn-outline-danger',
                    visible: row => row.status == 'draft'
                },
                {
                    type: 'approve',
                    title: 'Approve',
                    icon: 'fa-check',
                    class: 'btn-outline-success',
                    visible: row => row.status === 'submitted',
                    handler: ({ id }) => {
                        if (confirm("Yakin ingin menyetujui dan memproses permintaan ini?")) {
                            window.location.href = `/atk/out-requests/${id}/approve`;
                        }
                    }
                },
                {
                    type: 'cancel',
                    title: 'Reject',
                    icon: 'fa-times',
                    class: 'btn-outline-danger',
                    visible: row => row.status === 'submitted',
                    handler: ({ id }) => {
                        window.location.href = `/atk/out-requests/${id}/cancel`;
                    }
                }
            ], "Actions");
        });
    </script>
@endsection
