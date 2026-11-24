<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Menampilkan daftar seluruh layanan laundry pada halaman admin.
     */
    public function index()
    {
        $services = Service::latest()->paginate(10);

        return view('admin.services.index', compact('services'));
    }

    /**
     * Menampilkan form untuk menambahkan layanan laundry baru.
     */
    public function create()
    {
        return view('admin.services.create');
    }

    /**
     * Menyimpan data layanan laundry baru ke database beserta upload gambar.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'harga_per_kg' => 'required|integer|min:1',
            'unit' => 'required|in:kg,pcs',
            'estimasi_waktu' => 'required|string|max:255',
        ]);
        Service::create($request->only('nama_layanan', 'harga_per_kg', 'unit', 'estimasi_waktu'));

        return redirect()->route('services.index')->with('success', 'Data layanan laundry berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit layanan laundry berdasarkan data yang dipilih.
     */
    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Memperbarui data layanan laundry di database dan mengganti gambar jika diunggah ulang.
     */
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'harga_per_kg' => 'required|integer|min:1',
            'unit' => 'required|in:kg,pcs',
            'estimasi_waktu' => 'required|string|max:255',
        ]);
        $service->update($request->only('nama_layanan', 'harga_per_kg', 'unit', 'estimasi_waktu'));

        return redirect()->route('services.index')->with('success', 'Data layanan laundry berhasil diperbarui!');
    }

    /**
     * Menghapus data layanan laundry beserta file gambarnya dari storage.
     */
    public function destroy(Service $service)
    {
        if ($service->orders()->exists()) {
            return back()->with('error', 'Layanan tidak dapat dihapus karena masih digunakan transaksi.');
        }
        $service->delete();

        return redirect()->route('services.index')->with('success', 'Data layanan laundry berhasil dihapus!');
    }
}
