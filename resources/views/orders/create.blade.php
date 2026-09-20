@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Order Baru</h1>
        <a href="{{ route('orders.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm mt-2 inline-block">← Kembali</a>
    </div>

    @if ($errors->any())
        <div class="mb-4 bg-red-50 text-red-700 px-4 py-3 rounded-md">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('orders.store') }}" class="space-y-6">
        @csrf

        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Informasi Pelanggan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-1">Pelanggan *</label>
                    <select id="customer_id" name="customer_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Pilih pelanggan</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} ({{ $customer->phone }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="estimated_done" class="block text-sm font-medium text-gray-700 mb-1">Estimasi Selesai</label>
                    <input type="date" id="estimated_done" name="estimated_done" value="{{ old('estimated_done') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Item Order</h2>
            <div id="items-container" class="space-y-4">
                <div class="item-row grid grid-cols-12 gap-4 items-end">
                    <div class="col-span-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Layanan *</label>
                        <select name="items[0][service_id]" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 service-select">
                            <option value="">Pilih layanan</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" data-price="{{ $service->price }}" data-unit="{{ $service->unit }}">
                                    {{ $service->name }} ({{ $service->unit === 'kg' ? 'Rp '.number_format($service->price,0,',','.') : 'Rp '.number_format($service->price,0,',','.'/item) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kuantitas *</label>
                        <input type="number" name="items[0][qty]" step="0.01" min="0.01" max="999.99" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 qty-input">
                    </div>
                    <div class="col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subtotal</label>
                        <div class="px-3 py-2 bg-gray-50 rounded-md text-sm subtotal">Rp 0</div>
                    </div>
                    <div class="col-span-1">
                        <button type="button" class="remove-item text-red-600 hover:text-red-900 text-sm">✕</button>
                    </div>
                </div>
            </div>
            <button type="button" id="add-item" class="mt-4 text-indigo-600 hover:text-indigo-900 text-sm font-medium">+ Tambah Item</button>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center">
                <span class="text-lg font-medium text-gray-900">Total</span>
                <span id="total-price" class="text-xl font-bold text-gray-900">Rp 0</span>
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('orders.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Batal</a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Simpan Order</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 1;
    const container = document.getElementById('items-container');
    const addBtn = document.getElementById('add-item');
    const totalDisplay = document.getElementById('total-price');

    function updateTotals() {
        let total = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const select = row.querySelector('.service-select');
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const option = select.options[select.selectedIndex];
            const price = parseFloat(option?.dataset?.price || 0);
            const subtotal = qty * price;
            row.querySelector('.subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
            total += subtotal;
        });
        totalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    addBtn.addEventListener('click', function() {
        const firstRow = container.querySelector('.item-row');
        const newRow = firstRow.cloneNode(true);
        newRow.querySelectorAll('select, input').forEach(el => {
            el.name = el.name.replace(/\[0\]/, '[' + itemIndex + ']');
            el.value = '';
        });
        newRow.querySelector('.subtotal').textContent = 'Rp 0';
        container.appendChild(newRow);
        itemIndex++;
        attachEvents();
    });

    function attachEvents() {
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.onclick = function() {
                if (document.querySelectorAll('.item-row').length > 1) {
                    this.closest('.item-row').remove();
                    updateTotals();
                }
            };
        });
        document.querySelectorAll('.service-select').forEach(select => {
            select.onchange = updateTotals;
        });
        document.querySelectorAll('.qty-input').forEach(input => {
            input.oninput = updateTotals;
        });
    }

    attachEvents();
});
</script>
@endsection