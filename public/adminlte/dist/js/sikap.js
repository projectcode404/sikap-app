//Begin OverlayScrollbars Configure
const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
const Default = {
    scrollbarTheme: 'os-theme-light',
    scrollbarAutoHide: 'leave',
    scrollbarClickScroll: true,
};
document.addEventListener('DOMContentLoaded', function () {
    const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
    if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
        OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
                theme: Default.scrollbarTheme,
                autoHide: Default.scrollbarAutoHide,
                clickScroll: Default.scrollbarClickScroll,
            },
        });
    }
});
//End OverlayScrollbars Configure

//Begin Color Mode Toggler
(() => {
    "use strict";

    const storedTheme = localStorage.getItem("theme");

    const getPreferredTheme = () => {
        if (storedTheme) {
            return storedTheme;
        }

        return window.matchMedia("(prefers-color-scheme: dark)").matches
        ? "dark"
        : "light";
    };

    const setTheme = function (theme) {
        if (
        theme === "auto" &&
        window.matchMedia("(prefers-color-scheme: dark)").matches
        ) {
        document.documentElement.setAttribute("data-bs-theme", "dark");
        } else {
        document.documentElement.setAttribute("data-bs-theme", theme);
        }
    };

    const setSidebarTheme = (theme) => {
        const sidebar = document.getElementById("app-sidebar");
        if (!sidebar) return;

        if (theme === "dark") {
            sidebar.classList.remove("bg-primary-subtle");
            sidebar.classList.add("bg-dark-subtle");
        } else {
            sidebar.classList.remove("bg-dark-subtle");
            sidebar.classList.add("bg-primary-subtle");
        }

        localStorage.setItem("sidebar-theme", theme);
    };

    const setGridTheme = (theme) => {
        const sikapGrid = document.getElementById("sikapGrid");
        if (!sikapGrid) return;

        if (theme === "dark") {
            sikapGrid.classList.remove("ag-theme-quartz");
            sikapGrid.classList.add("ag-theme-quartz-dark");
        } else {
            sikapGrid.classList.remove("ag-theme-quartz-dark");
            sikapGrid.classList.add("ag-theme-quartz");
        }

        localStorage.setItem("grid-theme", theme);
    };

    setTheme(getPreferredTheme());
    setSidebarTheme(getPreferredTheme());
    setGridTheme(getPreferredTheme());


    const showActiveTheme = (theme, focus = false) => {
        const themeSwitcher = document.querySelector("#bd-theme");

        if (!themeSwitcher) {
            return;
        }

        const themeSwitcherText = document.querySelector("#bd-theme-text");
        const activeThemeIcon = document.querySelector(".theme-icon-active i");
        const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`);
        const svgOfActiveBtn = btnToActive.querySelector("i").getAttribute("class");

        for (const element of document.querySelectorAll("[data-bs-theme-value]")) {
            element.classList.remove("active");
            element.setAttribute("aria-pressed", "false");
        }

        btnToActive.classList.add("active");
        btnToActive.setAttribute("aria-pressed", "true");
        activeThemeIcon.setAttribute("class", svgOfActiveBtn);
        const themeSwitcherLabel = `${themeSwitcherText.textContent} (${btnToActive.dataset.bsThemeValue})`;
        themeSwitcher.setAttribute("aria-label", themeSwitcherLabel);

        if (focus) {
            themeSwitcher.focus();
        }
    };

    window.addEventListener("DOMContentLoaded", () => {
        showActiveTheme(getPreferredTheme());

        for (const toggle of document.querySelectorAll("[data-bs-theme-value]")) {
        toggle.addEventListener("click", () => {
            const theme = toggle.getAttribute("data-bs-theme-value");
            localStorage.setItem("theme", theme);
            setTheme(theme);
            setSidebarTheme(theme);
            setGridTheme(theme);
            showActiveTheme(theme, true);
        });
        }
    });
})();
//End Color Mode Toggler

// // AG Grid (Community, Browser version)
// async function initializeAGGrid(gridSelector, columnDefs, apiRoute) {
//     const gridDiv = document.querySelector(gridSelector);
//     if (!gridDiv) return;

//     columnDefs.push({
//         headerName: "Actions",
//         field: "actions",
//         cellRenderer: function (params) {
//             return `
//             <div class="btn-group gap-1">
//                 <button title="Edit" class="btn btn-sm btn-warning edit-btn" data-id="${params.data.id}">
//                     <i class="fas fa-edit"></i>
//                 </button>
//                 <button title="Delete" class="btn btn-sm btn-danger delete-btn" data-id="${params.data.id}">
//                     <i class="fas fa-trash"></i>
//                 </button>
//             </div>
//             `;
//         },
//         width: 120,
//         sortable: false,
//         filter: false,
//     });

//     const gridOptions = {
//         columnDefs: columnDefs,
//         rowData: [],
//         pagination: true,
//         paginationPageSize: 20,
//         rowModelType: 'clientSide',
//         onGridReady: async function (params) {
//             window.gridApi = params.api; // ✅ gunakan global gridApi
//             await fetchData(apiRoute, gridSelector);
//         }
//     };

//     agGrid.createGrid(gridDiv, gridOptions);
// }


// async function fetchData(apiRoute, gridSelector) {
//     try {
//         const response = await fetch(apiRoute, {
//             headers: { 'X-Requested-With': 'XMLHttpRequest' },
//             credentials: "same-origin"
//         });

//         if (!response.ok) throw new Error(`❌ Failed fetch data from ${apiRoute}.`);

//         const data = await response.json();
//         if (window.gridApi) {
//             // 🔥 Hapus semua baris dulu
//             const allNodes = [];
//             window.gridApi.forEachNode(node => allNodes.push(node.data));
//             window.gridApi.applyTransaction({ remove: allNodes });

//             // ✅ Tambahkan data baru
//             window.gridApi.applyTransaction({ add: data });

//             // Pasang listener ulang
//             setTimeout(() => attachEventListeners(gridSelector), 100);
//         }
//     } catch (error) {
//         console.error(`❌ Error fetch data:`, error);
//     }
// }


// // 🔹 Fungsi untuk menambahkan event listener ke tombol Edit dan Delete
// function attachEventListeners(gridSelector, gridOptions) {
//     const gridDiv = document.querySelector(gridSelector);
//     if (gridDiv.classList.contains('listeners-attached')) return;
//     gridDiv.classList.add('listeners-attached');

//     gridDiv.addEventListener("click", function (e) {
//         const deleteBtn = e.target.closest(".delete-btn");
//         const editBtn = e.target.closest(".edit-btn");

//         if (editBtn) {
//             const id = editBtn.dataset.id;
//             const [module, resource] = window.location.pathname.split('/').filter(Boolean);
//             const editUrl = `/${module}/${resource}/${id}`;
//             window.location.href = `${editUrl}/edit`;
//         }

//         if (deleteBtn) {
//             const id = deleteBtn.dataset.id;
//             const [module, resource] = window.location.pathname.split('/').filter(Boolean);
//             const deleteUrl = `/${module}/${resource}/${id}`;
//             const apiUrl = `/${module}/${resource}/api`;

//             Swal.fire({
//                 title: 'Are you sure?',
//                 text: 'Data will be deleted permanently!',
//                 icon: 'warning',
//                 showCancelButton: true,
//                 confirmButtonText: 'Confirm',
//                 cancelButtonText: 'Cancel',
//                 reverseButtons: true
//             }).then(result => {
//                 if (result.isConfirmed) {
//                     deleteData(deleteUrl, apiUrl, gridSelector, gridOptions);
//                 }
//             });
//         }
//     });
// }

// // 🔹 Fungsi untuk menghapus data
// async function deleteData(deleteUrl, apiRoute, gridSelector) {
//     try {
//         const response = await fetch(deleteUrl, {
//             method: 'DELETE',
//             headers: {
//                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
//                 'X-Requested-With': 'XMLHttpRequest'
//             }
//         });

//         if (response.ok) {
//             showSuccessToast('Data deleted successfully.');
//             await fetchData(apiRoute, gridSelector); // reload grid
//         } else {
//             showErrorToast('Failed to delete data.');
//         }
//     } catch (error) {
//         console.error('❌ Delete error:', error);
//         showErrorToast('Something went wrong while deleting data.');
//     }
// }
// // End AG Grid

// Tom Select
function initTomSelectAjax(element, url, placeholder = 'Search...', extraConfig = {}) {
    if (!element) return null;

    return new TomSelect(element, {
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        placeholder: placeholder,
        load: function(query, callback) {
            if (!query.length) return callback();
            const urlWithQuery = `${url}?q=${encodeURIComponent(query)}`;
        
            fetch(urlWithQuery)
                .then(response => response.json())
                .then(results => callback(results))
                .catch(() => callback());
        },
        ...extraConfig
    });
}
// End Tom Select

// AG Grid (Community, Browser version) with Custom Action Buttons
async function initializeAGGrid(gridSelector, columnDefs, apiRoute, actionButtons = []) {
    const gridDiv = document.querySelector(gridSelector);
    if (!gridDiv) return;

    columnDefs.push({
        headerName: "Actions",
        field: "actions",
        cellRenderer: function (params) {
            const dataId = params.data.id;
            const buttonsHTML = actionButtons
            .filter(button => typeof button.visible !== 'function' || button.visible(params.data))
            .map(button => {
                return `
                    <button title="${button.title}" 
                            class="btn btn-sm ${button.class} action-btn" 
                            data-id="${dataId}" 
                            data-action="${button.type}">
                        <i class="fas ${button.icon}"></i>
                    </button>
                `;
            }).join('');

            return `<div class="btn-group gap-1">${buttonsHTML}</div>`;
        },
        width: 40 + (actionButtons.length * 40),
        sortable: false,
        filter: false,
    });

    const gridOptions = {
        columnDefs: columnDefs,
        rowData: [],
        pagination: true,
        paginationPageSize: 20,
        rowModelType: 'clientSide',
        context: { actionButtons },
        onGridReady: async function (params) {
            gridDiv.gridApi = params.api;
            gridDiv.gridOptions = gridOptions;
            await fetchData(apiRoute, gridDiv);
            attachEventListeners(gridDiv, apiRoute);
        }
    };

    agGrid.createGrid(gridDiv, gridOptions);
}

async function fetchData(apiRoute, gridDiv) {
    try {
        const response = await fetch(apiRoute, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            credentials: "same-origin"
        });

        if (!response.ok) throw new Error(`❌ Failed fetch data from ${apiRoute}.`);

        const data = await response.json();
        const gridApi = gridDiv.gridApi;

        if (gridApi) {
            const allNodes = [];
            gridApi.forEachNode(node => allNodes.push(node.data));
            gridApi.applyTransaction({ remove: allNodes });
            gridApi.applyTransaction({ add: data });
        }
    } catch (error) {
        console.error(`❌ Error fetch data:`, error);
    }
}

function attachEventListeners(gridDiv, apiRoute) {
    if (gridDiv.classList.contains('listeners-attached')) return;
    gridDiv.classList.add('listeners-attached');

    gridDiv.addEventListener("click", function (e) {
        const button = e.target.closest(".action-btn");
        if (!button) return;

        const id = button.dataset.id;
        const action = button.dataset.action;
        const [module, resource] = window.location.pathname.split('/').filter(Boolean);
        const actionButtons = gridDiv.gridOptions?.context?.actionButtons || [];
        const handler = actionButtons.find(btn => btn.type === action)?.handler;

        if (handler && typeof handler === 'function') {
            handler({ id, module, resource, gridDiv });
        } else {
            // default fallback actions
            switch(action) {
                case 'edit':
                    window.location.href = `/${module}/${resource}/${id}/edit`;
                    break;
                case 'delete':
                    const deleteUrl = `/${module}/${resource}/${id}`;
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Data will be deleted permanently!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Confirm',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then(result => {
                        if (result.isConfirmed) {
                            deleteData(deleteUrl, apiRoute, gridDiv);
                        }
                    });
                    break;
                default:
                    console.warn(`No handler defined for action: ${action}`);
            }
        }
    });
}

async function deleteData(deleteUrl, apiRoute, gridDiv) {
    try {
        const response = await fetch(deleteUrl, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (response.ok) {
            showSuccessToast('Data deleted successfully.');
            await fetchData(apiRoute, gridDiv);
        } else {
            showErrorToast('Failed to delete data.');
        }
    } catch (error) {
        console.error('❌ Delete error:', error);
        showErrorToast('Something went wrong while deleting data.');
    }
}

// AG Grid example usage
// initializeAGGrid("#gridContainer", columnDef, "/atk/purchase-orders/api", [
//     {
//         type: "receive",
//         title: "Receive",
//         icon: "fa-box",
//         class: "btn-success",
//         handler: ({ id }) => {
//             window.location.href = `/atk/purchase-orders/${id}/receive`;
//         }
//     },
//     {
//         type: "edit",
//         title: "Edit",
//         icon: "fa-edit",
//         class: "btn-warning",
//         handler: ({ id }) => {
//             window.location.href = `/atk/purchase-orders/${id}/edit`;
//         }
//     },
//     {
//         type: "delete",
//         title: "Delete",
//         icon: "fa-trash",
//         class: "btn-danger",
//         handler: ({ id }) => {
//             const deleteUrl = `/atk/purchase-orders/${id}`;
//             const apiUrl = `/atk/purchase-orders/api`;
//             handleDeleteAction(deleteUrl, apiUrl, "#gridContainer");
//         }
//     }
// ]);