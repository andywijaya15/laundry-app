@extends('layouts.app')
@section('title', 'Edit Layanan')
@section('breadcrumb', 'Edit Layanan')

@section('content')
<div class="row">
    <div class="col-12 col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h5 class="mb-0">Edit Layanan</h5>
                <a href="{{ route('services.index') }}" class="btn btn-light btn-sm ms-auto">
                    <i class="ph-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <ul class="list-unstyled mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('services.update', $service) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Layanan <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $service->name) }}"
                               class="form-control @error('name') is-invalid @enderror"
                               required maxlength="100" placeholder="Masukkan nama layanan">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="unit" class="form-label">Satuan <span class="text-danger">*</span></label>
                        <select id="unit" name="unit" class="form-select @error('unit') is-invalid @enderror" required>
                            <option value="kg" {{ old('unit', $service->unit) === 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                            <option value="item" {{ old('unit', $service->unit) === 'item' ? 'selected' : '' }}>Item</option>
                        </select>
                        @error('unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Harga <span class="text-danger">*</span></label>
                        <input type="number" id="price" name="price" value="{{ old('price', $service->price) }}"
                               class="form-control @error('price') is-invalid @enderror"
                               required min="0" step="100" max="99999999">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Dalam Rupiah, tanpa titik/koma</div>
                    </div>

                    <div class="mb-3 form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                               id="is_active" {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Aktif</label>
                    </div>

                    <div class="d-flex justify-end gap-2 mt-4">
                        <a href="{{ route('services.index') }}" class="btn btn-light">
                            <i class="ph-x me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ph-floppy-disk me-1"></i> Perbarui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection