<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'user'])
            ->forRole();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_code', 'like', '%'.$request->search.'%')
                    ->orWhereHas('customer', function ($q2) use ($request) {
                        $q2->where('name', 'like', '%'.$request->search.'%');
                    });
            });
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        $orders->getCollection()->transform(function ($order) {
            $order->nota_url = URL::temporarySignedRoute(
                'orders.nota',
                now()->addDays(7),
                ['order' => $order->id]
            );
            return $order;
        });

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $services = Service::active()->orderBy('name')->get();
        $customers = Customer::orderBy('name')->get();

        return view('orders.create', compact('services', 'customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.qty' => 'required|numeric|min:0.01|max:999.99',
            'estimated_done' => 'nullable|date|after_or_equal:today',
        ]);

        $order = DB::transaction(function () use ($validated) {
            $order = Order::create([
                'order_code' => Order::generateOrderCode(),
                'outlet_id' => 1,
                'customer_id' => $validated['customer_id'],
                'user_id' => auth()->id(),
                'status' => 'diterima',
                'payment_status' => 'belum_bayar',
                'total_price' => 0,
                'estimated_done' => $validated['estimated_done'] ?? null,
            ]);

            $totalPrice = 0;

            foreach ($validated['items'] as $item) {
                $service = Service::find($item['service_id']);
                $subtotal = OrderItem::calculateSubtotal($item['qty'], $service->price);

                OrderItem::create([
                    'order_id' => $order->id,
                    'service_id' => $item['service_id'],
                    'qty' => $item['qty'],
                    'price' => $service->price,
                    'subtotal' => $subtotal,
                ]);

                $totalPrice += $subtotal;
            }

            $order->update(['total_price' => $totalPrice]);

            return $order;
        });

        return redirect()->route('orders.show', $order)
            ->with('success', "Order {$order->order_code} berhasil dibuat.");
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'user', 'items.service', 'payment']);

        $nextStatus = $order->getNextStatus();

        return view('orders.show', compact('order', 'nextStatus'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:'.implode(',', Order::WORKFLOW),
        ]);

        $nextStatus = $order->getNextStatus();

        if (! $nextStatus || $request->status !== $nextStatus) {
            return back()->withErrors([
                'status' => "Transisi status tidak valid. Status berikutnya: {$nextStatus}",
            ]);
        }

        $updates = ['status' => $request->status];

        if ($request->status === 'selesai' && $order->payment_status === 'lunas') {
            $updates['finished_at'] = now();
        }

        $order->update($updates);

        return back()->with('success', "Status berhasil diupdate ke {$order->getStatusLabel()}.");
    }

    public function editItems(Order $order)
    {
        if (! $order->isEditable()) {
            abort(403, 'Order hanya bisa diedit saat status Diterima.');
        }

        $order->load('items.service');
        $services = Service::active()->orderBy('name')->get();

        return view('orders.edit-items', compact('order', 'services'));
    }

    public function updateItems(Request $request, Order $order)
    {
        if (! $order->isEditable()) {
            abort(403, 'Order hanya bisa diedit saat status Diterima.');
        }

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.qty' => 'required|numeric|min:0.01|max:999.99',
        ]);

        DB::transaction(function () use ($order, $validated) {
            $order->items()->delete();

            $totalPrice = 0;

            foreach ($validated['items'] as $item) {
                $service = Service::find($item['service_id']);
                $subtotal = OrderItem::calculateSubtotal($item['qty'], $service->price);

                OrderItem::create([
                    'order_id' => $order->id,
                    'service_id' => $item['service_id'],
                    'qty' => $item['qty'],
                    'price' => $service->price,
                    'subtotal' => $subtotal,
                ]);

                $totalPrice += $subtotal;
            }

            $order->update(['total_price' => $totalPrice]);
        });

        return redirect()->route('orders.show', $order)
            ->with('success', 'Item order berhasil diperbarui.');
    }

    public function nota(Request $request, Order $order)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'Link tidak valid atau sudah kedaluwarsa.');
        }

        $order->load(['customer', 'user', 'items.service', 'payment']);

        return view('orders.nota', compact('order'));
    }
}
