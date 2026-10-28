@extends('layouts.app')

@section('content')
    <div class="container-xl">

        {{-- HEADER --}}
        <div class="page-header mb-4">
            <h2 class="page-title">Edit Data Toko</h2>
        </div>

        {{-- ALERT --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- FORM --}}
        <form method="POST" action="{{ route('store.profile.update') }}" class="card">
            @csrf

            <div class="card-body">

                {{-- KODE TOKO --}}
                <div class="mb-3">
                    <label class="form-label">Kode Toko</label>
                    <input type="text" class="form-control" value="{{ $store->store_code }}" disabled>
                </div>

                {{-- NAMA TOKO --}}
                <div class="mb-3">
                    <label class="form-label">Nama Toko</label>
                    <input type="text" name="store_name" class="form-control"
                        value="{{ old('store_name', $store->store_name) }}">
                </div>

                {{-- NAMA PEMILIK --}}
                <div class="mb-3">
                    <label class="form-label">Nama Pemilik</label>
                    <input type="text" name="owner_name" class="form-control"
                        value="{{ old('owner_name', $store->owner_name) }}">
                </div>

                {{-- PHONE & EMAIL --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $store->phone) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $store->email) }}">
                    </div>
                </div>

                {{-- ALAMAT --}}
                <div class="mb-3">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea name="address" id="address" class="form-control" rows="3">{{ old('address', $store->address) }}</textarea>
                </div>

                {{-- MAP --}}
                <div class="mb-3">
                    <label class="form-label">Lokasi Toko (Klik pada Peta)</label>
                    <div id="map" style="height: 350px; border-radius: 8px;"></div>
                </div>

                {{-- LAT LNG (HIDDEN / READONLY) --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Latitude</label>
                        <input type="text" name="latitude" id="latitude" class="form-control"
                            value="{{ old('latitude', $store->latitude) }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Longitude</label>
                        <input type="text" name="longitude" id="longitude" class="form-control"
                            value="{{ old('longitude', $store->longitude) }}" readonly>
                    </div>
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="card-footer text-end">
                <button class="btn btn-primary">
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
@endsection
@section('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            const addressInput = document.getElementById('address');

            const defaultLat = latInput.value || -6.200000; // Jakarta
            const defaultLng = lngInput.value || 106.816666;

            const map = L.map('map').setView([defaultLat, defaultLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            let marker = L.marker([defaultLat, defaultLng], {
                draggable: true
            }).addTo(map);

            // ==========================
            // REVERSE GEOCODING FUNCTION
            // ==========================
            async function fetchAddress(lat, lng) {
                try {
                    const res = await fetch(
                        `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`
                    );
                    const data = await res.json();

                    if (data && data.display_name) {
                        addressInput.value = data.display_name;
                    }
                } catch (error) {
                    console.error('Gagal mengambil alamat', error);
                }
            }

            // ==========================
            // DRAG MARKER
            // ==========================
            marker.on('dragend', function(e) {
                const pos = marker.getLatLng();

                latInput.value = pos.lat.toFixed(6);
                lngInput.value = pos.lng.toFixed(6);

                fetchAddress(pos.lat, pos.lng);
            });

            // ==========================
            // CLICK MAP
            // ==========================
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);

                latInput.value = e.latlng.lat.toFixed(6);
                lngInput.value = e.latlng.lng.toFixed(6);

                fetchAddress(e.latlng.lat, e.latlng.lng);
            });

        });
    </script>
@endsection
