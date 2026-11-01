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
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="bi bi-tools me-1"></i> Manajemen Spareparts</h4>
            <small class="text-muted">Kelola data sparepart ban dan bobot fuzzy</small>
        </div>
        <div>
            <button id="btn-add" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Tambah Sparepart
            </button>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table id="spareparts-table" class="table table-hover align-middle mb-0" style="width:100%">
                <thead class="bg-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Sparepart</th>
                        <th>Brand</th>
                        <th>Ukuran</th>
                        <th>Tipe</th>
                        <th class="text-center">Berat (Kg)</th>
                        <th class="text-center">Fuzzy Value</th>
                        <th class="text-center">Stok</th>
                        <th class="text-end">Harga</th>
                        <th class="text-center" width="100">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

{{-- MODAL FORM (TAMBAH / EDIT) --}}
<div class="modal fade" id="sparepartModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="sparepartForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Sparepart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="sparepart_id" name="sparepart_id">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nama Sparepart:</label>
                            <input type="text" class="form-control form-control-sm" name="name" id="name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Brand:</label>
                            <input type="text" class="form-control form-control-sm" name="brand" id="brand">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Ukuran (Size):</label>
                            <input type="text" class="form-control form-control-sm" name="size" id="size">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Tipe Ban:</label>
                            <select class="form-select form-select-sm" name="type" id="type" required>
                                <option value="tubetype">Tubetype</option>
                                <option value="tubeless">Tubeless</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Berat (Kg):</label>
                            <input type="number" step="0.01" class="form-control form-control-sm" name="weight" id="weight" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Fuzzy Weight Value:</label>
                            <select class="form-select form-select-sm" name="fuzzy_weight_value" id="fuzzy_weight_value" required>
                                <option value="1">1 = Ringan</option>
                                <option value="2">2 = Sedang</option>
                                <option value="3">3 = Berat</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Stok:</label>
                            <input type="number" class="form-control form-control-sm" name="stock" id="stock" value="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Harga (Rp):</label>
                            <input type="number" step="0.01" class="form-control form-control-sm" name="price" id="price">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="saveBtn">Simpan</button>
                </div>
            </div>
        </form>
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
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let table = $('#spareparts-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('spareparts.data') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'name', name: 'name', className: 'fw-semibold' },
            { data: 'brand', name: 'brand' },
            { data: 'size', name: 'size' },
            { data: 'type', name: 'type' },
            { data: 'formatted_weight', name: 'weight', className: 'text-center' },
            { data: 'fuzzy_badge', name: 'fuzzy_weight_value', className: 'text-center', orderable: false, searchable: false },
            { data: 'stock', name: 'stock', className: 'text-center' },
            { data: 'formatted_price', name: 'price', className: 'text-end' },
            { data: 'action', name: 'action', className: 'text-center', orderable: false, searchable: false }
        ]
    });

    // Buka Modal Tambah
    $('#btn-add').click(function() {
        $('#sparepartForm').trigger("reset");
        $('#sparepart_id').val('');
        $('#modalTitle').text('Tambah Sparepart');
        $('#sparepartModal').modal('show');
    });

    // Simpan Data (Tambah / Update)
    $('#sparepartForm').submit(function(e) {
        e.preventDefault();
        let id = $('#sparepart_id').val();
        let url = id ? `/spareparts/${id}` : "{{ route('spareparts.store') }}";
        let type = id ? 'PUT' : 'POST';

        $('#saveBtn').prop('disabled', true).text('Menyimpan...');

        $.ajax({
            url: url,
            type: type,
            data: $(this).serialize(),
            success: function(response) {
                $('#sparepartModal').modal('hide');
                table.ajax.reload(null, false);
                alert(response.message);
            },
            error: function(xhr) {
                alert('Terjadi kesalahan saat menyimpan data.');
            },
            complete: function() {
                $('#saveBtn').prop('disabled', false).text('Simpan');
            }
        });
    });

    // Buka Modal Edit
    $('#spareparts-table').on('click', '.edit-btn', function() {
        let id = $(this).data('id');
        $.get(`/spareparts/${id}`, function(data) {
            $('#sparepart_id').val(data.id);
            $('#name').val(data.name);
            $('#brand').val(data.brand);
            $('#size').val(data.size);
            $('#type').val(data.type);
            $('#weight').val(data.weight);
            $('#fuzzy_weight_value').val(data.fuzzy_weight_value);
            $('#stock').val(data.stock);
            $('#price').val(data.price);

            $('#modalTitle').text('Edit Sparepart');
            $('#sparepartModal').modal('show');
        });
    });

    // Hapus Data
    $('#spareparts-table').on('click', '.delete-btn', function() {
        let id = $(this).data('id');
        if (confirm('Apakah Anda yakin ingin menghapus sparepart ini?')) {
            $.ajax({
                url: `/spareparts/${id}`,
                type: 'DELETE',
                success: function(response) {
                    table.ajax.reload(null, false);
                    alert(response.message);
                },
                error: function(xhr) {
                    alert('Gagal menghapus data.');
                }
            });
        }
    });
});
</script>
@endsection
