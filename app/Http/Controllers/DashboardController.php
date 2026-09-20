<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isOwner = $user->isOwner();

        $baseOrderQuery = Order::query();
        $basePaymentQuery = Payment::query();

        if (! $isOwner) {
            $baseOrderQuery->where('user_id', $user->id);
            $basePaymentQuery->whereHas('order', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $todayRevenue = $basePaymentQuery
            ->whereDate('paid_at', today())
            ->sum('amount');

        $activeOrders = $baseOrderQuery
            ->whereIn('status', ['diterima', 'cuci', 'setrika'])
            ->count();

        $readyOrders = $baseOrderQuery
            ->where('status', 'siap_diambil')
            ->count();

        $recentOrders = $baseOrderQuery
            ->with(['customer', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'todayRevenue',
            'activeOrders',
            'readyOrders',
            'recentOrders',
            'isOwner'
        ));
    }
}
