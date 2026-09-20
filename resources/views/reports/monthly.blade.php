@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Laporan Bulanan</h1>
        <form method="GET" action="{{ route('reports.monthly') }}" class="flex items-center space-x-3">
            <label class="text-sm text-gray-500">Tahun:</label>
            <select name="year" onchange="this.form.submit()"
                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @for($y = 2024; $y <= \Carbon\Carbon::now()->year + 1; $y++)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-500">Total Omzet Tahun {{ $year }}</p>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalYear, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-500">Total Order</p>
            <p class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-500">Rata-rata / Bulan</p>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalOrders ? $totalYear / $totalOrders : 0, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bulan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Omzet</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Order</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($months as $month)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $month['label'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">Rp {{ number_format($month['total'], 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $month['count'] }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <td class="px-6 py-3 text-sm font-medium text-gray-900">Total</td>
                    <td class="px-6 py-3 text-sm font-bold text-gray-900">Rp {{ number_format($totalYear, 0, ',', '.') }}</td>
                    <td class="px-6 py-3 text-sm font-medium text-gray-500">{{ $totalOrders }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection