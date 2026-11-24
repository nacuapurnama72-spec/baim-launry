@include('layouts.modern-start', ['title' => 'Tambah Layanan'])
<h4 class="fw-bold mb-3">Tambah Layanan</h4>
<div class="card border-0 shadow-sm"><div class="card-body p-4">
<form method="POST" action="{{ route('services.store') }}">@csrf
<div class="mb-3"><label class="form-label fw-semibold">Nama Layanan</label><input class="form-control" name="nama_layanan" value="{{ old('nama_layanan') }}" required></div>
<div class="mb-3"><label class="form-label fw-semibold">Harga per Kg / Pcs</label><input class="form-control" type="number" min="1" name="harga_per_kg" value="{{ old('harga_per_kg') }}" required></div>
<div class="mb-3"><label class="form-label fw-semibold">Satuan</label><select class="form-select" name="unit" required><option value="kg">Per Kilogram (kg)</option><option value="pcs">Per Pcs</option></select></div>
<div class="mb-3"><label class="form-label fw-semibold">Estimasi Waktu</label><input class="form-control" name="estimasi_waktu" value="{{ old('estimasi_waktu') }}" placeholder="Contoh: 3-4 hari" required></div>
<button class="btn btn-primary">Simpan</button><a class="btn btn-light ms-2" href="{{ route('services.index') }}">Batal</a>
</form></div></div>
@include('layouts.modern-end')
