@extends('layouts.main')

@section('content')
    @php
        $profileWords = preg_split('/\s+/u', trim($user->nama ?: $user->username), -1, PREG_SPLIT_NO_EMPTY);
        $profileInitials = mb_strtoupper(mb_substr($profileWords[0] ?? 'U', 0, 1) . (count($profileWords) > 1 ? mb_substr(end($profileWords), 0, 1) : ''));
    @endphp
    <style>
        .profile-page { --profile-blue: #2463d3; }
        .profile-hero { position: relative; overflow: hidden; border: 0; border-radius: 1.15rem; background: linear-gradient(118deg, #eef5ff 0%, #fff 68%); }
        .profile-hero::after { content: ''; position: absolute; width: 230px; height: 230px; right: -70px; top: -120px; border-radius: 50%; background: rgba(36, 99, 211, .08); }
        .profile-avatar { display: inline-flex; align-items: center; justify-content: center; width: 72px; height: 72px; flex: 0 0 72px; border-radius: 22px; color: #fff; font-size: 1.45rem; font-weight: 800; letter-spacing: .04em; background: linear-gradient(145deg, #3886f6, #1749a5); box-shadow: 0 12px 24px rgba(36, 99, 211, .22); }
        .profile-card { border: 1px solid #edf0f5; border-radius: 1rem; box-shadow: 0 8px 30px rgba(30, 50, 90, .045); }
        .profile-card .form-control { min-height: 44px; border-color: #e1e7f0; border-radius: .65rem; }
        .profile-card .form-control:focus { border-color: #8bb4f5; box-shadow: 0 0 0 .2rem rgba(36, 99, 211, .1); }
        .profile-label { margin-bottom: .45rem; color: #344054; font-size: .83rem; font-weight: 700; }
        .profile-section-icon { display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; border-radius: 12px; color: var(--profile-blue); background: #edf4ff; }
        @media(max-width:575.98px) { .profile-avatar { width: 58px; height: 58px; flex-basis: 58px; border-radius: 18px; } }
    </style>

    <div class="profile-page container-fluid px-0">
        <div class="profile-hero card mb-6">
            <div class="card-body p-5 p-lg-7 position-relative" style="z-index:1">
                <div class="d-flex align-items-center gap-4">
                    <div class="profile-avatar" aria-label="Inisial {{ $user->nama }}">{{ $profileInitials }}</div>
                    <div class="min-w-0">
                        <span class="text-primary fw-bold fs-8 text-uppercase">Pengaturan akun</span>
                        <h1 class="fs-2 fw-bolder text-gray-900 mb-1">Profil Saya</h1>
                        <div class="text-muted">Perbarui informasi akun dan keamanan login Anda.</div>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success d-flex align-items-center gap-3 mb-5" role="status">
                <i class="fas fa-check-circle fs-2"></i><span>{{ session('success') }}</span>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger mb-5" role="alert">
                <div class="fw-bold mb-1">Periksa kembali data yang dimasukkan.</div>
                <ul class="mb-0 ps-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" autocomplete="off">
            @csrf
            @method('PUT')
            <div class="card profile-card mb-6">
                <div class="card-header border-0 pt-5 px-5 px-lg-7">
                    <div class="d-flex align-items-center gap-3">
                        <span class="profile-section-icon"><i class="fas fa-id-card"></i></span>
                        <div><h2 class="fs-4 fw-bold text-gray-900 mb-1">Informasi pribadi</h2><span class="text-muted fs-7">Data identitas yang digunakan pada sistem.</span></div>
                    </div>
                </div>
                <div class="card-body px-5 px-lg-7 pt-4">
                    <div class="row g-5">
                        <div class="col-md-6">
                            <label for="nama" class="form-label profile-label">Nama lengkap</label>
                            <input id="nama" name="nama" type="text" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $user->nama) }}" maxlength="255" required>
                            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="nip" class="form-label profile-label">NIP</label>
                            <input id="nip" name="nip" type="text" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip', $user->nip) }}" maxlength="255" required>
                            @error('nip')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label profile-label">Email</label>
                            <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" maxlength="255">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="username" class="form-label profile-label">Username</label>
                            <input id="username" name="username" type="text" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}" maxlength="255" autocomplete="username" required>
                            @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card profile-card mb-6">
                <div class="card-header border-0 pt-5 px-5 px-lg-7">
                    <div class="d-flex align-items-center gap-3">
                        <span class="profile-section-icon"><i class="fas fa-shield-alt"></i></span>
                        <div><h2 class="fs-4 fw-bold text-gray-900 mb-1">Keamanan akun</h2><span class="text-muted fs-7">Kosongkan password baru jika tidak ingin menggantinya.</span></div>
                    </div>
                </div>
                <div class="card-body px-5 px-lg-7 pt-4">
                    <div class="row g-5">
                        <div class="col-md-4">
                            <label for="current_password" class="form-label profile-label">Password saat ini</label>
                            <input id="current_password" name="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" autocomplete="current-password">
                            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="password" class="form-label profile-label">Password baru</label>
                            <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" minlength="8" autocomplete="new-password">
                            <div class="form-text">Minimal 8 karakter.</div>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="password_confirmation" class="form-label profile-label">Konfirmasi password baru</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" minlength="8" autocomplete="new-password">
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex flex-wrap justify-content-between align-items-center gap-3 px-5 px-lg-7 py-4 bg-light bg-opacity-50 border-0">
                    <span class="text-muted fs-7"><i class="fas fa-lock me-1"></i> Perubahan akun disimpan secara aman.</span>
                    <button type="submit" class="btn btn-primary px-5"><i class="fas fa-save me-2"></i>Simpan perubahan</button>
                </div>
            </div>
        </form>
    </div>
@endsection
