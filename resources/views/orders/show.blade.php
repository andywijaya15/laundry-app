@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('orders.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">&larr; Kembali</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Detail Order</h1>
    </div>

    @if (session('success'))
        <div class="mb-4 bg-green-50 text-green-700 px-4 py-3 rounded-md">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="mb-4 bg-red-50 text-red-700 px-4 py-3 rounded-md">{{ session('error') }}</div>
    @endif

    @php
        $statusColors = [
            'diterima' => 'bg-blue-100 text-blue-800',
            'cuci' => 'bg-yellow-100 text-yellow-800',
            'setrika' => 'bg-orange-100 text-orange-800',
            'siap_diambil' => 'bg-purple-100 text-purple-800',
            'selesai' => 'bg-green-100 text-green-800',
        ];
        $paymentColors = [
            'belum_bayar' => 'bg-red-100 text-red-800',
            'lunas' => 'bg-green-100 text-green-800',
        ];
        $nextStatusLabels = [
            'diterima' => 'Proses Cuci',
            'cuci' => 'Proses Setrika',
            'setrika' => 'Siap Diambil',
            'siap_diambil' => 'Selesai',
        ];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Order Info --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Informasi Order</h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500">Kode Order</dt>
                        <dd class="font-mono font-medium text-gray-900">{{ $order->order_code }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Tanggal Masuk</dt>
                        <dd class="text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Pelanggan</dt>
                        <dd class="text-gray-900">{{ $order->customer->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Telepon</dt>
                        <dd class="text-gray-900">{{ $order->customer->phone }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-gray-500">Alamat</dt>
                        <dd class="text-gray-900">{{ $order->customer->address ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Staff</dt>
                        <dd class="text-gray-900">{{ $order->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Estimasi Selesai</dt>
                        <dd class="text-gray-900">{{ $order->estimated_done ? $order->estimated_done->format('d/m/Y') : '-' }}</dd>
                    </div>
                    @if ($order->finished_at)
                        <div>
                            <dt class="text-gray-500">Tanggal Selesai</dt>
                            <dd class="text-gray-900">{{ $order->finished_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Items Table --}}
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Item Pesanan</h2>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Layanan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga/Satuan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($order->items as $item)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $item->service->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    Rp {{ number_format($item->price, 0, ',', '.') }} / {{ $item->service->unit }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $item->qty }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 text-right">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">Belum ada item.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-sm font-medium text-gray-900 text-right">Total</td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900 text-right">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Status Card --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 uppercase mb-3">Status</h3>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$order->status] ?? '' }}">
                    {{ $order->getStatusLabel() }}
                </span>

                @if ($order->getNextStatus())
                    <form method="POST" action="{{ route('orders.updateStatus', $order) }}" class="mt-4">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $order->getNextStatus() }}">
                        <button type="submit"
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition-colors">
                            {{ $nextStatusLabels[$order->getNextStatus()] ?? ucfirst(str_replace('_', ' ', $order->getNextStatus())) }}
                        </button>
                    </form>
                @endif
            </div>

            {{-- Payment Card --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 uppercase mb-3">Pembayaran</h3>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $paymentColors[$order->payment_status] ?? '' }}">
                    {{ $order->getPaymentStatusLabel() }}
                </span>

                @if ($order->payment_status === 'belum_bayar')
                    <div class="mt-4">
                        <a href="{{ route('orders.payment.create', $order) }}"
                           class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition-colors">
                            Bayar
                        </a>
                    </div>
                @elseif ($order->payment)
                    <div class="mt-4 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Jumlah</span>
                            <span class="text-gray-900 font-medium">Rp {{ number_format($order->payment->amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Metode</span>
                            <span class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $order->payment->method)) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Tanggal</span>
                            <span class="text-gray-900">{{ $order->payment->paid_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="bg-white shadow rounded-lg p-6 space-y-3">
                <h3 class="text-sm font-medium text-gray-500 uppercase mb-3">Aksi</h3>

                @if ($order->isEditable())
                    <a href="{{ route('orders.editItems', $order) }}"
                       class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50 transition-colors">
                        Edit Item
                    </a>
                @endif

                <a href="{{ $order->getNotaUrl() }}"
                   target="_blank"
                   class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50 transition-colors">
                    Cetak Nota
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
