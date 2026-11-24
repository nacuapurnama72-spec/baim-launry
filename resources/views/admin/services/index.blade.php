@include('layouts.modern-start', ['title' => 'Data Layanan'])
<div class="d-flex justify-content-between align-items-center mb-3"><h4 class="fw-bold mb-0">Data Layanan</h4><a class="btn btn-primary" href="{{ route('services.create') }}"><i class="bi bi-plus-lg me-1"></i>Tambah Layanan</a></div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
<div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table align-middle mb-0"><thead class="table-light"><tr><th>Nama Layanan</th><th>Harga</th><th>Satuan</th><th>Estimasi</th><th class="text-end">Aksi</th></tr></thead><tbody>
@forelse($services as $service)<tr><td class="fw-semibold">{{ $service->nama_layanan }}</td><td>Rp {{ number_format($service->harga_per_kg,0,',','.') }}</td><td>/{{ $service->unit }}</td><td>{{ $service->estimasi_waktu }}</td><td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('services.edit',$service) }}">Edit</a><form class="d-inline" method="POST" action="{{ route('services.destroy',$service) }}" onsubmit="return confirm('Hapus layanan ini?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button></form></td></tr>
@empty<tr><td colspan="5" class="text-center py-4">Belum ada layanan</td></tr>@endforelse
</tbody></table></div><div class="card-footer bg-white">{{ $services->links() }}</div></div>
@include('layouts.modern-end')
