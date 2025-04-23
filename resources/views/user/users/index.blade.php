@extends('layouts.app')

@section('styles')
    <!-- ✅ Load AG Grid Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community@33.1.1/styles/ag-theme-quartz.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
@endsection

@section('content')
<div class="container">
    <div class="mb-3 pt-3 d-flex justify-content-between align-items-center">
        <h2 class="mb-0">User List</h2>
        <a href="{{ route('user.users.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> Add User
        </a>
    </div>
    <div id="sikapGrid" class="ag-theme-quartz users-grid" style="height: 500px; width: 100%;"></div>
</div>
@endsection

@section('scripts')
    <!-- ✅ Load AG Grid Library -->
    <script src="https://cdn.jsdelivr.net/npm/ag-grid-community@33.1.1/dist/ag-grid-community.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const usersColumn = [
                { headerName: "Employee ID", field: "employee_id", minWidth: 150, sortable: true, filter: true },
                { headerName: "Employee Name", field: "full_name", minWidth: 500, sortable: true, filter: true },
                { headerName: "Role", field: "role", minWidth: 150, maxWidth: 300, sortable: true, filter: true },
                { headerName: "Status", field: "status", minWidth: 100, maxWidth: 120, sortable: true, filter: true },
            ];

            initializeAGGrid(".users-grid", usersColumn, "{{ route('user.users.api') }}");
        });
    </script>
@endsection
