@extends('layouts.app')
@section('title', 'Master Layanan')
@section('breadcrumb', 'Master Layanan')

@section('content')
<div class="row">
    <div class="col-12">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h5 class="mb-0">Master Layanan</h5>
                <a href="{{ route('services.create') }}" class="btn btn-primary btn-sm ms-auto">
                    <i class="ph-plus me-1"></i> Tambah Layanan
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama Layanan</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($services as $service)
                                <tr>
                                    <td>{{ $loop->iteration + ($services->currentPage() - 1) * $services->perPage() }}</td>
                                    <td>{{ $service->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $service->unit === 'kg' ? 'info' : 'success' }} rounded-pill">
                                            {{ $service->unit === 'kg' ? 'Kilogram' : 'Item' }}
                                        </span>
                                    </td>
                                    <td>Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $service->is_active ? 'success' : 'danger' }} rounded-pill">
                                            {{ $service->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('services.edit', $service) }}" class="btn btn-sm btn-light">
                                                <i class="ph-pencil"></i>
                                            </a>
                                            <form action="{{ route('services.destroy', $service) }}" method="POST"
                                                  onsubmit="return confirm('Nonaktifkan layanan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="ph-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="ph-list-plus ph-lg d-block mb-2"></i>
                                        Belum ada data layanan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($services->hasPages())
                    <div class="card-footer">
                        {{ $services->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection