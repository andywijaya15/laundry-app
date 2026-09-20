@extends('layouts.app')
@section('title', 'Edit Item')
@section('breadcrumb', 'Edit Item')

@section('content')
<div class="row">
    <div class="col-12 col-lg-10 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('orders.show', $order) }}" class="btn btn-light btn-sm">
                <i class="ph-arrow-left me-1"></i> Kembali
            </a>
            <h4 class="mb-0">Edit Item - {{ $order->order_code }}</h4>
        </div>

        <form action="{{ route('orders.updateItems', $order) }}" method="POST" id="editItemsForm">
            @csrf
            @method('PUT')

            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Item Layanan</h5>
                    <button type="button" id="addItemBtn" class="btn btn-success btn-sm">
                        <i class="ph-plus me-1"></i> Tambah Item
                    </button>
                </div>
                <div class="card-body">
                    <div id="itemsContainer" class="space-y-3">
                        @foreach($order->items as $index => $item)
                            <div class="item-row card mb-3">
                                <div class="card-body">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-5">
                                            <label class="form-label">Layanan</label>
                                            <select name="items[{{ $index }}][service_id]" class="form-select service-select" required>
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

                                        <div class="col-md-2">
                                            <label class="form-label">Qty</label>
                                            <input type="number"
                                                   name="items[{{ $index }}][qty]"
                                                   value="{{ $item->qty }}"
                                                   class="form-control qty-input @error('items.*.qty') is-invalid @enderror"
                                                   min="1"
                                                   step="0.01"
                                                   required>
                                        @error('items.*.qty')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Subtotal</label>
                                        <div class="form-control-plaintext subtotal fw-bold">Rp 0</div>
                                    </div>

                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-outline-danger remove-item-btn w-100">
                                            <i class="ph-trash me-1"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <span class="fs-4 fw-bold">Total:</span>
                        <span id="grandTotal" class="fs-3 fw-bold text-primary">Rp 0</span>
                    </div>
                </div>

                <div class="d-flex justify-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="ph-floppy-disk me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
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
    newRow.className = 'item-row card mb-3';
    newRow.innerHTML = `
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label">Layanan</label>
                    <select name="items[${itemIndex}][service_id]" class="form-select service-select" required>
                        <option value="">Pilih Layanan</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" data-price="{{ $service->price }}">
                                {{ $service->name }} (Rp {{ number_format($service->price, 0, ',', '.') }}/{{ $service->unit }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Qty</label>
                    <input type="number"
                           name="items[${itemIndex}][qty]"
                           value="1"
                           class="form-control qty-input"
                           min="1"
                           step="0.01"
                           required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Subtotal</label>
                    <div class="form-control-plaintext subtotal fw-bold">Rp 0</div>
                </div>

                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger remove-item-btn w-100">
                        <i class="ph-trash me-1"></i> Hapus
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
@endpush