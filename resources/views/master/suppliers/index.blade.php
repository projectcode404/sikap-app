@extends('layouts.app')

@section('styles')
    <!-- ✅ Load AG Grid Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community@33.1.1/styles/ag-theme-quartz.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
@endsection

@section('content')
<div class="container">
    <div class="mb-3 pt-3 d-flex justify-content-between align-items-center">
        <h2>Suppliers List</h2>
        <a href="{{ route('master.suppliers.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Add Supplier
        </a>
    </div>
    <div id="sikapGrid" class="ag-theme-quartz suppliers-grid" style="height: 500px; width: 100%;"></div>
</div>
@endsection

@section('scripts')
    <!-- ✅ Load AG Grid Library -->
    <script src="https://cdn.jsdelivr.net/npm/ag-grid-community@33.1.1/dist/ag-grid-community.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const suppliersColumn = [
                    { headerName: "Supplier Name", field: "name", minWidth: 250, sortable: true, filter: true },
                    { headerName: "Address", field: "address", minWidth: 250, sortable: true, filter: true },
                    { headerName: "Phone", field: "phone", minWidth: 150, sortable: true, filter: true },
                    { headerName: "PIC", field: "pic", minWidth: 150, sortable: true, filter: true },
                    { headerName: "Status", field: "status", minWidth: 100, sortable: true, filter: true },
            ];

            initializeAGGrid(".suppliers-grid", suppliersColumn, "{{ route('master.suppliers.api') }}");
        });
    </script>
@endsection
