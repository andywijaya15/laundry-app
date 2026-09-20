<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota - {{ $order->order_code }}</title>
    <link href="{{ asset('assets/fonts/inter/inter.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/icons/phosphor/styles.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/ltr/all.min.css') }}" rel="stylesheet">
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
<body class="bg-light p-4">
    <div class="no-print mb-3 text-center">
        <a
            href="https://wa.me/?text={{ urlencode(url()->full()) }}"
            target="_blank"
            class="btn btn-success w-100 fw-medium py-3"
        >
            <i class="ph-whatsapp-logo me-1"></i> Bagikan via WhatsApp
        </a>
    </div>

    <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-5">
        <div class="text-center border-bottom pb-4 mb-4">
            <h1 class="h4 fw-bold text-dark">Laundry</h1>
            <p class="text-sm text-muted mt-1">Nota Pembayaran</p>
        </div>

        <div class="space-y-3 mb-4 small">
            <div class="d-flex justify-content-between">
                <span class="text-muted">Kode Order:</span>
                <span class="fw-mono fw-medium">{{ $order->order_code }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-muted">Tanggal:</span>
                <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-muted">Pelanggan:</span>
                <span class="fw-medium">{{ $order->customer->name }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-muted">No. HP:</span>
                <span>{{ $order->customer->phone }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-muted">Status:</span>
                <span class="fw-medium">
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

        <div class="border-top border-bottom py-4 mb-4">
            <h5 class="fw-semibold text-dark mb-3">Item</h5>
            <div class="space-y-2 small">
                @foreach($order->items as $item)
                    <div class="d-flex justify-content-between">
                        <div class="flex-grow-1">
                            <div class="fw-medium">{{ $item->service->name }}</div>
                            <div class="text-muted">{{ $item->qty }} {{ $item->service->unit }} × Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                        </div>
                        <div class="fw-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="space-y-2 mb-5">
            <div class="d-flex justify-content-between fs-5 fw-bold">
                <span>Total</span>
                <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>

            @if($order->payment)
                <div class="small text-muted space-y-1">
                    <div class="d-flex justify-content-between">
                        <span>Metode:</span>
                        <span>{{ $order->payment->method === 'cash' ? 'Tunai' : 'Transfer' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Dibayar:</span>
                        <span>Rp {{ number_format($order->payment->amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Tanggal Bayar:</span>
                        <span>{{ $order->payment->paid_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
                <div class="d-inline-block px-3 py-1 bg-success bg-opacity-10 text-success small fw-semibold rounded-pill">
                    LUNAS
                </div>
            @else
                <div class="d-inline-block px-3 py-1 bg-danger bg-opacity-10 text-danger small fw-semibold rounded-pill">
                    BELUM BAYAR
                </div>
            @endif
        </div>

        <div class="text-center small text-muted mb-4">
            Terima kasih atas kepercayaan Anda
        </div>
    </div>
</body>
</html>