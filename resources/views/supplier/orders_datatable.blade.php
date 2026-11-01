@extends('layouts.app')

@section('ext-css')
<link rel="stylesheet" href="/assets/dist/libs/select2/css/select2.min.css">
<link rel="stylesheet" href="/assets/dist/libs/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="/assets/dist/libs/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="/assets/dist/libs/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/assets/dist/libs/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/assets/dist/libs/datatables-buttons/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="/assets/dist/libs/datatables-searchbuilder/css/searchBuilder.bootstrap4.min.css">
<link rel="stylesheet" href="/assets/dist/libs/datatables-searchpanes/css/searchPanes.bootstrap4.min.css">
<link rel="stylesheet" href="/assets/dist/libs/datatables-select/css/select.bootstrap4.min.css">
<link rel="stylesheet" href="/assets/dist/libs/fontawesome-free/css/all.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('ext-menu')
@if(auth()->user()->isSupplier())
<li class="nav-item {{ request()->is('spareparts') ? 'active' : '' }}">
    <a href="/spareparts" class="nav-link ">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
        </span>
        <span class="nav-link-title">Data Produk</span>
    </a>
</li>
@endif
@endsection

@section('content')
 <div class="container py-4">
     {{-- HEADER --}}
    <div class="d-flex align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="bi bi-inbox me-1"></i> Order Masuk (Yajra DataTables)
            </h4>
            <small class="text-muted">Urut berdasarkan prioritas pengiriman (Fuzzy Sugeno)</small>
        </div>
    </div>

    {{-- ADVANCED FILTER CARD --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold"><i class="bi bi-filter"></i> Filter Data Lanjutan</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                {{-- Tanggal Mulai --}}
                <div class="col-md-3">
                    <label for="start_date" class="form-label small fw-semibold">Tanggal Mulai:</label>
                    <input type="date" id="start_date" class="form-control form-control-sm">
                </div>

                {{-- Tanggal Akhir --}}
                <div class="col-md-3">
                    <label for="end_date" class="form-label small fw-semibold">Tanggal Akhir:</label>
                    <input type="date" id="end_date" class="form-control form-control-sm">
                </div>

                {{-- Filter Keputusan Pengiriman --}}
                <div class="col-md-3">
                    <label for="delivery_decision" class="form-label small fw-semibold">Keputusan Pengiriman:</label>
                    <select id="delivery_decision" class="form-select form-select-sm">
                        <option value="">-- Semua Keputusan --</option>
                        <option value="Cepat">Cepat</option>
                        <option value="Normal">Normal</option>
                        <option value="Lambat">Lambat</option>
                    </select>
                </div>

                {{-- Filter Status Order --}}
                <div class="col-md-3">
                    <label for="status" class="form-label small fw-semibold">Status Order:</label>
                    <select id="status" class="form-select form-select-sm">
                        <option value="">-- Semua Status --</option>
                        <option value="draft">Draft</option>
                        <option value="submitted">Submitted</option>
                        <option value="processed">Processed</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>

                {{-- Filter Toko --}}
                <div class="col-md-4">
                    <label for="store_id" class="form-label small fw-semibold">Toko:</label>
                    <select id="store_id" class="form-select form-select-sm">
                        <option value="">-- Semua Toko --</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}">{{ $store->store_name }} ({{ $store->city }})</option>
                        @endforeach
                    </select>
                </div>

                {{-- Action Buttons --}}
                <div class="col-md-8 d-flex align-items-end justify-content-end gap-2">
                    <button type="button" id="btn-reset" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
                    </button>
                    <button type="button" id="btn-filter" class="btn btn-primary btn-sm px-4">
                        <i class="bi bi-search"></i> Terapkan Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="d-flex my-4 flex-row-reverse align-items-center">
                <div><span>&nbsp;</span></div>
                <div>
                    <button id="process-all-btn" class="btn btn-success btn-sm">
                        <i class="bi bi-play-fill"></i> Proses Semua Fuzzy
                    </button>
                </div>
            </div>
            <table id="shipments-table" class="table table-hover align-middle mb-0" style="width:100%">
                <thead class="bg-light">
                    <tr>
                        <th>Kode Order</th>
                        <th>Toko</th>
                        <th>Kota</th>
                        <th class="text-center">Status Order</th>
                        <th class="text-center">Keputusan</th>
                        <th class="text-center">Fuzzy</th>
                        <th class="text-center">Ongkir</th>
                        <th class="text-center" width="120">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
 </div>
@endsection
@section('scripts')
<script src="/assets/dist/libs/jquery/jquery.min.js"></script>
<script src="/assets/dist/libs/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/dist/libs/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/assets/dist/libs/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/assets/dist/libs/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="/assets/dist/libs/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="/assets/dist/libs/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="/assets/dist/libs/jszip/jszip.min.js"></script>
<script src="/assets/dist/libs/pdfmake/pdfmake.min.js"></script>
<script src="/assets/dist/libs/pdfmake/vfs_fonts.js"></script>
<script src="/assets/dist/libs/datatables-searchbuilder/js/dataTables.searchBuilder.min.js"></script>
<script src="/assets/dist/libs/datatables-searchbuilder/js/searchBuilder.bootstrap4.min.js"></script>
<script src="/assets/dist/libs/datatables-searchpanes/js/dataTables.searchPanes.min.js"></script>
<script src="/assets/dist/libs/datatables-searchpanes/js/searchPanes.bootstrap4.min.js"></script>
<script src="/assets/dist/libs/datatables-select/js/dataTables.select.min.js"></script>
<script src="/assets/dist/libs/datatables-select/js/select.bootstrap4.min.js"></script>

<script>
$(document).ready(function(){
    // Client-side session state tracking map
    const clientOrderStates = JSON.parse(sessionStorage.getItem('client_shipment_states')) || {};

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let table = $('#shipments-table').DataTable({
        processing: true,
        serverSide: true,
        dom: "<'row mb-3'<'col-md-6'B><'col-md-6'f>>" +
             "<'row'<'col-md-12'tr>>" +
             "<'row mt-3'<'col-md-5'i><'col-md-7'p>>",
        buttons: [
            {
                extend: 'pdfHtml5',
                text: '<i class="bi bi-file-earmark-pdf"></i> Export PDF Resmi',
                className: 'btn btn-danger btn-sm',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                },
                customize: function (doc) {
                    // Pastikan vfs terikat dengan aman untuk menghindari error M_ID
                    if (typeof pdfMake !== 'undefined' && typeof pdfMake.vfs === 'undefined' && typeof pdfFonts !== 'undefined') {
                        pdfMake.vfs = pdfFonts.pdfMake.vfs;
                    }

                    doc.defaultStyle.fontSize = 9;
                    doc.styles.tableHeader.fontSize = 10;
                    doc.styles.tableHeader.fillColor = '#343a40';
                    doc.styles.tableHeader.alignment = 'center';

                    // Header Laporan Resmi
                    doc.content.splice(0, 0, {
                        text: 'LAPORAN RESMI PRIORITAS PENGIRIMAN ORDER (FUZZY SUGENO)',
                        fontSize: 14,
                        bold: true,
                        alignment: 'center',
                        margin: [0, 0, 0, 5]
                    }, {
                        text: 'Dicetak pada: ' + new Date().toLocaleString('id-ID'),
                        fontSize: 8,
                        italics: true,
                        alignment: 'center',
                        margin: [0, 0, 0, 15]
                    });

                    // Validasi pengaman agar properti widths tidak undefined
                    if (doc.content[2] && doc.content[2].table) {
                        doc.content[2].table.widths = ['15%', '20%', '15%', '10%', '12%', '13%', '15%'];
                    }

                    // Footer halaman laporan
                    doc['footer'] = function(currentPage, pageCount) {
                        return {
                            text: 'Halaman ' + currentPage.toString() + ' dari ' + pageCount.toString(),
                            alignment: 'right',
                            fontSize: 8,
                            margin: [20, 10, 20, 0]
                        };
                    };
                }
            }
        ],
        ajax: {
            url: "{{ route('shipments.data') }}",
            data: function (d) {
                d.start_date = $('#start_date').val();
                d.end_date = $('#end_date').val();
                d.delivery_decision = $('#delivery_decision').val();
                d.status = $('#status').val();
                d.store_id = $('#store_id').val();
            }
        },
        columns: [
            { data: 'order_code', name: 'order_code', className: 'fw-semibold text-primary' },
            { data: 'store_name', name: 'store.store_name' },
            { data: 'city', name: 'store.city' },
            { data: 'status_badge', name: 'status', className: 'text-center', orderable: false, searchable: false },
            { data: 'decision_badge', name: 'shipment.delivery_decision', className: 'text-center', orderable: false, searchable: false },
            { data: 'fuzzy_score', name: 'shipment.fuzzy_score', className: 'text-center' },
            { data: 'shipping_cost', name: 'shipment.shipping_cost', className: 'text-center', orderable: false, searchable: false },
            { data: 'action', name: 'action', className: 'text-center', orderable: false, searchable: false }
        ],
        rowCallback: function(row, data) {
            let state = clientOrderStates[data.id];
            if (state === 'processing') {
                $(row).find('.status-text, .badge').first().text('processing').removeClass('bg-secondary bg-success').addClass('bg-warning text-dark');
            } else if (state === 'completed' || (data.shipment && data.shipment.fuzzy_score !== null)) {
                $(row).find('.process-btn').replaceWith('<span class="text-muted small">Selesai</span>');
            }
        }
    });

    // Trigger Filter Saat Tombol Ditekan
    $('#btn-filter').click(function() {
        table.draw();
    });

    // Reset Semua Filter
    $('#btn-reset').click(function() {
        $('#start_date').val('');
        $('#end_date').val('');
        $('#delivery_decision').val('');
        $('#status').val('');
        $('#store_id').val('');
        table.draw();
    });

    function updateClientState(orderId, status) {
        clientOrderStates[orderId] = status;
        sessionStorage.setItem('client_shipment_states', JSON.stringify(clientOrderStates));
    }

    // Single Row AJAX Post Button Click
    $('#shipments-table').on('click', '.process-btn', function() {
        let btn = $(this);
        let row = btn.closest('tr');
        let orderId = btn.attr('id') || btn.data('id');

        updateClientState(orderId, 'processing');
        btn.prop('disabled', true).text('Menghitung...');

        $.ajax({
            url: `/shipments/${orderId}/fuzzy`,
            type: 'POST',
            success: function(response) {
                if(response.success) {
                    updateClientState(orderId, 'completed');
                    btn.replaceWith('<span class="text-muted small">Selesai</span>');
                    table.ajax.reload(null, false);
                }
            },
            error: function(xhr) {
                updateClientState(orderId, 'failed');
                btn.prop('disabled', false).text('Proses Fuzzy');
                alert('Gagal memproses order ID: ' + orderId);
            }
        });
    });

    // Process All Rows Asynchronously
    $('#process-all-btn').click(async function() {
        let processButtons = $('.process-btn');
        if(processButtons.length === 0) {
            alert('Tidak ada baris yang perlu diproses pada filter ini.');
            return;
        }

        $(this).prop('disabled', true).text('Memproses Semua...');

        for (let i = 0; i < processButtons.length; i++) {
            await new Promise((resolve) => {
                setTimeout(() => {
                    processButtons.eq(i).trigger('click');
                    setTimeout(resolve, 300);
                }, 150);
            });
        }

        $(this).prop('disabled', false).text('Proses Semua Asinkron');
    });
});
</script>
@endsection
