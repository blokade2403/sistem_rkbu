@extends('layouts-login.main')

@section('container')
    <main class="login-page">
        <section class="login-showcase" aria-label="Informasi Sistem RKBU">
            <div class="showcase-topline"><span>SISTEM PERENCANAAN PENGADAAN DAN KEUANGAN TERINTEGRASI </span></div>

            <div class="showcase-copy">
                <span class="eyebrow"><span class="status-dot"></span> RUANG KERJA PERENCANAAN</span>
                <h1>Rencanakan kebutuhan.<br><span>Kelola anggaran</span><br>dengan lebih terarah.</h1>
                <p>Platform terpadu untuk mendukung proses perencanaan kebutuhan barang dan jasa secara tertib, transparan, dan akuntabel.</p>
            </div>

            <div class="showcase-bottom">
                <div class="showcase-decoration" aria-hidden="true"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>
                <span>PERENCANAAN YANG LEBIH BAIK, DIMULAI DI SINI.</span>
            </div>
        </section>

        <section class="login-panel">
            <div class="login-content">
                <div class="application-brand">
                    <img src="{{ $header6LogoUrl }}" alt="Logo Si-Penguin">
                    <div><strong>Si-Penguin</strong><span>Sistem Perencanaan Pengadaan<br class="desktop-break"> dan Keuangan Terintegrasi</span></div>
                </div>
                <div class="login-heading">
                    <span class="login-kicker">SELAMAT DATANG KEMBALI</span>
                    <h2>Masuk ke akun Anda</h2>
                    <p>Gunakan akun yang terdaftar untuk melanjutkan.</p>
                </div>

                <form class="login-form" id="kt_sign_in_form" action="{{ route('login.process') }}" method="POST">
                    @csrf
                    <div class="field-group">
                        <label for="username">Username</label>
                        <input id="username" class="form-control" type="text" name="username" autocomplete="username" placeholder="Masukkan username" required autofocus>
                    </div>

                    <div class="field-group">
                        <label for="password">Password</label>
                        <div class="password-wrap">
                            <input id="password" class="form-control" type="password" name="password" autocomplete="current-password" placeholder="Masukkan password" required>
                            <button class="password-toggle" type="button" aria-label="Tampilkan password" aria-pressed="false" data-password-toggle>
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M2.5 12s3.4-6 9.5-6 9.5 6 9.5 6-3.4 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.7"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="field-group">
                        <label for="tahun_anggaran_id">Tahun anggaran</label>
                        <select id="tahun_anggaran_id" name="tahun_anggaran_id" class="form-select" data-control="select2" data-placeholder="Pilih tahun anggaran" required>
                            <option value="">Pilih tahun anggaran</option>
                            @foreach ($tahun as $tahun_anggaran)
                                <option value="{{ $tahun_anggaran->id }}">{{ $tahun_anggaran->nama_tahun_anggaran }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="login-submit">
                        <span class="indicator-label">Masuk ke Sistem</span>
                        <span class="indicator-progress">Sedang memproses<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </button>
                    <p class="login-note"><span class="lock-icon" aria-hidden="true">&#9679;</span> Akses Anda terlindungi dan hanya untuk pengguna terdaftar.</p>
                </form>
            </div>

            <footer class="login-footer"><span>&copy; {{ date('Y') }} Sistem RKBU</span><span>Perencanaan kebutuhan barang dan jasa</span></footer>
        </section>
    </main>

    <style>
        :root { --login-ink: #172b3a; --login-muted: #8290a0; --login-blue: #2563c9; --login-blue-dark: #194da8; }
        body.login-body { margin: 0; background: #f8faf9; font-family: Inter, Poppins, "Segoe UI", sans-serif; color: var(--login-ink); }
        .login-page { min-height: 100vh; display: grid; grid-template-columns: minmax(390px, 0.92fr) minmax(520px, 1.08fr); }
        .login-showcase { position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; min-height: 100vh; padding: clamp(38px, 5vw, 76px); color: #fff; background: radial-gradient(ellipse at 8% 95%, rgba(79, 145, 239, .3), transparent 34%), linear-gradient(145deg, #173b70 0%, #102d59 52%, #0d2347 100%); }
        .login-showcase::before, .login-showcase::after { content: ""; position: absolute; border: 1px solid rgba(255,255,255,.08); border-radius: 50%; pointer-events: none; }
        .login-showcase::before { width: 620px; height: 620px; right: -310px; top: 18%; box-shadow: 0 0 0 44px rgba(255,255,255,.018), 0 0 0 88px rgba(255,255,255,.018), 0 0 0 132px rgba(255,255,255,.018); }
        .login-showcase::after { width: 360px; height: 360px; left: -260px; bottom: -170px; }
        .showcase-topline, .mobile-brand { position: relative; z-index: 1; display: flex; align-items: center; gap: 12px; font-size: 13px; font-weight: 700; letter-spacing: .16em; }
        .showcase-copy { position: relative; z-index: 1; max-width: 590px; margin: auto 0; padding: 70px 0; }
        .eyebrow, .login-kicker { display: inline-flex; align-items: center; gap: 10px; font-size: 10px; font-weight: 700; letter-spacing: .17em; }
        .eyebrow { color: #c0d8ff; }
        .status-dot { width: 7px; height: 7px; border-radius: 50%; background: #8ebaff; box-shadow: 0 0 0 4px rgba(142,186,255,.16); }
        .showcase-copy h1 { margin: 25px 0 20px; font-size: clamp(38px, 4.2vw, 62px); line-height: 1.16; font-weight: 500; letter-spacing: -.045em; }
        .showcase-copy h1, .showcase-copy h1 span { color: #fff; }
        .showcase-copy p { max-width: 460px; margin: 0; color: rgba(235,247,239,.68); font-size: 15px; line-height: 1.9; }
        .showcase-bottom { position: relative; z-index: 1; display: flex; align-items: end; justify-content: space-between; gap: 20px; color: rgba(231,245,236,.48); font-size: 9px; font-weight: 700; letter-spacing: .15em; }
        .showcase-decoration { display: flex; align-items: end; gap: 5px; height: 31px; }
        .showcase-decoration span { width: 4px; border-radius: 5px; background: #77aaf5; opacity: .78; }
        .showcase-decoration span:nth-child(3n+1) { height: 12px; opacity: .4; }.showcase-decoration span:nth-child(3n+2) { height: 21px; opacity: .6; }.showcase-decoration span:nth-child(3n) { height: 30px; }
        .login-panel { display: flex; flex-direction: column; justify-content: center; min-height: 100vh; padding: 56px clamp(38px, 8vw, 130px); background: #fbfcfb; }
        .login-content { width: 100%; max-width: 430px; margin: auto; }
        .application-brand { display: flex; align-items: center; gap: 17px; margin-bottom: 47px; }
        .application-brand img { display: block; flex: 0 0 auto; width: 150px; height: 150px; object-fit: contain; }
        .application-brand div { display: flex; flex-direction: column; gap: 5px; }
        .application-brand strong { color: #1d3553; font-size: 21px; line-height: 1.2; font-weight: 750; letter-spacing: -.035em; }
        .application-brand span { color: #8290a0; font-size: 11px; line-height: 1.55; }
        .login-heading { margin-bottom: 38px; }
        .login-kicker { color: var(--login-blue); }
        .login-heading h2 { margin: 13px 0 8px; color: #1c3029; font-size: clamp(27px, 2.5vw, 34px); line-height: 1.25; font-weight: 650; letter-spacing: -.04em; }
        .login-heading p { margin: 0; color: var(--login-muted); font-size: 14px; }
        .login-form { display: grid; gap: 23px; }
        .field-group label { display: block; margin-bottom: 9px; color: #34473f; font-size: 13px; font-weight: 650; }
        .login-form .form-control, .login-form .form-select { width: 100%; height: 53px; padding: 0 15px; border: 1px solid #e1e9e4; border-radius: 10px; outline: none; background-color: #fff; color: #263b32; font: inherit; font-size: 14px; box-shadow: 0 2px 5px rgba(29,57,43,.025); transition: border-color .2s, box-shadow .2s; }
        .login-form .form-control::placeholder { color: #a5b0aa; }
        .login-form .form-control:focus, .login-form .form-select:focus { border-color: #75a5ed; box-shadow: 0 0 0 4px rgba(37,99,201,.1); }
        .password-wrap { position: relative; }
        .password-wrap .form-control { padding-right: 50px; }
        .password-toggle { position: absolute; top: 0; right: 7px; display: grid; place-items: center; width: 40px; height: 53px; padding: 0; border: 0; background: transparent; color: #91a098; cursor: pointer; }
        .password-toggle svg { width: 20px; height: 20px; stroke: currentColor; stroke-width: 1.6; }
        .login-submit { display: flex; align-items: center; justify-content: center; gap: 11px; width: 100%; min-height: 54px; margin-top: 5px; border: 0; border-radius: 10px; background: var(--login-blue); color: #fff; font: inherit; font-size: 14px; font-weight: 650; cursor: pointer; box-shadow: 0 8px 18px rgba(37,99,201,.2); transition: background .2s, transform .2s, box-shadow .2s; }
        .login-submit:hover { background: var(--login-blue-dark); transform: translateY(-1px); box-shadow: 0 10px 22px rgba(37,99,201,.25); }
        .login-submit svg { width: 18px; height: 18px; stroke: currentColor; stroke-width: 1.8; }
        .login-submit .indicator-progress { display: none; }.login-submit .indicator-label { display: inline; }
        .login-note { display: flex; align-items: center; justify-content: center; gap: 8px; margin: 0; color: #96a29c; font-size: 11px; text-align: center; }
        .lock-icon { display: inline-grid; place-items: center; width: 13px; height: 13px; border: 1px solid #a2afa7; border-radius: 4px; font-size: 0; }.lock-icon::after { content: ""; width: 5px; height: 4px; border: 1px solid #a2afa7; border-bottom: 0; border-radius: 5px 5px 0 0; transform: translateY(-2px); }
        .login-footer { display: flex; justify-content: space-between; gap: 18px; width: 100%; max-width: 670px; margin: 48px auto 0; color: #9aa69f; font-size: 10px; }
        @media (max-width: 900px) { .login-page { grid-template-columns: minmax(300px, .8fr) minmax(420px, 1.2fr); }.login-showcase { padding: 42px 34px; }.showcase-copy h1 { font-size: 42px; }.login-panel { padding: 45px 48px; } }
        @media (max-width: 680px) { .login-page { display: block; min-height: 100vh; }.login-showcase { display: none; }.login-panel { min-height: 100vh; padding: 34px 24px 24px; }.application-brand { margin-bottom: 38px; }.application-brand img { width: 112px; height: 112px; }.application-brand strong { font-size: 19px; }.application-brand span { font-size: 10px; }.desktop-break { display: none; }.login-heading { margin-bottom: 31px; }.login-content { max-width: 440px; }.login-footer { margin-top: 42px; font-size: 9px; }.login-footer span:last-child { text-align: right; } }
        @media (max-width: 380px) { .login-panel { padding-right: 18px; padding-left: 18px; }.login-footer { flex-direction: column; gap: 6px; }.login-footer span:last-child { text-align: left; } }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.body.classList.add('login-body');
            const toggle = document.querySelector('[data-password-toggle]');
            const password = document.getElementById('password');
            if (toggle && password) {
                toggle.addEventListener('click', function () {
                    const visible = password.type === 'password';
                    password.type = visible ? 'text' : 'password';
                    toggle.setAttribute('aria-pressed', String(visible));
                    toggle.setAttribute('aria-label', visible ? 'Sembunyikan password' : 'Tampilkan password');
                });
            }
        });
    </script>
@endsection
