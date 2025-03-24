@extends('layouts.app')

@section('styles')
    <!-- ✅ Load AG Grid Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community@33.1.1/styles/ag-theme-quartz.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
@endsection

@section('content')
<div class="container">
    <h2>Users List</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div id="myGrid" class="ag-theme-quartz" style="height: 500px; width: 100%;"></div>
</div>
@endsection

@section('scripts')
    <!-- ✅ Load AG Grid Library -->
    <script src="https://cdn.jsdelivr.net/npm/ag-grid-community@33.1.1/dist/ag-grid-community.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", async function () {
            const gridDiv = document.querySelector("#myGrid");

            // ✅ Buat AG Grid
            const gridOptions = agGrid.createGrid(gridDiv, {
                columnDefs: [
                    { headerName: "Employee ID", field: "employee_id", sortable: true, filter: true },
                    { headerName: "Name", field: "name", sortable: true, filter: true },
                    { headerName: "Email", field: "email", sortable: true, filter: true },
                    { headerName: "Status", field: "status", sortable: true, filter: true },
                    { 
                        headerName: "Actions", 
                        cellRenderer: (params) => {
                            if (!params.data) return "";
                            return `
                                <button onclick="editUser('${params.data.id}')" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteUser('${params.data.id}')" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            `;
                        } 
                    }
                ],
                rowData: [],
                pagination: true,
                paginationPageSize: 10,
                onGridReady: async function (params) {
                    console.log("✅ AG Grid siap, mengambil data...");
                    gridOptions.api = params.api;
                    await fetchUsers();
                }
            });

            // ✅ Fetch Data Users
            async function fetchUsers() {
                try {
                    console.log("✅ Memulai fetch API...");
                    const response = await fetch("{{ route('users.apiweb') }}", {
                        method: "GET",
                        credentials: "same-origin",
                        headers: { 
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}" 
                        }
                    });

                    if (!response.ok) throw new Error("❌ Gagal mengambil data users.");
                    const data = await response.json();
                    console.log("✅ Data users diterima:", data);

                    if (gridOptions.api) {
                        console.log("✅ Memasukkan data ke AG Grid...");
                        gridOptions.api.applyTransaction({ add: data });
                    } else {
                        console.warn("❌ AG Grid belum siap, menunggu...");
                        setTimeout(fetchUsers, 500);
                    }
                } catch (error) {
                    console.error("❌ Error fetching data:", error);
                }
            }

            // ✅ Fungsi Edit User
            window.editUser = function(id) {
                if (!id) return alert("Invalid user ID");
                window.location.href = `/users/${id}/edit`;
            };

            // ✅ Fungsi Delete User
            window.deleteUser = async function(id) {
                if (!id) return alert("Invalid user ID");

                if (confirm("Are you sure you want to delete this user?")) {
                    try {
                        const response = await fetch(`/users/${id}`, {
                            method: "DELETE",
                            headers: { 
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Content-Type": "application/json"
                            }
                        });

                        const result = await response.json().catch(() => null);
                        alert(result?.message || "User deleted successfully.");

                        // ✅ Refresh tabel setelah delete
                        await fetchUsers();
                    } catch (error) {
                        console.error("❌ Error deleting user:", error);
                        alert("Failed to delete user.");
                    }
                }
            };

        });
    </script>
@endsection
