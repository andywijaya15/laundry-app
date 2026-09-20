@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')
<div class="content d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 200px);">
    <div class="login-form w-100" style="max-width: 420px;">
        <div class="card mb-0 shadow-sm">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary-light rounded-circle mb-3" style="width: 72px; height: 72px;">
                        <i class="ph-lock-key text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <h4 class="mb-1">Reset Password</h4>
                    <p class="text-muted mb-0">Masukkan email untuk menerima link reset</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Masukkan email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="ph-paper-plane me-2"></i>Kirim Link Reset
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