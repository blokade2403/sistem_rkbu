<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\MasterBackend\SettingUser\Unit;
use App\Models\MasterBackend\SettingInput\JudulHeader;
use App\Models\MasterBackend\SettingInput\TahunAnggaran;
use App\Models\MasterBackend\SettingUser\Position;

class AuthController extends Controller
{
    public function showLogin()
    {
        $tahun = TahunAnggaran::where('status', 'aktif')->get();
        return view('login.index', compact('tahun'));
    }

    public function login(Request $request)
    {
        $request->validate(
            [
                'username' => 'required',
                'password' => 'required',
                'tahun_anggaran_id' => 'required|exists:tahun_anggarans,id',
            ],
            [
                'username.required' => 'Username wajib diisi',
                'password.required' => 'Password wajib diisi',
                'tahun_anggaran_id.required' => 'Tahun anggaran wajib dipilih',
                'tahun_anggaran_id.exists' => 'Tahun anggaran tidak valid',
            ],
        );

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (!Auth::attempt($credentials)) {
            return back()->with('error', 'Username atau password salah.');
        }

        $user = Auth::user();

        // Cek status user
        if ($user->status_user !== 'aktif') {
            Auth::logout();
            return back()->with('error', 'User tidak aktif.');
        }

        // Ambil tahun anggaran
        $tahun = TahunAnggaran::with('fase')->where('id', $request->tahun_anggaran_id)->first();

        $unit = Unit::find($user->unit_id);
        $position = Position::find($user->position_id);

        if (!$tahun) {
            Auth::logout();
            return back()->with('error', 'Tahun anggaran tidak valid.');
        }

        $request->session()->regenerate();

        Session::put('user_id', $user->id);
        Session::put('nip', $user->nip);
        Session::put('nama', $user->nama);
        Session::put('unit_id', $user->unit_id);
        Session::put('position_id', $user->position_id);
        Session::put('nama_jabatan', $position?->nama_jabatan);
        Session::put('tahun_anggaran', $tahun->nama_tahun_anggaran);
        Session::put('tahun_anggaran_id', $tahun->id);
        Session::put('fase_tahun_anggaran', $tahun->fase?->nama_fase);
        Session::put('fase_tahun_anggaran_id', $tahun->fase_id);
        Session::put('nama_unit', $unit?->nama_unit);

        return redirect()->route('module-portal');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->flush();
        return redirect()->route('login.form');
    }
}
