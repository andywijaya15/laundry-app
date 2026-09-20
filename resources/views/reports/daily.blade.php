@extends('layouts.app')
@section('title', 'Laporan Harian')
@section('breadcrumb', 'Laporan Harian')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('reports.daily') }}" class="row g-3">
                    <div class="col-12 col-md-3">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="date_from" value="{{ $dateFrom->format('Y-m-d') }}"
                               class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="date_to" value="{{ $dateTo->format('Y-m-d') }}"
                               class="form-control">
                    </div>
                    <div class="col-12 col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                    <div class="col-12 col-md-3 d-flex align-items-end">
                        <a href="{{ route('reports.daily') }}" class="btn btn-light w-100">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <p class="text-muted mb-1">Total Omzet</p>
                        <h4 class="mb-0">Rp {{ number_format($dailyData->sum('total'), 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <p class="text-muted mb-1">Total Transaksi</p>
                        <h4 class="mb-0">{{ $dailyData->sum('count') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <p class="text-muted mb-1">Rata-rata / Hari</p>
                        <h4 class="mb-0">Rp {{ number_format($dailyData->count() ? $dailyData->sum('total') / $dailyData->count() : 0, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Omzet</th>
                                <th>Jumlah Transaksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dailyData as $data)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($data->date)->format('d/m/Y') }}</td>
                                    <td>Rp {{ number_format($data->total, 0, ',', '.') }}</td>
                                    <td class="text-muted">{{ $data->count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">Tidak ada data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($payments->hasPages())
                    <div class="card-footer">
                        {{ $payments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection