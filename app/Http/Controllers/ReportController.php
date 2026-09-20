<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function daily(Request $request)
    {
        $dateFrom = $request->date_from ? Carbon::parse($request->date_from) : Carbon::today()->subDays(30);
        $dateTo = $request->date_to ? Carbon::parse($request->date_to) : Carbon::today();

        $payments = Payment::whereHas('order')
            ->whereDate('paid_at', '>=', $dateFrom)
            ->whereDate('paid_at', '<=', $dateTo)
            ->with('order')
            ->latest('paid_at')
            ->paginate(20)
            ->withQueryString();

        $dailyData = Payment::whereHas('order')
            ->whereDate('paid_at', '>=', $dateFrom)
            ->whereDate('paid_at', '<=', $dateTo)
            ->selectRaw('DATE(paid_at) as date, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        return view('reports.daily', compact('payments', 'dailyData', 'dateFrom', 'dateTo'));
    }

    public function monthly(Request $request)
    {
        $year = $request->year ?? Carbon::now()->year;

        $monthlyData = Payment::whereHas('order')
            ->whereYear('paid_at', $year)
            ->selectRaw("CAST(strftime('%m', paid_at) AS INTEGER) as month, SUM(amount) as total, COUNT(*) as count")
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $months = collect(range(1, 12))->map(function ($month) use ($monthlyData, $year) {
            $data = $monthlyData->firstWhere('month', $month);

            return [
                'month' => $month,
                'label' => Carbon::create($year, $month)->locale('id')->monthName,
                'total' => $data ? $data->total : 0,
                'count' => $data ? $data->count : 0,
            ];
        });

        $totalYear = $monthlyData->sum('total');
        $totalOrders = $monthlyData->sum('count');

        return view('reports.monthly', compact('months', 'year', 'totalYear', 'totalOrders'));
    }

    public function transactions(Request $request)
    {
        $query = Payment::whereHas('order')
            ->with(['order.customer', 'order.user', 'order.items.service'])
            ->latest('paid_at');

        if ($request->filled('date_from')) {
            $query->whereDate('paid_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('paid_at', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->whereHas('order', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        if ($request->filled('payment_status')) {
            $query->whereHas('order', function ($q) use ($request) {
                $q->where('payment_status', $request->payment_status);
            });
        }

        $transactions = $query->paginate(20)->withQueryString();

        return view('reports.transactions', compact('transactions'));
    }
}
