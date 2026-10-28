<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
<meta http-equiv="X-UA-Compatible" content="ie=edge" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Lex Fuzzy</title>
<link rel="icon" type="image/png" href="{{ asset('assets/images/logo_hr.png') }}">
<link href="{{ asset('assets/dist/css/tabler.min.css?1738096685') }}" rel="stylesheet" />
<link href="{{ asset('assets/dist/css/tabler-flags.min.css?1738096685') }}" rel="stylesheet" />
<link href="{{ asset('assets/dist/css/tabler-socials.min.css?1738096685') }}" rel="stylesheet" />
<link href="{{ asset('assets/dist/css/tabler-payments.min.css?1738096685') }}" rel="stylesheet" />
<link href="{{ asset('assets/dist/css/tabler-vendors.min.css?1738096685') }}" rel="stylesheet" />
<link href="{{ asset('assets/dist/css/tabler-marketing.min.css?1738096685') }}" rel="stylesheet" />
<link href="{{ asset('assets/dist/css/demo.min.css?1738096685') }}" rel="stylesheet" />
<link href="{{ asset('assets/dist/libs/dropzone/dist/dropzone.css?1738096684') }}" rel="stylesheet">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<style>
    @import url('https://rsms.me/inter/inter.css');

    /* =========================
   MODERN CREDENTIAL CARD
========================= */
    .credential-card {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 18px;
        font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .credential-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #ffffff;
        border-radius: 10px;
        padding: 12px 14px;
        margin-bottom: 10px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    }

    .credential-row .label {
        font-size: 12px;
        color: #6b7280;
    }

    .credential-row .value {
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
        font-size: 14px;
        font-weight: 600;
        color: #111827;
    }

    .copy-btn {
        width: 100%;
        margin-top: 12px;
        background: #2563eb;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 12px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .swal2-popup {
        font-size: 0.95rem;
    }

    @media (max-width: 576px) {
        .swal2-actions {
            flex-direction: column;
            gap: 0.5rem;
        }

        .swal2-confirm,
        .swal2-cancel {
            width: 100%;
        }
    }

    .copy-btn:hover {
        background: #1d4ed8;
    }

    .copy-btn.copied {
        background: #16a34a;
    }

    .credential-note {
        margin-top: 10px;
        font-size: 12px;
        color: #6b7280;
    }


    /* Batasi tinggi preview agar modal tidak kepanjangan */
    .table-preview-scroll {
        max-height: 380px;
        /* 🔥 atur sesuai kebutuhan */
        overflow-y: auto;
        border: 1px solid #dee2e6;
    }

    /* Sticky header */
    .table-preview-scroll thead th {
        position: sticky;
        top: 0;
        background: #f8f9fa;
        z-index: 2;
    }

    /* Sticky footer (opsional) */
    .table-preview-scroll tfoot td {
        position: sticky;
        bottom: 0;
        background: #ffffff;
        z-index: 1;
        font-weight: bold;
    }

    .autocomplete-suggestions {
        border: 1px solid #ddd;
        background: white;
        position: absolute;
        z-index: 9999;
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
        border-radius: 4px;
    }

    .autocomplete-suggestion {
        padding: 8px 12px;
        cursor: pointer;
    }

    .autocomplete-suggestion:hover {
        background: #f0f0f0;
    }

    /* Mengatur tinggi container agar bisa di-scroll */
    .table-container {
        min-height: 500px;
        max-height: 650px;
        /* Sesuaikan tinggi tabel sesuai kebutuhan */
        overflow-y: auto;
        border: 1px solid #ddd;
    }

    .table-container-confirm {
        min-height: 100px;
        max-height: 250px;
        /* Sesuaikan tinggi tabel sesuai kebutuhan */
        overflow-y: auto;
        border: 1px solid #ddd;
    }

    /* Membuat header tetap di atas */
    .sticky-header {
        position: sticky;
        top: 0;
        background: white;
        z-index: 1000;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    }

    /* Agar header tetap terlihat jelas */
    .sticky-header th {
        background-color: #f8f9fa;
        padding: 10px;
        text-align: left;
    }

    /* Atur lebar minimal & maksimal untuk kolom ALAMAT */
    th.w-alamat,
    td.w-alamat {
        min-width: 200px;
        max-width: 400px;
        word-wrap: break-word;
        white-space: normal;
    }

    th.w-alamatP,
    td.w-alamatP {
        min-width: 200px;
        max-width: 400px;
        word-wrap: break-word;
        white-space: normal;
    }

    th.w-tanggal,
    td.w-tanggal {
        min-width: 200px;
        max-width: 400px;
        word-wrap: break-word;
        white-space: normal;
    }

    #commentContainer {
        max-height: 300px;
        /* Batasi tinggi maksimal agar maksimal 3 komentar terlihat */
        overflow-y: auto;
        /* Aktifkan scroll jika lebih dari 3 komentar */
        display: flex;
        flex-direction: column-reverse;
        /* Agar komentar terbaru tetap di atas */
    }

    #employeeList {
        z-index: 1060 !important;
        /* Lebih tinggi dari modal */
        position: absolute;
        background: white;
        max-height: 250px;
        /* Batasi tinggi agar tidak panjang */
        overflow-y: auto;
        /* Scroll jika terlalu panjang */
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .logo-navbar {
        width: 45px;
        /* Atur ukuran sesuai kebutuhan */
        height: auto;
        padding: 0;
        /* Hilangkan margin */
        filter: brightness(0) saturate(100%) invert(29%) sepia(96%) saturate(749%) hue-rotate(185deg) brightness(95%) contrast(95%);
    }

    .logo-login {
        width: 100px;
        /* Atur ukuran sesuai kebutuhan */
        height: 70px;
        padding: 0;
        /* Hilangkan margin */
        filter: brightness(0) saturate(100%) invert(29%) sepia(96%) saturate(749%) hue-rotate(185deg) brightness(95%) contrast(95%);
    }

    .logo-size {
        width: 140px;
        height: auto;
        padding: 0;
    }

    .highlighted {
        background-color: #ffff99 !important;
        /* Warna kuning lembut */
        transition: background-color 0.3s ease-in-out;
    }

    .sticky-column {
        position: sticky;
        left: 0;
        background: white;
        z-index: 2;
    }

    .sticky-column input[type="checkbox"] {
        margin-left: 4px;
    }

    .burden-row td:first-child {
        position: sticky;
        left: 0;
        background: #fff;
        z-index: 1;
    }

    /* Mengatur overflow pada dropdown */
    .select-employee {
        width: 200px;
        /* Tentukan lebar yang sesuai dengan kebutuhan Anda */
        max-width: 100%;
        white-space: nowrap;
        overflow-x: auto;
    }

    /* Mengatur lebar dropdown select */
    .select-employee option {
        white-space: nowrap;
        /* Agar teks tidak terpotong */
        padding-left: 10px;
        /* Memberikan jarak pada teks */
    }

    /* Warna merah untuk hari Minggu */
    .flatpickr-day.sunday {
        color: red !important;
        font-weight: bold;
    }

    /* Tambahkan gaya titik di atas angka */
    .flatpickr-day.holiday-national {
        color: red !important;
        font-weight: bold;
        position: relative;
    }

    /* Titik benar-benar di atas angka tanggal */
    .flatpickr-day.holiday-national::before {
        content: '•';
        position: absolute;
        /* Angkat titik ke atas elemen */
        left: 50%;
        transform: translateX(-50%);
        font-size: 12px;
        line-height: 1;
        color: red;
    }

    .filter-box {
        position: absolute;
        background: #fff;
        border: 1px solid #ddd;
        padding: 5px;
        z-index: 999;
        width: 180px;
        margin-top: 5px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }

    th {
        position: relative;
    }

    .sort-asc,
    .sort-desc {
        background-color: #f0f0f0;
        font-weight: bold;
    }

    .angka {
        font-variant-numeric: tabular-nums;
        /* Angka punya lebar seragam */
        font-feature-settings: "tnum";
        /* Fallback untuk browser lama */
        font-family: "Roboto Mono", "Courier New", monospace;
        /* Monospaced fallback */
        white-space: nowrap;
        font-weight: 900;
    }
</style>
