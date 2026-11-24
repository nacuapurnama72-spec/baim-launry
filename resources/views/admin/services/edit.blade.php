@include('layouts.modern-start', ['title' => 'Edit Layanan'])
<h4 class="fw-bold mb-3">Edit Layanan</h4>
<div class="card border-0 shadow-sm"><div class="card-body p-4">
<form method="POST" action="{{ route('services.update',$service) }}">@csrf @method('PUT')
<div class="mb-3"><label class="form-label fw-semibold">Nama Layanan</label><input class="form-control" name="nama_layanan" value="{{ old('nama_layanan',$service->nama_layanan) }}" required></div>
<div class="mb-3"><label class="form-label fw-semibold">Harga per Kg / Pcs</label><input class="form-control" type="number" min="1" name="harga_per_kg" value="{{ old('harga_per_kg',$service->harga_per_kg) }}" required></div>
<div class="mb-3"><label class="form-label fw-semibold">Satuan</label><select class="form-select" name="unit" required><option value="kg" {{ $service->unit==='kg'?'selected':'' }}>Per Kilogram (kg)</option><option value="pcs" {{ $service->unit==='pcs'?'selected':'' }}>Per Pcs</option></select></div>
<div class="mb-3"><label class="form-label fw-semibold">Estimasi Waktu</label><input class="form-control" name="estimasi_waktu" value="{{ old('estimasi_waktu',$service->estimasi_waktu) }}" required></div>
<button class="btn btn-primary">Perbarui</button><a class="btn btn-light ms-2" href="{{ route('services.index') }}">Batal</a>
</form></div></div>
@include('layouts.modern-end')
