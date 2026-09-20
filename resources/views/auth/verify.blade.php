@extends('layouts.auth')

@section('title', 'Verifikasi Email')

@section('content')
<div class="content d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 200px);">
    <div class="login-form w-100" style="max-width: 420px;">
        <div class="card mb-0 shadow-sm">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary-light rounded-circle mb-3" style="width: 72px; height: 72px;">
                        <i class="ph-envelope-open text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <h4 class="mb-1">Verifikasi Email</h4>
                    <p class="text-muted mb-0">Periksa email Anda untuk link verifikasi</p>
                </div>

                @if (session('resent'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        Link verifikasi baru telah dikirim ke email Anda.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <p class="text-center mb-4">Sebelum melanjutkan, silakan periksa email Anda untuk link verifikasi.</p>

                <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ph-paper-plane me-2"></i>Kirim Ulang Link
                    </button>
                </form>

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-decoration-none"><i class="ph-arrow-left me-1"></i>Kembali ke Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection