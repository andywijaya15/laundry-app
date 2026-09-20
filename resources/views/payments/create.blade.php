@extends('layouts.app')
@section('title', 'Pembayaran')
@section('breadcrumb', 'Pembayaran')

@section('content')
<div class="row">
    <div class="col-12 col-lg-6 mx-auto">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pembayaran Order {{ $order->order_code }}</h5>
                <a href="{{ route('orders.show', $order) }}" class="btn btn-light btn-sm">
                    <i class="ph-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card-body">
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Pelanggan:</span>
                        <span class="fw-medium">{{ $order->customer->name }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Status Order:</span>
                        <span class="badge bg-{{ $order->status === 'diterima' ? 'info' : ($order->status === 'cuci' ? 'warning' : ($order->status === 'setrika' ? 'orange' : ($order->status === 'siap_diambil' ? 'purple' : 'success'))) }} rounded-pill">
                            {{ $order->getStatusLabel() }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Total Order:</span>
                        <span class="fs-5 fw-bold text-dark">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('orders.payment.store', $order) }}">
                    @csrf

                    <div class="mb-3">
                        <label for="amount" class="form-label">Jumlah Bayar <span class="text-danger">*</span></label>
                        <input
                            type="number"
                            name="amount"
                            id="amount"
                            required
                            min="1"
                            max="{{ $order->total_price }}"
                            value="{{ old('amount', $order->total_price) }}"
                            placeholder="Jumlah bayar"
                            class="form-control @error('amount') is-invalid @enderror">
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-check form-check-inline w-100">
                                    <input
                                        type="radio"
                                        name="method"
                                        value="cash"
                                        {{ old('method', 'cash') === 'cash' ? 'checked' : '' }}
                                        required
                                        class="form-check-input">
                                    <span class="form-check-label">
                                        <i class="ph-coins me-1"></i> Tunai
                                    </span>
                                </label>
                            </div>
                            <div class="col-6">
                                <label class="form-check form-check-inline w-100">
                                    <input
                                        type="radio"
                                        name="method"
                                        value="transfer"
                                        {{ old('method') === 'transfer' ? 'checked' : '' }}
                                        required
                                        class="form-check-input">
                                    <span class="form-check-label">
                                        <i class="ph-bank me-1"></i> Transfer
                                    </span>
                                </label>
                            </div>
                        </div>
                        @error('method')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="ph-cash me-1"></i> Bayar Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection