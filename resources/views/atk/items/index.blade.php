@extends('layouts.app')

@section('styles')
    <!-- ✅ Load AG Grid Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community@33.1.1/styles/ag-theme-quartz.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
@endsection

@section('content')
<div class="container">
    <div class="mb-3 pt-3 d-flex justify-content-between align-items-center">
        <h2 class="mb-0">ATK Items</h2>
        <a href="{{ route('atk.items.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> Add ATK
        </a>
    </div>
    <div id="sikapGrid" class="ag-theme-quartz atk-items-grid" style="height: 500px; width: 100%;"></div>
</div>
@endsection

@section('scripts')
    <!-- ✅ Load AG Grid Library -->
    <script src="https://cdn.jsdelivr.net/npm/ag-grid-community@33.1.1/dist/ag-grid-community.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const itemsColumn = [
                    { headerName: "ATK ID", field: "id", minWidth: 150, maxWidth: 150, sortable: true, filter: true },
                    { headerName: "ATK Name", field: "name", minWidth: 250, sortable: true, filter: true },
                    { headerName: "Unit", field: "unit", minWidth: 150, maxWidth: 150, sortable: true, filter: true },
                    { headerName: "Current Stock", field: "current_stock", minWidth: 150, sortable: true, filter: true },
                    { headerName: "Min Stock", field: "min_stock", minWidth: 150, sortable: true, filter: true },
            ];

            initializeAGGrid(".atk-items-grid", itemsColumn, "{{ route('atk.items.api') }}");
        });
    </script>
@endsection
