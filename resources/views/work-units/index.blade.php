@extends('layouts.app')

@section('styles')
    <!-- ✅ Load AG Grid Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community@33.1.1/styles/ag-theme-quartz.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
@endsection

@section('content')
<div class="container">
    <h2>Work Units List</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div id="sikapGrid" class="ag-theme-quartz work-units-grid" style="height: 500px; width: 100%;"></div>
</div>
@endsection

@section('scripts')
    <!-- ✅ Load AG Grid Library -->
    <script src="https://cdn.jsdelivr.net/npm/ag-grid-community@33.1.1/dist/ag-grid-community.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const workUnitsColumn = [
                { headerName: "Work Unit ID", field: "work_unit_id", sortable: true, filter: true },
                { headerName: "Work Unit", field: "name", sortable: true, filter: true },
                { headerName: "Type", field: "type", sortable: true, filter: true },
            ];

            initializeAGGrid(".work-units-grid", workUnitsColumn, "{{ route('work-units.api') }}");
        });
    </script>
@endsection
