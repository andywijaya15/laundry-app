<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('phone', 'like', '%'.$request->search.'%');
            });
        }

        $customers = $query->latest()->paginate(15)->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($customers);
        }

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|regex:/^(?:\+62|0)8[1-9][0-9]{7,10}$/',
            'address' => 'nullable|string|max:500',
        ]);

        $customer = Customer::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($customer, 201);
        }

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|regex:/^(?:\+62|0)8[1-9][0-9]{7,10}$/',
            'address' => 'nullable|string|max:500',
        ]);

        $customer->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($customer);
        }

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil diperbarui.');
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');

        $customers = Customer::where(function ($q) use ($query) {
            $q->where('name', 'like', '%'.$query.'%')
                ->orWhere('phone', 'like', '%'.$query.'%');
        })
            ->limit(10)
            ->get(['id', 'name', 'phone', 'address']);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($customers);
        }

        return view('customers.partials._search-result', compact('customers'));
    }
}
