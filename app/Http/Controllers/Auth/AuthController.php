<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\MasterBackend\SettingInput\JudulHeader;
use App\Models\MasterBackend\SettingInput\TahunAnggaran;

class AuthController extends Controller
{
    /**
     * Menampilkan form login
     */
    public function showLoginForm()
    {
        $tahun_anggarans = TahunAnggaran::where('status', 'aktif')->get();
        $logo_login      = JudulHeader::get();
        return view('login.index', compact('tahun_anggarans', 'logo_login'));
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username'          => 'required',
            'password'          => 'required',
            'tahun_anggaran'    => 'required',
        ]);


        $credentials = [
            'username'    => $request->username,
            'password'    => $request->password,
            'status_user' => 'aktif'
        ];

        // Cek apakah user dapat diotentikasi
        if (Auth::attempt($credentials)) {
            // Ambil user yang terautentikasi
            $user = Auth::user();

            // Ambil data TahunAnggaran berdasarkan id_tahun_anggaran dari input
            $tahunAnggaran = TahunAnggaran::find($request->tahun_anggaran);

            if (!$tahunAnggaran) {
                // Jika tidak ditemukan, kembalikan error
                return redirect()->back()->withErrors(['tahun_anggaran' => 'Tahun anggaran tidak valid.'])->withInput();
            }

            // Simpan data yang dibutuhkan ke dalam session
            session([
                'id_user'                   => $user->id_user,
                'username'                  => $user->username,
                'nama_lengkap'              => $user->nama_lengkap,
                'tahun_anggaran'            => $tahunAnggaran->nama_tahun_anggaran, // Mengambil nama_tahun_anggaran dari hasil query
                'id_fase'                   => $user->id_fase,
                'id_pejabat'                => $user->id_pejabat,
                'nip_user'                  => $user->nip_user,
                'email'                     => $user->email,
                'id_level'                  => $user->id_level,
                'id_unit'                   => $user->id_unit,
                'id_ksp'                    => $user->id_ksp,
                'status_user'               => $user->status_user,
                'status_edit'               => $user->status_edit,
                'id_jabatan'                => $user->pejabat->id_jabatan ?? null,
                'nama_jabatan'              => $user->pejabat->jabatan->nama_jabatan ?? null,
                'nama_unit'                 => $user->unit->nama_unit ?? null,   // Mengambil nama_unit dari relasi
                'nama_fase'                 => $user->fase->nama_fase ?? null,   // Mengambil nama_fase dari relasi
                'nama_ksp'                  => $user->ksp->nama_ksp ?? null,     // Mengambil nama_ksp dari relasi
                'nama_level_user'           => $user->levelUser->nama_level_user ?? null,     // Mengambil nama_ksp dari relasi
                'id_admin_pendukung_ppk'    => $user->id_admin_pendukung_ppk,     // Mengambil nama_ksp dari relasi
                'id_pptk'                   => $user->id_pptk,     // Mengambil nama_ksp dari relasi
            ]);

            // dd(session()->all());

            // Redirect to dashboard with notifications
            return redirect()->route('dashbord')->with([
                'success' => 'Login berhasil untuk Anggaran Tahun ' . $tahunAnggaran->nama_tahun_anggaran,

            ]);
        }

        // Jika autentikasi gagal, kembalikan ke halaman login dengan error
        return redirect()->back()->withErrors(['username' => 'Username atau password salah.'])->withInput();
    }


    public function logout()
    {
        Session::flush();
        return redirect()->route('login.form')->with('success', 'Anda telah logout.');
    }
}
