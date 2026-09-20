@extends('layouts.app')
@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')
@section('content')
<div class="row">
    <div class="col-12 col-md-4">
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="w-40px h-40px rounded-circle bg-primary-light d-flex align-items-center justify-content-center me-3">
                        <i class="ph-coins text-primary fs-3"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-1">Omzet Hari Ini</p>
                        <h4 class="mb-0">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="w-40px h-40px rounded-circle bg-info-light d-flex align-items-center justify-content-center me-3">
                        <i class="ph-list-checks text-info fs-3"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-1">Order Aktif</p>
                        <h4 class="mb-0">{{ $activeOrders }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="w-40px h-40px rounded-circle bg-purple-light d-flex align-items-center justify-content-center me-3">
                        <i class="ph-package text-purple fs-3"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-1">Siap Diambil</p>
                        <h4 class="mb-0">{{ $readyOrders }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h5 class="mb-0">Order Terbaru</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Pelanggan</th>
                                <th>Status</th>
                                <th>Bayar</th>
                                <th>Tanggal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentOrders as $order)
                                <tr>
                                    <td class="fw-mono">{{ $order->order_code }}</td>
                                    <td>{{ $order->customer->name }}</td>
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
                                    <td>
                                        @php
                                            $paymentColors = [
                                                'belum_bayar' => 'bg-danger-light text-danger',
                                                'lunas' => 'bg-success-light text-success',
                                            ];
                                        @endphp
                                        <span class="badge rounded-pill {{ $paymentColors[$order->payment_status] ?? 'bg-secondary' }}">
                                            {{ $order->getPaymentStatusLabel() }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-light">
                                            <i class="ph-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="ph-inbox ph-lg d-block mb-2"></i>
                                        Belum ada order.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($recentOrders->hasPages())
                    <div class="card-footer">
                        {{ $recentOrders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection