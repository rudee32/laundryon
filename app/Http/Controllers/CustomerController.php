<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::withCount('orders')->get();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'telegram_username' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z0-9_]{5,32}$/']
        ]);

        if ($request->telegram_username) {
            $request->merge([
                'telegram_username' => ltrim($request->telegram_username, '@')
            ]);
        }

        Customer::create($request->all());
        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'telegram_id' => 'nullable|string'
        ]);

        $customer->update($request->all());
        return redirect()->route('customers.index')->with('success', 'Data pelanggan berhasil diperbarui');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil dihapus');
    }
}
