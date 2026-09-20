<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $services = $query->latest()->paginate(15)->withQueryString();

        return view('services.index', compact('services'));
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:services,name',
            'unit' => 'required|in:kg,item',
            'price' => 'required|numeric|min:0|max:999999.99',
            'is_active' => 'boolean',
        ]);

        Service::create($validated);

        return redirect()->route('services.index')
            ->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:services,name,'.$service->id,
            'unit' => 'required|in:kg,item',
            'price' => 'required|numeric|min:0|max:999999.99',
            'is_active' => 'boolean',
        ]);

        $service->update($validated);

        return redirect()->route('services.index')
            ->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Service $service)
    {
        $service->update(['is_active' => false]);

        return redirect()->route('services.index')
            ->with('success', 'Layanan dinonaktifkan.');
    }
}
