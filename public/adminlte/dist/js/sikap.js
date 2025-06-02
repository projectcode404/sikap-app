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

function renderStatusBadge(status) {
    if (!status) return '';

    const classes = {
        open: "bg-info",
        partial: "bg-warning",
        received: "bg-primary",
        completed: "bg-success",
        canceled: "bg-danger",
        draft : "bg-info",
        submitted : "bg-primary",
        approved : "bg-success",
        rejected : "bg-danger",
        realized : "bg-warning",
        received : "bg-success",
        canceled : "bg-danger",
    };

    const badgeClass = classes[status] || "bg-secondary";
    return `
        <span class="badge rounded-pill ${badgeClass}" style="text-transform: capitalize;">
            ${status}
        </span>
    `;
}

// AG Grid (Community, Browser version) with Custom Action Buttons
async function initializeAGGrid(gridSelector, columnDefs, apiRoute, actionButtons = [], actionHeader = "Actions") {
    const gridDiv = document.querySelector(gridSelector);
    if (!gridDiv) return;

    columnDefs.push({
        headerName: actionHeader,
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

        getRowId: (params) => String(params.data.id),

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
        const rowNode = gridDiv.gridApi.getRowNode(id);
        const rowData = rowNode?.data;

        const handler = actionButtons.find(btn => btn.type === action)?.handler;
        if (handler && typeof handler === 'function') {
            handler({
                id,
                module,
                resource,
                gridDiv,
                row: rowData // ✅ inject full row data
            });
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
