@extends('layouts.auth')

@section('title', 'Login')

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
                    <p class="text-muted mb-0">Masuk ke akun Anda</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Masukkan email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Masukkan password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 d-flex align-items-center justify-content-between">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">Ingat saya</label>
                        </div>

                        @if (Route::has('password.request'))
                            <a class="text-decoration-none" href="{{ route('password.request') }}">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="ph-sign-in me-2"></i>Masuk
                        </button>
                    </div>
                </form>

                <div class="text-center text-muted small">
                    <p class="mb-1"><strong>Owner:</strong> owner@laundry.test / password123</p>
                    <p class="mb-0"><strong>Staff:</strong> staff@laundry.test / password123</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection