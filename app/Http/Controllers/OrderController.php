<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public function index()
    {
        return redirect()->route('login');
    }

    public function create()
    {
        $services = Service::orderBy('nama_layanan')->get();
        $customers = Customer::orderBy('nama')->get();

        return view('admin.transactions.create', compact('services', 'customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['nullable', 'exists:customers,id'],
            'nama_pelanggan' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:20'],
            'id_layanan' => ['required', 'exists:services,id'],
            'berat_kg' => ['required', 'numeric', 'min:0.1'],
            'tanggal_masuk' => ['required', 'date'],
            'payment_status' => ['required', 'in:Lunas,Belum Bayar'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $service = Service::findOrFail($validated['id_layanan']);
        $total = (int) round((float) $validated['berat_kg'] * (int) $service->harga_per_kg);
        $start = Carbon::parse($validated['tanggal_masuk']);
        $estimated = match ($service->nama_layanan) {
            'Express', 'Superkilat' => $start->copy()->addHours(5),
            'Cuci Setrika', 'Ekspress' => $start->copy()->addDay(),
            default => $start->copy()->addDays(3),
        };

        $sequence = str_pad((string) (Order::count() + 1), 4, '0', STR_PAD_LEFT);
        $invoice = 'INV-'.now()->format('Ymd').'-'.$sequence;
        $transactionCode = 'TRX-'.now()->format('Ymd').'-'.$sequence;

        $order = Order::create([
            ...$validated,
            'kode_transaksi' => $transactionCode,
            'invoice_number' => $invoice,
            'total_harga' => $total,
            'estimasi_selesai' => $estimated,
            'status' => 'Diterima',
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.transactions.index')
            ->with('success', 'Transaksi '.$invoice.' berhasil ditambahkan.');
    }

    public function adminDashboard()
    {
        $today = now()->toDateString();
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        $todayTransactions = Order::whereDate('tanggal_masuk', $today)->count();
        $processing = Order::whereIn('status', ['Diterima', 'Dicuci', 'Disetrika'])->count();
        $ready = Order::where('status', 'Siap Diambil')->count();
        $monthlyIncome = Order::where('payment_status', 'Lunas')
            ->whereBetween('tanggal_masuk', [$monthStart, $monthEnd])
            ->sum('total_harga');
        $recent = Order::with(['customer', 'service'])->latest()->limit(5)->get();

        return view('dashboard', compact(
            'todayTransactions',
            'processing',
            'ready',
            'monthlyIncome',
            'recent'
        ));
    }

    public function transactions(Request $request)
    {
        $orders = Order::with(['customer', 'service', 'user'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');
                $query->where(function ($nested) use ($search) {
                    $nested->where('nama_pelanggan', 'like', "%{$search}%")
                        ->orWhere('invoice_number', 'like', "%{$search}%")
                        ->orWhere('kode_transaksi', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('payment_status'), fn ($query) => $query->where('payment_status', $request->string('payment_status')))
            ->when($request->filled('dari'), fn ($query) => $query->whereDate('tanggal_masuk', '>=', $request->date('dari')))
            ->when($request->filled('sampai'), fn ($query) => $query->whereDate('tanggal_masuk', '<=', $request->date('sampai')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $incomeQuery = Order::query()->where('payment_status', 'Lunas');
        if ($request->filled('dari')) {
            $incomeQuery->whereDate('tanggal_masuk', '>=', $request->date('dari'));
        }
        if ($request->filled('sampai')) {
            $incomeQuery->whereDate('tanggal_masuk', '<=', $request->date('sampai'));
        }
        $income = $incomeQuery->sum('total_harga');

        return view('admin.transactions.index', compact('orders', 'income'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:Diterima,Dicuci,Disetrika,Siap Diambil,Selesai'],
        ]);

        $order->update([
            'status' => $validated['status'],
            'tanggal_selesai' => $validated['status'] === 'Selesai' ? now() : null,
        ]);

        return back()->with('success', 'Status cucian berhasil diperbarui.');
    }

    public function updatePayment(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => ['required', 'in:Lunas,Belum Bayar'],
        ]);

        $order->update($validated);

        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    public function tracking()
    {
        return view('public.tracking');
    }

    public function track(Request $request)
    {
        $validated = $request->validate([
            'invoice' => ['required', 'string', 'max:50'],
        ]);

        $order = Order::with('service')
            ->where('invoice_number', $validated['invoice'])
            ->orWhere('kode_transaksi', $validated['invoice'])
            ->first();

        return view('public.tracking', compact('order'));
    }

    public function printReceipt(Order $order)
    {
        $order->load(['customer', 'service', 'user']);

        return view('admin.transactions.print', compact('order'));
    }

    public function report(Request $request)
    {
        $orders = $this->reportOrders($request);
        $income = (clone $orders)->where('payment_status', 'Lunas')->sum('total_harga');

        return view('admin.reports.index', [
            'orders' => $orders->paginate(15)->withQueryString(),
            'income' => $income,
        ]);
    }

    public function exportReport(Request $request): StreamedResponse
    {
        $orders = $this->reportOrders($request)->with(['customer', 'service'])->get();

        $filename = 'laporan-laundry-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Kode Invoice', 'Kode Transaksi', 'Pelanggan', 'WhatsApp', 'Layanan', 'Jumlah', 'Total', 'Pembayaran', 'Status', 'Tanggal Masuk']);
            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->invoice_number,
                    $order->kode_transaksi,
                    $order->nama_pelanggan,
                    $order->no_hp,
                    $order->service->nama_layanan ?? '-',
                    $order->berat_kg,
                    $order->total_harga,
                    $order->payment_status,
                    $order->status,
                    $order->tanggal_masuk->format('Y-m-d'),
                ]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    private function reportOrders(Request $request)
    {
        return Order::query()
            ->with(['customer', 'service'])
            ->when($request->filled('dari'), fn ($query) => $query->whereDate('tanggal_masuk', '>=', $request->date('dari')))
            ->when($request->filled('sampai'), fn ($query) => $query->whereDate('tanggal_masuk', '<=', $request->date('sampai')))
            ->when($request->filled('payment_status'), fn ($query) => $query->where('payment_status', $request->string('payment_status')))
            ->latest();
    }
}
