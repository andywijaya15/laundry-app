@extends('layouts.app')
@section('title', 'Order')
@section('breadcrumb', 'Order')

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
                <form method="GET" action="{{ route('orders.index') }}" class="row g-3">
                    <div class="col-12 col-sm-6 col-md-3">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode order atau nama..."
                               class="form-control form-control-sm">
                    </div>
                    <div class="col-12 col-sm-4 col-md-2">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Semua Status</option>
                            @foreach(\App\Models\Order::WORKFLOW as $status)
                                <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ \App\Models\Order::WORKFLOW[$status] ?? ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-sm-4 col-md-2">
                        <select name="payment_status" class="form-select form-select-sm">
                            <option value="">Semua Pembayaran</option>
                            <option value="belum_bayar" {{ request('payment_status') === 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                            <option value="lunas" {{ request('payment_status') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-3 col-md-2">
                        <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="Dari"
                               class="form-control form-control-sm">
                    </div>
                    <div class="col-12 col-sm-3 col-md-2">
                        <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="Sampai"
                               class="form-control form-control-sm">
                    </div>
                    <div class="col-12 col-sm-2 col-md-1">
                        <button type="submit" class="btn btn-primary w-100 d-flex justify-content-center">
                            <i class="ph-magnifying-glass me-1 d-none d-sm-inline"></i>
                            <span class="d-sm-none d-md-inline">Filter</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h5 class="mb-0">Daftar Order</h5>
                <a href="{{ route('orders.create') }}" class="btn btn-primary btn-sm ms-auto">
                    <i class="ph-plus me-1"></i> Order Baru
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Bayar</th>
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
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-light" title="Lihat Detail">
                                                <i class="ph-eye"></i>
                                            </a>
                                            <a href="https://wa.me/?text={{ urlencode('🧺 *NOTA LAUNDRY*%0A%0AKode: ' . $order->order_code . '%0APelanggan: ' . $order->customer->name . '%0ATotal: Rp ' . number_format($order->total_price, 0, ',', '.') . '%0AStatus: ' . $order->getPaymentStatusLabel() . '%0A%0ADetail: ' . $order->nota_url) }}" target="_blank" class="btn btn-sm btn-success" title="Bagikan Nota via WhatsApp">
                                                <i class="ph-whatsapp-logo"></i>
                                            </a>
                                        </div>
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