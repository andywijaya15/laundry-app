@extends('layouts.app')
@section('title', 'Riwayat Transaksi')
@section('breadcrumb', 'Riwayat Transaksi')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('reports.transactions') }}" class="row g-3">
                    <div class="col-12 col-md-2">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                               class="form-control">
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                               class="form-control">
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label">Status Order</label>
                        <select name="status" class="form-select">
                            <option value="">Semua</option>
                            @foreach(\App\Models\Order::WORKFLOW as $status)
                                <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ \App\Models\Order::WORKFLOW[$status] ?? ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label">Status Bayar</label>
                        <select name="payment_status" class="form-select">
                            <option value="">Semua</option>
                            <option value="belum_bayar" {{ request('payment_status') === 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                            <option value="lunas" {{ request('payment_status') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                        <a href="{{ route('reports.transactions') }}" class="btn btn-light w-100">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal Bayar</th>
                                <th>Kode Order</th>
                                <th>Pelanggan</th>
                                <th>Status</th>
                                <th>Metode</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $payment)
                                <tr>
                                    <td>{{ $payment->paid_at->format('d/m/Y H:i') }}</td>
                                    <td class="fw-mono">{{ $payment->order->order_code }}</td>
                                    <td>{{ $payment->order->customer->name }}</td>
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
                                        <span class="badge rounded-pill {{ $statusColors[$payment->order->status] ?? '' }}">
                                            {{ $payment->order->getStatusLabel() }}
                                        </span>
                                    </td>
                                    <td class="text-muted">{{ $payment->method === 'cash' ? 'Tunai' : 'Transfer' }}</td>
                                    <td class="fw-medium">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Tidak ada transaksi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection