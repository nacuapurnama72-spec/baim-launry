<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $customers = Customer::withCount('transactions')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');
                $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('no_whatsapp', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function create(): View
    {
        return view('admin.customers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'no_whatsapp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string', 'max:1000'],
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Data pelanggan berhasil ditambahkan.');
    }

    public function edit(Customer $customer): View
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'no_whatsapp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string', 'max:1000'],
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        if ($customer->transactions()->exists()) {
            return back()->with('error', 'Pelanggan tidak dapat dihapus karena masih memiliki transaksi.');
        }

        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Data pelanggan berhasil dihapus.');
    }
}
