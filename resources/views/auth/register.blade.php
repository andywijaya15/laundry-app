@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="content d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 200px);">
    <div class="login-form w-100" style="max-width: 420px;">
        <div class="card mb-0 shadow-sm">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary-light rounded-circle mb-3" style="width: 72px; height: 72px;">
                        <i class="ph-washing-machine text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <h4 class="mb-1">Laundry App</h4>
                    <p class="text-muted mb-0">Buat akun baru</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Masukkan nama">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Masukkan email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Masukkan password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password">
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="ph-user-plus me-2"></i>Daftar
                        </button>
                    </div>
                </form>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-decoration-none">Sudah punya akun? Masuk</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection