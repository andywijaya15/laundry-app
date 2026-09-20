@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Pembayaran Order {{ $order->order_code }}</h1>
        <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">← Kembali</a>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <div class="mb-6 space-y-2">
            <div class="flex justify-between">
                <span class="text-sm text-gray-500">Pelanggan:</span>
                <span class="text-sm font-medium text-gray-900">{{ $order->customer->name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm text-gray-500">Total Order:</span>
                <span class="text-lg font-bold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
        </div>

        <form method="POST" action="{{ route('orders.payment.store', $order) }}" class="space-y-6">
            @csrf

            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Bayar</label>
                <input 
                    type="number" 
                    name="amount" 
                    id="amount" 
                    required 
                    min="1" 
                    max="{{ $order->total_price }}"
                    value="{{ old('amount', $order->total_price) }}"
                    placeholder="Jumlah bayar"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('amount') border-red-500 @enderror"
                >
                @error('amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input 
                            type="radio" 
                            name="method" 
                            value="cash" 
                            {{ old('method', 'cash') === 'cash' ? 'checked' : '' }}
                            required
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                        >
                        <span class="ml-2 text-sm text-gray-900">Tunai</span>
                    </label>
                    <label class="flex items-center">
                        <input 
                            type="radio" 
                            name="method" 
                            value="transfer"
                            {{ old('method') === 'transfer' ? 'checked' : '' }}
                            required
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                        >
                        <span class="ml-2 text-sm text-gray-900">Transfer</span>
                    </label>
                </div>
                @error('method')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Bayar Sekarang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
