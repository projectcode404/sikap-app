@extends('layouts.app')

@section('styles')
    <!-- ✅ Load AG Grid Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community@33.1.1/styles/ag-theme-quartz.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
@endsection

@section('content')
<div class="container">
    <div class="mb-3 pt-3 d-flex justify-content-between align-items-center">
        <h2 class="mb-0">ATK Stock Adjustments</h2>
        <a href="{{ route('atk.adjustments.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> Add Adjustment
        </a>
    </div>
    <div id="sikapGrid" class="ag-theme-quartz adjustments-grid" style="height: 500px; width: 100%;"></div>
</div>
@endsection

@section('scripts')
    <!-- ✅ Load AG Grid Library -->
    <script src="https://cdn.jsdelivr.net/npm/ag-grid-community@33.1.1/dist/ag-grid-community.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const adjustmentsColumn = [
                { headerName: "ID", field: "id", minWidth: 80, maxWidth: 80, sortable: true },
                { headerName: "Item Name", field: "item_name", minWidth: 200, sortable: true, filter: true },
                { headerName: "Unit", field: "unit", minWidth: 100, sortable: true },
                { headerName: "Qty", field: "adjustment_qty", minWidth: 100, sortable: true },
                { headerName: "Reason", field: "reason_type", minWidth: 120, sortable: true },
                { headerName: "Date", field: "date", minWidth: 130, sortable: true },
                { headerName: "Adjusted By", field: "adjusted_by", minWidth: 180, sortable: true },
                { headerName: "Note", field: "note", minWidth: 250 },
            ];

            initializeAGGrid(".adjustments-grid", adjustmentsColumn, "{{ route('atk.adjustments.api') }}", [
                {
                    type: 'view',
                    title: 'View',
                    icon: 'fa-eye',
                    class: 'btn-outline-primary',
                    handler: ({ id }) => {
                        window.location.href = `/atk/adjustments/${id}`;
                    }
                }
            ]);
        });
    </script>
@endsection
