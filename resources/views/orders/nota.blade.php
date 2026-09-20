<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota - {{ $order->order_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                background: white;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-gray-50 p-4">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-6">
        <div class="text-center border-b pb-4 mb-4">
            <h1 class="text-2xl font-bold text-gray-900">Laundry</h1>
            <p class="text-sm text-gray-500 mt-1">Nota Pembayaran</p>
        </div>

        <div class="space-y-3 mb-4 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Kode Order:</span>
                <span class="font-mono font-medium">{{ $order->order_code }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Tanggal:</span>
                <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Pelanggan:</span>
                <span class="font-medium">{{ $order->customer->name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">No. HP:</span>
                <span>{{ $order->customer->phone }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Status:</span>
                <span class="font-medium">
                    @switch($order->status)
                        @case('diterima') Diterima @break
                        @case('cuci') Cuci @break
                        @case('setrika') Setrika @break
                        @case('siap_diambil') Siap Diambil @break
                        @case('selesai') Selesai @break
                    @endswitch
                </span>
            </div>
        </div>

        <div class="border-t border-b py-4 mb-4">
            <h2 class="font-semibold text-gray-900 mb-3">Item</h2>
            <div class="space-y-2 text-sm">
                @foreach($order->items as $item)
                    <div class="flex justify-between">
                        <div class="flex-1">
                            <div class="font-medium">{{ $item->service->name }}</div>
                            <div class="text-gray-500">{{ $item->qty }} {{ $item->service->unit }} × Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                        </div>
                        <div class="font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="space-y-2 mb-6">
            <div class="flex justify-between text-lg font-bold">
                <span>Total</span>
                <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
            
            @if($order->payment)
                <div class="text-sm text-gray-600 space-y-1">
                    <div class="flex justify-between">
                        <span>Metode:</span>
                        <span>{{ $order->payment->method === 'cash' ? 'Tunai' : 'Transfer' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Dibayar:</span>
                        <span>Rp {{ number_format($order->payment->amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Tanggal Bayar:</span>
                        <span>{{ $order->payment->paid_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
                <div class="inline-block px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                    LUNAS
                </div>
            @else
                <div class="inline-block px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">
                    BELUM BAYAR
                </div>
            @endif
        </div>

        <div class="text-center text-xs text-gray-500 mb-4">
            Terima kasih atas kepercayaan Anda
        </div>

        <a 
            href="https://wa.me/?text={{ urlencode("*NOTA LAUNDRY*\n\nKode: {$order->order_code}\nPelanggan: {$order->customer->name}\nTotal: Rp " . number_format($order->total_price, 0, ',', '.') . "\nStatus: " . ($order->payment_status === 'lunas' ? 'LUNAS' : 'BELUM BAYAR')) }}"
            target="_blank"
            class="no-print block w-full bg-green-600 text-white text-center py-3 px-4 rounded-md hover:bg-green-700 font-medium"
        >
            Bagikan via WhatsApp
        </a>
    </div>
</body>
</html>
