@include('layouts.modern-start', ['title' => 'Edit Pelanggan'])
<h4 class="fw-bold mb-3">Edit Pelanggan</h4>
<div class="card border-0 shadow-sm"><div class="card-body p-4">
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ route('customers.update',$customer) }}">@csrf @method('PUT')
<div class="mb-3"><label class="form-label fw-semibold">Nama Pelanggan</label><input class="form-control" name="nama" value="{{ old('nama',$customer->nama) }}" required></div>
<div class="mb-3"><label class="form-label fw-semibold">No. WhatsApp</label><input class="form-control" name="no_whatsapp" value="{{ old('no_whatsapp',$customer->no_whatsapp) }}" required></div>
<div class="mb-3"><label class="form-label fw-semibold">Alamat</label><textarea class="form-control" name="alamat" rows="3" required>{{ old('alamat',$customer->alamat) }}</textarea></div>
<button class="btn btn-primary">Perbarui</button><a class="btn btn-light ms-2" href="{{ route('customers.index') }}">Batal</a>
</form></div></div>
@include('layouts.modern-end')
