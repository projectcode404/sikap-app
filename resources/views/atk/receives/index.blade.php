@extends('layouts.app')

@section('styles')
    <!-- ✅ Load AG Grid Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community@33.1.1/styles/ag-theme-quartz.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
@endsection

@section('content')
<div class="container">
    <div class="mb-3 pt-3 d-flex justify-content-between align-items-center">
        <h2>Receive Order List</h2>
    </div>
    <div id="sikapGrid" class="ag-theme-quartz receives-grid" style="height: 500px; width: 100%;"></div>
</div>
@endsection

@section('scripts')
    <!-- ✅ Load AG Grid Library -->
    <script src="https://cdn.jsdelivr.net/npm/ag-grid-community@33.1.1/dist/ag-grid-community.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const receives = [
                    { headerName: "PO Number", field: "po_number", minWidth: 100, sortable: true, filter: true },
                    { headerName: "Receives Date", field: "receive_date", minWidth: 100, sortable: true, filter: true },
                    { headerName: "Received By", field: "receiver_name", minWidth: 100, sortable: true, filter: true },
                    { headerName: "Note", field: "note", minWidth: 200, sortable: true, filter: true },
                    { headerName: "Receipt File", field: "receipt_file", 
                        cellRenderer: function(params) {
                            if (!params.value) return '-';
                            return `<a href="${params.value}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-file-pdf"></i> View File
                                    </a>`;
                        }, minWidth: 200, sortable: true, filter: true 
                    },
            ];

            initializeAGGrid('.receives-grid', receives, "{{ route('atk.receives.api') }}", [
                {
                    type: 'view',
                    title: 'View',
                    icon: 'fa-eye',
                    class: 'btn-outline-primary',
                    handler: ({ id }) => {
                        window.location.href = `/atk/receives/${id}`;
                    }
                },
            ]);
        });
    </script>
@endsection
