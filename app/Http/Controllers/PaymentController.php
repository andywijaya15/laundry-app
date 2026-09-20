<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'user'])
            ->forRole()
            ->where('payment_status', 'belum_bayar')
            ->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_code', 'like', '%'.$request->search.'%')
                    ->orWhereHas('customer', fn($q2) => $q2->where('name', 'like', '%'.$request->search.'%'));
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(15)->withQueryString();

        $orders->getCollection()->transform(function ($order) {
            $order->nota_url = URL::temporarySignedRoute(
                'orders.nota',
                now()->addDays(7),
                ['order' => $order->id]
            );
            $order->whatsapp_share_url = $this->generateWhatsAppShareUrl($order);
            return $order;
        });

        return view('payments.index', compact('orders'));
    }

    protected function generateWhatsAppShareUrl(Order $order): string
    {
        $message = "🧺 *NOTA LAUNDRY*\n\n"
            . "Kode: {$order->order_code}\n"
            . "Pelanggan: {$order->customer->name}\n"
            . "Total: Rp " . number_format($order->total_price, 0, ',', '.') . "\n"
            . "Status: {$order->getPaymentStatusLabel()}\n\n"
            . "Detail: {$order->nota_url}";

        return 'https://wa.me/?text=' . urlencode($message);
    }

    public function create(Order $order)
    {
        $order->load(['customer', 'items.service', 'payment']);

        if ($order->payment) {
            return back()->with('error', 'Order ini sudah dibayar.');
        }

        return view('payments.create', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        if ($order->payment) {
            return back()->with('error', 'Order ini sudah dibayar.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1|max:'.$order->total_price,
            'method' => 'required|in:cash,transfer',
        ]);

        $payment = Payment::create([
            'order_id' => $order->id,
            'amount' => $validated['amount'],
            'method' => $validated['method'],
            'paid_at' => now(),
        ]);

        $order->update(['payment_status' => 'lunas']);

        if ($order->status === 'selesai') {
            $order->update(['finished_at' => now()]);
        }

        return redirect()->route('orders.show', $order)
            ->with('success', 'Pembayaran berhasil dicatat.');
    }
}
