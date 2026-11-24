@include('layouts.modern-start', ['title' => 'Rekap Transaksi'])
<div class="d-flex flex-wrap justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1">Rekap Transaksi</h4>
        <div class="text-secondary small">Kelola pembayaran, status cucian, dan cetak nota pelanggan.</div>
    </div>
    <a class="btn btn-primary px-3" href="{{ route('admin.transactions.create') }}">
        <i class="bi bi-plus-lg me-1"></i>Transaksi Baru
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
@endif

<div class="card content-card mb-3">
    <div class="card-body p-3 p-md-4">
        <form class="row g-2 align-items-end" method="GET">
            <div class="col-lg-4 col-md-6">
                <label class="form-label small fw-semibold text-secondary mb-1">Cari</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-secondary"></i></span>
                    <input class="form-control" name="search" value="{{ request('search') }}" placeholder="Nama atau kode transaksi">
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <label class="form-label small fw-semibold text-secondary mb-1">Tanggal dari</label>
                <input class="form-control" type="date" name="dari" value="{{ request('dari') }}">
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <label class="form-label small fw-semibold text-secondary mb-1">Tanggal sampai</label>
                <input class="form-control" type="date" name="sampai" value="{{ request('sampai') }}">
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="form-label small fw-semibold text-secondary mb-1">Status cucian</label>
                <select class="form-select" name="status">
                    <option value="">Semua status</option>
                    @foreach(['Diterima','Dicuci','Disetrika','Siap Diambil','Selesai'] as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="form-label small fw-semibold text-secondary mb-1">Pembayaran</label>
                <select class="form-select" name="payment_status">
                    <option value="">Semua</option>
                    <option value="Lunas" {{ request('payment_status')==='Lunas'?'selected':'' }}>Lunas</option>
                    <option value="Belum Bayar" {{ request('payment_status')==='Belum Bayar'?'selected':'' }}>Belum Bayar</option>
                </select>
            </div>
            <div class="col-12 d-flex justify-content-end gap-2 pt-1">
                @if(request()->query())
                    <a class="btn btn-light px-3" href="{{ route('admin.transactions.index') }}">Reset</a>
                @endif
                <button class="btn btn-primary px-4"><i class="bi bi-funnel me-1"></i>Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card content-card border-0 mb-3">
    <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3 px-4 py-3">
        <div>
            <div class="small text-secondary">Total pendapatan pada hasil filter</div>
            <div class="fw-bold text-success fs-5">Rp {{ number_format($income,0,',','.') }}</div>
        </div>
        <div class="text-secondary small">{{ $orders->total() }} transaksi ditemukan</div>
    </div>
</div>

<div class="card content-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Invoice</th>
                    <th>Kode Transaksi</th>
                    <th>Pelanggan</th>
                    <th>Layanan</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Tanggal</th>
                    <th>Status cucian</th>
                    <th class="text-end pe-4">Nota</th>
                </tr>
            </thead>
            <tbody>
            @forelse($orders as $order)
                <tr>
                    <td class="ps-4">
                        <span class="fw-bold text-dark">{{ $order->invoice_number ?? '-' }}</span>
                    </td>
                    <td>
                        <span class="badge text-bg-light border text-dark">{{ $order->kode_transaksi }}</span>
                    </td>
                    <td>
                        <div class="fw-semibold">{{ $order->nama_pelanggan }}</div>
                        <div class="small text-secondary">{{ $order->no_hp }}</div>
                    </td>
                    <td>{{ $order->service->nama_layanan }}</td>
                    <td>{{ number_format((float) $order->berat_kg, 1, ',', '.') }} {{ $order->service->unit }}</td>
                    <td class="fw-semibold">Rp {{ number_format($order->total_harga,0,',','.') }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.transactions.updatePayment',$order) }}">
                            @csrf
                            @method('PATCH')
                            <select name="payment_status" class="form-select form-select-sm fw-semibold {{ $order->payment_status === 'Lunas' ? 'text-success' : 'text-warning-emphasis' }}" onchange="this.form.submit()">
                                <option value="Belum Bayar" {{ $order->payment_status==='Belum Bayar'?'selected':'' }}>Belum Bayar</option>
                                <option value="Lunas" {{ $order->payment_status==='Lunas'?'selected':'' }}>Lunas</option>
                            </select>
                        </form>
                    </td>
                    <td>{{ $order->tanggal_masuk->format('d/m/Y') }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.transactions.updateStatus',$order) }}">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                @foreach(['Diterima','Dicuci','Disetrika','Siap Diambil','Selesai'] as $status)
                                    <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td class="text-end pe-4">
                        <a class="btn btn-sm btn-light border" target="_blank" title="Cetak nota" href="{{ route('admin.transactions.print',$order) }}">
                            <i class="bi bi-printer"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center py-5">
                        <div class="text-secondary mb-2"><i class="bi bi-receipt fs-3"></i></div>
                        <div class="fw-semibold">Belum ada transaksi</div>
                        <div class="small text-secondary">Ubah filter atau tambahkan transaksi baru.</div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
        <div class="card-footer bg-white py-3">{{ $orders->links() }}</div>
    @endif
</div>
@include('layouts.modern-end')
