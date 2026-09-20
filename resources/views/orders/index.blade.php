@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Order</h1>
        <a href="{{ route('orders.create') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors">
            + Order Baru
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 bg-green-50 text-green-700 px-4 py-3 rounded-md">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="mb-4 bg-red-50 text-red-700 px-4 py-3 rounded-md">{{ session('error') }}</div>
    @endif

    <div class="mb-4 bg-white shadow rounded-lg p-4">
        <form method="GET" action="{{ route('orders.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode order atau nama..."
                   class="px-3 py-2 border border-gray-300 rounded-md text-sm">
            <select name="status" class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                <option value="">Semua Status</option>
                @foreach(\App\Models\Order::WORKFLOW as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ \App\Models\Order::WORKFLOW[$status] ?? ucfirst($status) }}</option>
                @endforeach
            </select>
            <select name="payment_status" class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                <option value="">Semua Pembayaran</option>
                <option value="belum_bayar" {{ request('payment_status') === 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                <option value="lunas" {{ request('payment_status') === 'lunas' ? 'selected' : '' }}>Lunas</option>
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="Dari"
                   class="px-3 py-2 border border-gray-300 rounded-md text-sm">
            <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="Sampai"
                   class="px-3 py-2 border border-gray-300 rounded-md text-sm">
        </form>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bayar</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($orders as $order)
                    <tr>
                        <td class="px-6 py-4 text-sm font-mono text-gray-900">{{ $order->order_code }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $order->customer->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-sm">
                            @php
                                $statusColors = [
                                    'diterima' => 'bg-blue-100 text-blue-800',
                                    'cuci' => 'bg-yellow-100 text-yellow-800',
                                    'setrika' => 'bg-orange-100 text-orange-800',
                                    'siap_diambil' => 'bg-purple-100 text-purple-800',
                                    'selesai' => 'bg-green-100 text-green-800',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? '' }}">
                                {{ $order->getStatusLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @php
                                $paymentColors = [
                                    'belum_bayar' => 'bg-red-100 text-red-800',
                                    'lunas' => 'bg-green-100 text-green-800',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $paymentColors[$order->payment_status] ?? '' }}">
                                {{ $order->getPaymentStatusLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm">
                            <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">Belum ada order.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $orders->links() }}
    </div>
</div>
@endsection