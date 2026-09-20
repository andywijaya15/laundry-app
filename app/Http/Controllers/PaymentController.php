<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
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
