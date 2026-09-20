@extends('layouts.app')
@section('title', 'Master Pelanggan')
@section('breadcrumb', 'Master Pelanggan')

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
                <h5 class="mb-0">Master Pelanggan</h5>
                <a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm ms-auto">
                    <i class="ph-user-plus me-1"></i> Tambah Pelanggan
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <form method="GET" action="{{ route('customers.index') }}" class="p-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="ph-magnifying-glass"></i></span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Cari nama atau nomor HP..."
                                   class="form-control">
                        </div>
                    </form>
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>No. HP</th>
                                <th>Alamat</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($customers as $customer)
                                <tr>
                                    <td>{{ $loop->iteration + ($customers->currentPage() - 1) * $customers->perPage() }}</td>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->phone }}</td>
                                    <td class="text-truncate" style="max-width: 200px;">{{ $customer->address ?? '-' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('customers.edit', $customer) }}"
                                           class="btn btn-sm btn-light">
                                            <i class="ph-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="ph-users ph-lg d-block mb-2"></i>
                                        Belum ada data pelanggan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($customers->hasPages())
                    <div class="card-footer">
                        {{ $customers->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection