@extends('layouts.app')
@section('title', 'Order Baru')
@section('breadcrumb', 'Order Baru')

@section('content')
<div class="row">
    <div class="col-12 col-lg-10 mx-auto">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <ul class="list-unstyled mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('orders.store') }}">
            @csrf

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Pelanggan</h5>
                </div>
                <div class="card-body row g-3">
                    <div class="col-12 col-md-6">
                        <label for="customer_id" class="form-label">Pelanggan *</label>
                        <select id="customer_id" name="customer_id" required class="form-select">
                            <option value="">Pilih pelanggan</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} ({{ $customer->phone }})
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="estimated_done" class="form-label">Estimasi Selesai</label>
                        <input type="date" id="estimated_done" name="estimated_done" value="{{ old('estimated_done') }}"
                               class="form-control">
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Item Order</h5>
                    <button type="button" id="add-item" class="btn btn-outline-primary btn-sm">
                        <i class="ph-plus me-1"></i> Tambah Item
                    </button>
                </div>
                <div class="card-body">
                    <div id="items-container" class="space-y-3">
                        <div class="item-row row g-3 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label">Layanan *</label>
                                <select name="items[0][service_id]" required class="form-select service-select">
                                    <option value="">Pilih layanan</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" data-price="{{ $service->price }}" data-unit="{{ $service->unit }}">
                                            {{ $service->name }} ({{ $service->unit === 'kg' ? 'Rp '.number_format($service->price,0,',','.') : 'Rp '.number_format($service->price,0,',','.'/item) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('items.0.service_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kuantitas *</label>
                                <input type="number" name="items[0][qty]" step="0.01" min="0.01" max="999.99" required
                                       class="form-control qty-input @error('items.0.qty') is-invalid @enderror">
                                @error('items.0.qty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Subtotal</label>
                                <div class="form-control-plaintext subtotal fw-medium">Rp 0</div>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-outline-danger remove-item" style="padding: 0.375rem;">
                                    <i class="ph-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body d-flex justify-content-end align-items-center">
                    <span class="fs-5 fw-medium me-3">Total</span>
                    <span id="total-price" class="fs-4 fw-bold text-primary">Rp 0</span>
                </div>
            </div>

            <div class="d-flex justify-end gap-2">
                <a href="{{ route('orders.index') }}" class="btn btn-light">
                    <i class="ph-x me-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="ph-floppy-disk me-1"></i> Simpan Order
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
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
@endpush