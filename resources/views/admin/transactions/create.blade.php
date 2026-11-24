@include('layouts.modern-start', ['title' => 'Tambah Transaksi'])
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1">Tambah Transaksi Laundry</h4><div class="text-secondary small">Isi data pelanggan dan jumlah cucian untuk menghitung pembayaran.</div></div>
</div>
<div class="card content-card"><div class="card-body p-4">
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ route('admin.transactions.store') }}">
@csrf
<div class="row g-3">
<div class="col-md-4"><label class="form-label fw-semibold">Pilih Pelanggan</label><select class="form-select" name="customer_id" id="customer_id"><option value="">Pelanggan baru</option>@foreach($customers as $customer)<option value="{{ $customer->id }}" data-name="{{ $customer->nama }}" data-phone="{{ $customer->no_whatsapp }}">{{ $customer->nama }} - {{ $customer->no_whatsapp }}</option>@endforeach</select></div>
<div class="col-md-4"><label class="form-label fw-semibold">Nama Pelanggan</label><input class="form-control" name="nama_pelanggan" id="nama_pelanggan" value="{{ old('nama_pelanggan') }}" required></div>
<div class="col-md-4"><label class="form-label fw-semibold">Nomor HP / WhatsApp</label><input class="form-control" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" required></div>
<div class="col-md-6"><label class="form-label fw-semibold">Jenis Layanan</label><select class="form-select" name="id_layanan" id="id_layanan" required><option value="">Pilih layanan</option>@foreach($services as $service)<option value="{{ $service->id }}" data-price="{{ $service->harga_per_kg }}" data-unit="{{ $service->unit }}">{{ $service->nama_layanan }} - Rp {{ number_format($service->harga_per_kg) }}/{{ $service->unit }}</option>@endforeach</select></div>
<div class="col-md-3"><label class="form-label fw-semibold" id="quantity_label">Berat / Jumlah</label><input class="form-control" type="number" step="0.1" min="0.1" name="berat_kg" id="berat_kg" value="{{ old('berat_kg') }}" required></div>
<div class="col-md-3"><label class="form-label fw-semibold">Status Pembayaran</label><select class="form-select" name="payment_status" required><option value="Belum Bayar">Belum Bayar</option><option value="Lunas">Lunas</option></select></div>
<div class="col-md-3"><label class="form-label fw-semibold">Tanggal Masuk</label><input class="form-control" type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required></div>
<div class="col-12"><label class="form-label fw-semibold">Catatan</label><textarea class="form-control" name="catatan" rows="2">{{ old('catatan') }}</textarea></div>
<div class="col-12"><div class="alert alert-primary d-flex justify-content-between mb-0"><span class="fw-semibold">Total Pembayaran</span><strong id="total" class="fs-5">Rp 0</strong></div></div>
</div>
<div class="mt-4"><button class="btn btn-primary px-4">Simpan Transaksi</button><a class="btn btn-light ms-2" href="{{ route('admin.transactions.index') }}">Batal</a></div>
</form></div></div>
<script>
function hitung(){const s=document.getElementById('id_layanan');const selected=s.options[s.selectedIndex];const harga=parseInt(selected?.dataset.price||0);const unit=selected?.dataset.unit||'';const berat=parseFloat(document.getElementById('berat_kg').value||0);document.getElementById('quantity_label').textContent=unit==='pcs'?'Jumlah (pcs)':'Berat (kg)';document.getElementById('total').textContent='Rp '+Math.round(harga*berat).toLocaleString('id-ID')}
document.getElementById('id_layanan').addEventListener('change',hitung);document.getElementById('berat_kg').addEventListener('input',hitung)
document.getElementById('customer_id').addEventListener('change',function(){const selected=this.options[this.selectedIndex];document.getElementById('nama_pelanggan').value=selected.dataset.name||'';document.getElementById('no_hp').value=selected.dataset.phone||''})
</script>
@include('layouts.modern-end')
