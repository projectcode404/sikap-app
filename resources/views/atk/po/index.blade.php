@extends('layouts.app')

@section('title', 'Daftar PO ATK')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>📦 Daftar PO ATK</h4>
        <a href="{{ route('po-atk.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah PO
        </a>
    </div>

    <div id="poAtkGrid" style="height: 500px;" class="ag-theme-alpine"></div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const columnDefs = [
        { headerName: "PO Number", field: "po_number" },
        { headerName: "Tanggal PO", field: "po_date" },
        { headerName: "Supplier", field: "supplier_name" },
        { headerName: "Status", field: "status" },
        {
            headerName: "Actions",
            cellRenderer: function (params) {
                return `
                    <a href="/po-atk/${params.data.id}/edit" class="btn btn-sm btn-warning me-1">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="/po-atk/${params.data.id}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-eye"></i>
                    </a>
                `;
            },
            width: 120
        }
    ];

    const gridOptions = {
        columnDefs: columnDefs,
        rowData: [],
        pagination: true,
        paginationPageSize: 20,
        defaultColDef: {
            sortable: true,
            filter: true
        },
        onGridReady: async function(params) {
            const response = await fetch('{{ route("po-atk.api") }}');
            const data = await response.json();
            params.api.setRowData(data);
        }
    };

    const gridDiv = document.querySelector('#poAtkGrid');
    new agGrid.Grid(gridDiv, gridOptions);
});
</script>
@endpush