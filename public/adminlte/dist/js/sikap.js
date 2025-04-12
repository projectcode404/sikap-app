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

// AG Grid
async function initializeAGGrid(gridSelector, columnDefs, apiRoute) {
    const gridDiv = document.querySelector(gridSelector);
    if (!gridDiv) {
        console.error(`❌ Elemen ${gridSelector} tidak ditemukan.`);
        return;
    }

    // Tambahkan kolom aksi untuk Edit dan Delete
    columnDefs.push({
        headerName: "Actions",
        field: "actions",
        cellRenderer: function (params) {
            return `
                <button class="btn btn-sm btn-warning edit-btn" data-id="${params.data.id}">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger delete-btn" data-id="${params.data.id}">
                    <i class="fas fa-trash"></i>
                </button>
            `;
        },
        width: 120,
        sortable: false,
        filter: false,
    });

    const gridOptions = agGrid.createGrid(gridDiv, {
        columnDefs: columnDefs,
        rowData: [],
        pagination: true,
        paginationPageSize: 20,
        onGridReady: async function (params) {
            console.log(`✅ AG Grid siap untuk ${gridSelector}, mengambil data...`);
            gridOptions.api = params.api;
            await fetchData(gridOptions, apiRoute, gridSelector);
        }
    });

    return gridOptions;
}

async function fetchData(gridOptions, apiRoute, gridSelector) {
    try {
        console.log(`✅ Memulai fetch API untuk ${apiRoute}...`);
        const response = await fetch(apiRoute, {
            method: "GET",
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            credentials: "same-origin"
        });

        if (!response.ok) throw new Error(`❌ Gagal mengambil data dari ${apiRoute}.`);

        const data = await response.json();
        console.log(`✅ Data diterima dari ${apiRoute}:`, data);

        if (gridOptions.api) {
            console.log(`✅ Memasukkan data ke AG Grid...`);
            gridOptions.api.applyTransaction({ add: data });

            // Tunggu sejenak lalu tambahkan event listener untuk tombol aksi
            setTimeout(() => attachEventListeners(gridSelector), 500);
        } else {
            console.warn("❌ AG Grid belum siap, menunggu...");
            setTimeout(() => fetchData(gridOptions, apiRoute, gridSelector), 500);
        }
    } catch (error) {
        console.error(`❌ Error fetching data dari ${apiRoute}:`, error);
    }
}

// 🔹 Fungsi untuk menambahkan event listener ke tombol Edit dan Delete
function attachEventListeners(gridSelector) {
    document.querySelectorAll(`${gridSelector} .edit-btn`).forEach(button => {
        button.addEventListener("click", function () {
            const id = this.getAttribute("data-id");
            console.log(`📝 Edit data dengan ID: ${id}`);
            // Tambahkan logika untuk edit data di sini
        });
    });

    document.querySelectorAll(`${gridSelector} .delete-btn`).forEach(button => {
        button.addEventListener("click", function () {
            const id = this.getAttribute("data-id");
            console.log(`🗑️ Delete data dengan ID: ${id}`);
            if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                deleteData(id);
            }
        });
    });
}

// 🔹 Fungsi untuk menghapus data (simulasi)
async function deleteData(id) {
    console.log(`⏳ Menghapus data ID ${id}...`);
    // Implementasikan AJAX request untuk menghapus data di server jika perlu
    setTimeout(() => {
        console.log(`✅ Data dengan ID ${id} berhasil dihapus!`);
    }, 1000);
}
// End AG Grid

