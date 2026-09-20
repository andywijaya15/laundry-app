@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-800">
                &larr; Kembali
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6">Edit Item - {{ $order->order_code }}</h1>

            <form action="{{ route('orders.updateItems', $order) }}" method="POST" id="editItemsForm">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold">Item Layanan</h2>
                        <button type="button" id="addItemBtn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                            + Tambah Item
                        </button>
                    </div>

                    <div id="itemsContainer" class="space-y-4">
                        @foreach($order->items as $index => $item)
                        <div class="item-row border border-gray-300 rounded-lg p-4">
                            <div class="grid grid-cols-12 gap-4 items-end">
                                <div class="col-span-5">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Layanan</label>
                                    <select name="items[{{ $index }}][service_id]" class="service-select w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                        <option value="">Pilih Layanan</option>
                                        @foreach($services as $service)
                                        <option value="{{ $service->id }}" 
                                                data-price="{{ $service->price }}"
                                                {{ $item->service_id == $service->id ? 'selected' : '' }}>
                                            {{ $service->name }} (Rp {{ number_format($service->price, 0, ',', '.') }}/{{ $service->unit }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Qty</label>
                                    <input type="number" 
                                           name="items[{{ $index }}][qty]" 
                                           value="{{ $item->qty }}"
                                           class="qty-input w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                           min="1" 
                                           step="0.01"
                                           required>
                                </div>

                                <div class="col-span-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Subtotal</label>
                                    <div class="subtotal text-lg font-semibold text-gray-900">Rp 0</div>
                                </div>

                                <div class="col-span-2">
                                    <button type="button" class="remove-item-btn w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="border-t pt-4 mb-6">
                    <div class="flex justify-between items-center text-xl font-bold">
                        <span>Total:</span>
                        <span id="grandTotal">Rp 0</span>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let itemIndex = {{ count($order->items) }};

function formatRupiah(number) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
}

function calculateSubtotal(row) {
    const select = row.querySelector('.service-select');
    const qtyInput = row.querySelector('.qty-input');
    const subtotalDiv = row.querySelector('.subtotal');
    
    const selectedOption = select.options[select.selectedIndex];
    const price = parseFloat(selectedOption.dataset.price || 0);
    const qty = parseFloat(qtyInput.value || 0);
    const subtotal = price * qty;
    
    subtotalDiv.textContent = formatRupiah(subtotal);
    
    calculateGrandTotal();
}

function calculateGrandTotal() {
    let total = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const select = row.querySelector('.service-select');
        const qtyInput = row.querySelector('.qty-input');
        const selectedOption = select.options[select.selectedIndex];
        const price = parseFloat(selectedOption.dataset.price || 0);
        const qty = parseFloat(qtyInput.value || 0);
        total += price * qty;
    });
    
    document.getElementById('grandTotal').textContent = formatRupiah(total);
}

function addItemRow() {
    const container = document.getElementById('itemsContainer');
    const newRow = document.createElement('div');
    newRow.className = 'item-row border border-gray-300 rounded-lg p-4';
    newRow.innerHTML = `
        <div class="grid grid-cols-12 gap-4 items-end">
            <div class="col-span-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Layanan</label>
                <select name="items[${itemIndex}][service_id]" class="service-select w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="">Pilih Layanan</option>
                    @foreach($services as $service)
                    <option value="{{ $service->id }}" data-price="{{ $service->price }}">
                        {{ $service->name }} (Rp {{ number_format($service->price, 0, ',', '.') }}/{{ $service->unit }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Qty</label>
                <input type="number" 
                       name="items[${itemIndex}][qty]" 
                       value="1"
                       class="qty-input w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                       min="1" 
                       step="0.01"
                       required>
            </div>

            <div class="col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-2">Subtotal</label>
                <div class="subtotal text-lg font-semibold text-gray-900">Rp 0</div>
            </div>

            <div class="col-span-2">
                <button type="button" class="remove-item-btn w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                    Hapus
                </button>
            </div>
        </div>
    `;
    
    container.appendChild(newRow);
    itemIndex++;
    
    attachRowListeners(newRow);
}

function attachRowListeners(row) {
    const select = row.querySelector('.service-select');
    const qtyInput = row.querySelector('.qty-input');
    const removeBtn = row.querySelector('.remove-item-btn');
    
    select.addEventListener('change', () => calculateSubtotal(row));
    qtyInput.addEventListener('input', () => calculateSubtotal(row));
    
    removeBtn.addEventListener('click', () => {
        if (document.querySelectorAll('.item-row').length > 1) {
            row.remove();
            calculateGrandTotal();
        } else {
            alert('Minimal harus ada satu item');
        }
    });
}

document.getElementById('addItemBtn').addEventListener('click', addItemRow);

document.querySelectorAll('.item-row').forEach(row => {
    attachRowListeners(row);
    calculateSubtotal(row);
});

calculateGrandTotal();
</script>
@endsection
