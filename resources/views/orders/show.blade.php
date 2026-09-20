@extends('layouts.app')
@section('title', 'Detail Order')
@section('breadcrumb', 'Detail Order')

@section('content')
<div class="row">
    <div class="col-12">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('orders.index') }}" class="btn btn-light btn-sm">
                <i class="ph-arrow-left me-1"></i> Kembali
            </a>
            <h4 class="mb-0">Detail Order</h4>
        </div>
    </div>
</div>

@php
    $statusColors = [
        'diterima' => 'bg-info-light text-info',
        'cuci' => 'bg-warning-light text-warning',
        'setrika' => 'bg-orange-light text-orange',
        'siap_diambil' => 'bg-purple-light text-purple',
        'selesai' => 'bg-success-light text-success',
    ];
    $paymentColors = [
        'belum_bayar' => 'bg-danger-light text-danger',
        'lunas' => 'bg-success-light text-success',
    ];
    $nextStatusLabels = [
        'diterima' => 'Proses Cuci',
        'cuci' => 'Proses Setrika',
        'setrika' => 'Siap Diambil',
        'siap_diambil' => 'Selesai',
    ];
@endphp

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Informasi Order</h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3 text-muted">Kode Order</dt>
                    <dd class="col-sm-9 fw-mono">{{ $order->order_code }}</dd>

                    <dt class="col-sm-3 text-muted">Tanggal Masuk</dt>
                    <dd class="col-sm-9">{{ $order->created_at->format('d/m/Y H:i') }}</dd>

                    <dt class="col-sm-3 text-muted">Pelanggan</dt>
                    <dd class="col-sm-9">{{ $order->customer->name }}</dd>

                    <dt class="col-sm-3 text-muted">Telepon</dt>
                    <dd class="col-sm-9">{{ $order->customer->phone }}</dd>

                    <dt class="col-sm-3 text-muted">Alamat</dt>
                    <dd class="col-sm-9">{{ $order->customer->address ?? '-' }}</dd>

                    <dt class="col-sm-3 text-muted">Staff</dt>
                    <dd class="col-sm-9">{{ $order->user->name }}</dd>

                    <dt class="col-sm-3 text-muted">Estimasi Selesai</dt>
                    <dd class="col-sm-9">{{ $order->estimated_done ? $order->estimated_done->format('d/m/Y') : '-' }}</dd>

                    @if ($order->finished_at)
                        <dt class="col-sm-3 text-muted">Tanggal Selesai</dt>
                        <dd class="col-sm-9">{{ $order->finished_at->format('d/m/Y H:i') }}</dd>
                    @endif
                </dl>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Item Pesanan</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Layanan</th>
                                <th>Harga/Satuan</th>
                                <th>Qty</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($order->items as $item)
                                <tr>
                                    <td>{{ $item->service->name }}</td>
                                    <td class="text-muted">Rp {{ number_format($item->price, 0, ',', '.') }} / {{ $item->service->unit }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td class="text-end fw-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada item.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="fw-medium text-end">Total</td>
                                <td class="text-end fw-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Status</h5>
            </div>
            <div class="card-body">
                <span class="badge rounded-pill fs-6 px-3 py-2 {{ $statusColors[$order->status] ?? 'bg-secondary' }}">
                    {{ $order->getStatusLabel() }}
                </span>

                @if ($order->getNextStatus())
                    <form method="POST" action="{{ route('orders.updateStatus', $order) }}" class="mt-3">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $order->getNextStatus() }}">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ph-arrow-right me-1"></i> {{ $nextStatusLabels[$order->getNextStatus()] ?? ucfirst(str_replace('_', ' ', $order->getNextStatus())) }}
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Pembayaran</h5>
            </div>
            <div class="card-body">
                <span class="badge rounded-pill fs-6 px-3 py-2 {{ $paymentColors[$order->payment_status] ?? 'bg-secondary' }}">
                    {{ $order->getPaymentStatusLabel() }}
                </span>

                @if ($order->payment_status === 'belum_bayar')
                    <div class="mt-3 d-grid">
                        <a href="{{ route('orders.payment.create', $order) }}" class="btn btn-danger">
                            <i class="ph-cash me-1"></i> Bayar
                        </a>
                    </div>
                @elseif ($order->payment)
                    <div class="mt-3 small">
                        <div class="row">
                            <div class="col-6 text-muted">Jumlah</div>
                            <div class="col-6 text-end fw-medium">Rp {{ number_format($order->payment->amount, 0, ',', '.') }}</div>
                        </div>
                        <div class="row">
                            <div class="col-6 text-muted">Metode</div>
                            <div class="col-6 text-end">{{ ucfirst(str_replace('_', ' ', $order->payment->method)) }}</div>
                        </div>
                        <div class="row">
                            <div class="col-6 text-muted">Tanggal</div>
                            <div class="col-6 text-end">{{ $order->payment->paid_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Aksi</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if ($order->isEditable())
                        <a href="{{ route('orders.editItems', $order) }}" class="btn btn-outline-secondary">
                            <i class="ph-pencil me-1"></i> Edit Item
                        </a>
                    @endif

                    <a href="{{ $order->getNotaUrl() }}" target="_blank" class="btn btn-outline-primary">
                        <i class="ph-printer me-1"></i> Cetak Nota
                    </a>

                    <a href="{{ route('orders.show', $order) }}" target="_blank" class="btn btn-outline-info">
                        <i class="ph-share-network me-1"></i> Bagikan Nota
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection