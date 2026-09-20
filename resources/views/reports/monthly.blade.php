@extends('layouts.app')
@section('title', 'Laporan Bulanan')
@section('breadcrumb', 'Laporan Bulanan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-between items-center mb-3">
            <h4 class="mb-0">Laporan Bulanan</h4>
            <form method="GET" action="{{ route('reports.monthly') }}" class="d-flex align-items-center gap-2">
                <label class="text-muted small mb-0">Tahun:</label>
                <select name="year" onchange="this.form.submit()" class="form-select form-select-sm w-auto">
                    @for($y = 2024; $y <= \Carbon\Carbon::now()->year + 1; $y++)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-12 col-md-4">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-1">Total Omzet Tahun {{ $year }}</p>
                <h4 class="mb-0">Rp {{ number_format($totalYear, 0, ',', '.') }}</h4>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Order</p>
                    <h4 class="mb-0">{{ $totalOrders }}</h4>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <p class="text-muted mb-1">Rata-rata / Bulan</p>
                        <h4 class="mb-0">Rp {{ number_format($totalOrders ? $totalYear / $totalOrders : 0, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Bulan</th>
                            <th>Omzet</th>
                            <th>Jumlah Order</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($months as $month)
                            <tr>
                                <td>{{ $month['label'] }}</td>
                                <td>Rp {{ number_format($month['total'], 0, ',', '.') }}</td>
                                <td class="text-muted">{{ $month['count'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td class="fw-medium">Total</td>
                            <td class="fw-bold">Rp {{ number_format($totalYear, 0, ',', '.') }}</td>
                            <td class="text-muted fw-medium">{{ $totalOrders }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection