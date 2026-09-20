@extends('layouts.auth')

@section('title', 'Konfirmasi Password')

@section('content')
<div class="content d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 200px);">
    <div class="login-form w-100" style="max-width: 420px;">
        <div class="card mb-0 shadow-sm">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary-light rounded-circle mb-3" style="width: 72px; height: 72px;">
                        <i class="ph-lock text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <h4 class="mb-1">Konfirmasi Password</h4>
                    <p class="text-muted mb-0">Masukkan password untuk melanjutkan</p>
                </div>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Masukkan password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="ph-check me-2"></i>Konfirmasi
                        </button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-decoration-none"><i class="ph-arrow-left me-1"></i>Kembali ke Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection