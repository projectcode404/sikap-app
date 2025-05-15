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
        <a href="{{ route('atk.po-atk.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Add PO ATK
        </a>
    </div>
    <div id="sikapGrid" class="ag-theme-quartz po-atk-grid" style="height: 500px; width: 100%;"></div>
</div>
@endsection

@section('scripts')
    <!-- ✅ Load AG Grid Library -->
    <script src="https://cdn.jsdelivr.net/npm/ag-grid-community@33.1.1/dist/ag-grid-community.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const poAtkColumn = [
                    { headerName: "ID", field: "id", minWidth: 150, maxWidth: 150, sortable: true, filter: true },
                    { headerName: "PO Number SAP", field: "po_number", minWidth: 250, sortable: true, filter: true },
                    { headerName: "PO Date", field: "po_date", minWidth: 250, sortable: true, filter: true },
                    { headerName: "Schedule Date", field: "schedule_date", minWidth: 250, sortable: true, filter: true },
                    { headerName: "Supplier", field: "supplier_name", minWidth: 250, sortable: true, filter: true },
                    { headerName: "Status", field: "status", minWidth: 250, sortable: true, filter: true },
                    { headerName: "Created By", field: "created_by", minWidth: 250, sortable: true, filter: true },
            ];

            initializeAGGrid(".po-atk-grid", poAtkColumn, "{{ route('atk.po-atk.api') }}");
        });
    </script>
@endsection
