@extends('layouts.app')
@section('title', 'Pembayaran')
@section('breadcrumb', 'Pembayaran')

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

        <div class="card mb-3">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('payments.index') }}" class="row g-3 align-items-end">
                    <div class="col-12 col-md-3">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode order atau nama..."
                               class="form-control form-control-sm">
                    </div>
                    <div class="col-12 col-md-2">
                        <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="Dari"
                               class="form-control form-control-sm">
                    </div>
                    <div class="col-12 col-md-2">
                        <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="Sampai"
                               class="form-control form-control-sm">
                    </div>
                    <div class="col-12 col-md-1">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ph-magnifying-glass me-1"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h5 class="mb-0">Daftar Order Belum Lunas</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Status Order</th>
                                <th>Tanggal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td class="fw-mono">{{ $order->order_code }}</td>
                                    <td>{{ $order->customer->name }}</td>
                                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'diterima' => 'bg-info-light text-info',
                                                'cuci' => 'bg-warning-light text-warning',
                                                'setrika' => 'bg-orange-light text-orange',
                                                'siap_diambil' => 'bg-purple-light text-purple',
                                                'selesai' => 'bg-success-light text-success',
                                            ];
                                        @endphp
                                        <span class="badge rounded-pill {{ $statusColors[$order->status] ?? 'bg-secondary' }}">
                                            {{ $order->getStatusLabel() }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('orders.payment.create', $order) }}" class="btn btn-sm btn-primary">
                                            <i class="ph-coins me-1"></i> Bayar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="ph-check-circle ph-lg d-block mb-2 text-success"></i>
                                        Semua order sudah lunas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($orders->hasPages())
                    <div class="card-footer">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection