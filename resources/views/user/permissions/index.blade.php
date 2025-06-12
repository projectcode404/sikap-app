@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community@33.1.1/styles/ag-theme-quartz.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
@endsection

@section('content')
<div class="container">
    <div class="mb-3 pt-3 d-flex justify-content-between align-items-center">
        <h2 class="mb-0">Permission List</h2>
        <a href="{{ route('user.permissions.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> Add Permission
        </a>
    </div>
    <div id="sikapGrid" class="ag-theme-quartz permissions-grid" style="height: 500px; width: 100%;"></div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/ag-grid-community@33.1.1/dist/ag-grid-community.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const columnDefs = [
                { headerName: "Permission Name", field: "name", minWidth: 300, sortable: true, filter: true },
                { headerName: "Created At", field: "created_at", minWidth: 200, sortable: true, filter: true }
            ];

            initializeAGGrid(".permissions-grid", columnDefs, "{{ route('user.permissions.api') }}", [
                {
                    type: 'edit',
                    title: 'Edit',
                    icon: 'fa-edit',
                    class: 'btn-warning'
                },
                { 
                    type: 'delete', 
                    title: 'Delete', 
                    icon: 'fa-trash', 
                    class: 'btn-danger'
                },
            ]);
        });
    </script>
@endsection