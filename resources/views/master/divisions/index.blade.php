@extends('layouts.app')

@section('styles')
    <!-- ✅ Load AG Grid Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community@33.1.1/styles/ag-theme-quartz.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
@endsection

@section('content')
<div class="container">
    <h2>Divisions List</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div id="sikapGrid" class="ag-theme-quartz divisions-grid" style="height: 500px; width: 100%;"></div>
</div>
@endsection

@section('scripts')
    <!-- ✅ Load AG Grid Library -->
    <script src="https://cdn.jsdelivr.net/npm/ag-grid-community@33.1.1/dist/ag-grid-community.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const divisionsColumn = [
                    { headerName: "ID", field: "id", minWidth: 150, maxWidth: 150, sortable: true, filter: true },
                    { headerName: "Division", field: "name", minWidth: 250, sortable: true, filter: true },
            ];

            initializeAGGrid(".divisions-grid", divisionsColumn, "{{ route('master.divisions.api') }}", [
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
                }
            ]);
        });
    </script>
@endsection
