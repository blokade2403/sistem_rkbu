<?php

namespace App\Http\Controllers\MasterBackend\UserProfil;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\UserProfil\Ksp;
use App\Models\MasterBackend\UserProfil\User;
use App\Models\MasterBackend\SettingUser\Fase;
use App\Models\MasterBackend\SettingUser\Unit;
use App\Models\MasterBackend\UserProfil\Pejabat;
use App\Models\MasterBackend\SettingUser\LevelUser;
use App\Models\MasterBackend\SettingUser\AdminPendukung;
use App\Models\MasterBackend\SettingUser\Pptk;

class UserController extends Controller
{
    public function index()
    {
        $users                  = User::with(['ksp.pejabat'])->get();
        $admin_pendukungs       = AdminPendukung::all();
        $pptks                  = Pptk::all();
        // $level_user = LevelUser::all();
        return view('master_backend.user_profil.user.index', compact('users', 'admin_pendukungs', 'pptks'), [
            'title'       => 'Halaman User',
            'title2'      => 'User',
            'routePrefix' => 'users'
        ]);
    }

    public function create()
    {
        $ksps                   = Ksp::all();
        $units                  = Unit::all();
        $level_users            = LevelUser::all();
        $fases                  = Fase::all();
        $pejabats               = Pejabat::all();
        $admin_pendukungs       = AdminPendukung::all();
        $pptks                  = Pptk::all();
        // dd($ksps);
        return view('master_backend.user_profil.user.create', compact('ksps', 'units', 'pptks', 'level_users', 'fases', 'pejabats', 'admin_pendukungs'), [
            'title'       => 'Halaman User',
            'title2'      => 'User',
            'routePrefix' => 'users'
        ]);
    }

    // Method 'store' Ini adalah method dalam sebuah controller di Laravel yang bertanggung jawab untuk menyimpan data baru ke dalam database
    public function store(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'id_ksp'                    => 'required|exists:ksps,id_ksp',
                'id_unit'                   => 'required|exists:units,id_unit',
                'id_level'                  => 'required|exists:level_users,id_level',
                'id_fase'                   => 'required|exists:fases,id_fase',
                'id_pejabat'                => 'required|exists:pejabats,id_pejabat',
                //'id_admin_pendukung_ppk'    => 'nullable|sometimes|exists:admin_pendukungs,id_admin_pendukung_ppk',
                'username'                  => 'required|unique:users',
                'nama_lengkap'              => 'required',
                'nip_user'                  => 'required',
                'email'                     => 'required',
                'password'                  => 'required|min:6',
                'status_user'               => 'required|in:aktif,tidak aktif',
                'status_edit'               => 'required|in:aktif,tidak aktif',
            ]
        );

        $request->merge([
            'id_admin_pendukung_ppk'    => $request->id_admin_pendukung_ppk ?: null,
            'id_pptk'                   => $request->id_pptk ?: null,
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());
            // dd($errors);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        try {
            $user = new User([
                'id_ksp'                    => $request->id_ksp,
                'id_unit'                   => $request->id_unit,
                'id_level'                  => $request->id_level,
                'id_fase'                   => $request->id_fase,
                'id_pejabat'                => $request->id_pejabat,
                'id_admin_pendukung_ppk'    => $request->id_admin_pendukung_ppk ?? null,
                'id_pptk'                   => $request->id_pptk ?? null,
                'username'                  => $request->username,
                'nama_lengkap'              => $request->nama_lengkap,
                'nip_user'                  => $request->nip_user,
                'email'                     => $request->email,
                'password'                  => Hash::make($request->password),
                'status_user'               => $request->status_user,
                'status_edit'               => $request->status_edit,
            ]);

            // dd($user);

            $user->save();

            return redirect()->route('users.index')->with('success', 'User berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $user = User::with('ksp')->findOrFail($id);
        return view('backend.user_profile.users.show', compact('user'));
    }

    public function edit($id)
    {
        $users                   = User::findOrFail($id);
        $ksps                   = Ksp::all();
        $level_users            = LevelUser::all();
        $fases                  = Fase::all();
        $units                  = Unit::all();
        $pejabats               = Pejabat::all();
        $admin_pendukungs       = AdminPendukung::all();
        $pptks                  = Pptk::all();
        return view('master_backend.user_profil.user.edit', compact('users', 'ksps', 'pptks', 'level_users', 'fases', 'units', 'pejabats', 'admin_pendukungs'), [
            'title'       => 'Halaman User',
            'title2'      => 'User',
            'routePrefix' => 'users'
        ]);
    }

    public function update(Request $request, User $user)
    {

        // Validasi input
        $validator = Validator::make(
            $request->all(),
            [
                'username'                  => 'required',
                'nama_lengkap'              => 'required',
                'nip_user'                  => 'required',
                'email'                     => 'required',
                'password'                  => 'nullable',
                'status_user'               => 'required',
                'status_edit'               => 'required',
                'id_ksp'                    => 'required',
                'id_unit'                   => 'required',
                'id_level'                  => 'required',
                'id_fase'                   => 'required',
                'id_pejabat'                => 'required',
                'id_admin_pendukung_ppk'    => 'nullable',
                'id_pptk'                   => 'nullable',
            ]
        );

        // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan pesan error
        if ($validator->fails()) {
            // Mengambil semua pesan error
            $errors = $validator->errors();

            // Menyusun pesan error menjadi string
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $user->username                 = $request->input('username');
        $user->nama_lengkap             = $request->input('nama_lengkap');
        $user->nip_user                 = $request->input('nip_user');
        $user->email                    = $request->input('email');
        $user->status_user              = $request->input('status_user');
        $user->status_edit              = $request->input('status_edit');
        $user->id_ksp                   = $request->input('id_ksp');
        $user->id_unit                  = $request->input('id_unit');
        $user->id_level                 = $request->input('id_level');
        $user->id_fase                  = $request->input('id_fase');
        $user->id_pejabat               = $request->input('id_pejabat');
        $user->id_admin_pendukung_ppk   = $request->input('id_admin_pendukung_ppk');
        $user->id_pptk                  = $request->input('id_pptk');

        // Menggunakan fill() untuk mengisi field selain password
        $user->fill($request->except('password'));

        // Jika password diisi, hash dan update
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // dd($user);
        // Simpan perubahan user ke database
        $user->save();

        // Redirect kembali ke halaman user index dengan pesan sukses
        return redirect()->route('users.index')->with('success', 'User berhasil diupdate.');
    }

    public function edit_profil($id)
    {
        $user                   = User::findOrFail($id);
        $ksps                   = Ksp::all();
        $level_users            = LevelUser::all();
        $fases                  = Fase::all();
        $units                  = Unit::all();
        $pejabats               = Pejabat::all();
        $admin_pendukungs       = AdminPendukung::all();
        $pptks                  = Pptk::all();
        return view('backend.user_profile.users.edit_profil', compact('user', 'ksps', 'pptks', 'level_users', 'fases', 'units', 'pejabats', 'admin_pendukungs'));
    }

    public function update_profil(Request $request, User $user)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'username'                  => 'required',
            'nama_lengkap'              => 'required',
            'nip_user'                  => 'required',
            'email'                     => 'required|email',
            'password'                  => 'nullable',
            'status_user'               => 'required',
            'status_edit'               => 'required',
            'id_ksp'                    => 'required',
            'id_unit'                   => 'required',
            'id_level'                  => 'required',
            'id_fase'                   => 'required',
            'id_pejabat'                => 'required',
            'id_admin_pendukung_ppk'    => 'nullable',
            'id_pptk'                   => 'nullable',

        ]);

        if ($validator->fails()) {
            $errorMessages = implode(' ', $validator->errors()->all());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        // Data yang akan diupdate
        $dataProfil = $request->only([
            'username',
            'nama_lengkap',
            'nip_user',
            'email',
            'status_user',
            'status_edit',
            'id_ksp',
            'id_unit',
            'id_level',
            'id_fase',
            'id_pejabat',
            'id_admin_pendukung_ppk',
            'id_pptk',
        ]);

        // Cek apakah password diisi
        if ($request->filled('password')) {
            $dataProfil['password'] = Hash::make($request->password);
        }

        // dd($dataProfil);
        // Update profil user
        $user->update($dataProfil);

        return redirect()->route('dashbord')->with('success', 'User berhasil diupdate.');
    }



    public function updateStatus(Request $request, $id)
    {
        // Validasi ID level
        if (!$id || $id !== '9cff8aee-6b6d-49e8-a1d4-cb44f96377d8') {
            return redirect()->back()->withErrors(['msg' => 'ID tidak valid!']);
        }

        // Update semua user dengan id_level = '9cff8aee-6b6d-49e8-a1d4-cb44f96377d8'
        $affectedRows = User::where('id_level', $id)
            ->update(['status_edit' => 'tidak aktif']);

        if ($affectedRows > 0) {
            return redirect()->back()->with('success', 'Semua status berhasil diperbarui menjadi tidak aktif.');
        }

        return redirect()->back()->withErrors(['msg' => 'Tidak ada data yang diperbarui.']);
    }

    public function updateStatusAktif(Request $request, $id)
    {
        // Validasi ID level
        if (!$id || $id !== '9cff8aee-6b6d-49e8-a1d4-cb44f96377d8') {
            return redirect()->back()->withErrors(['msg' => 'ID tidak valid!']);
        }

        // Update semua user dengan id_level = '9cff8aee-6b6d-49e8-a1d4-cb44f96377d8'
        $affectedRows = User::where('id_level', $id)
            ->update(['status_edit' => 'aktif']);

        if ($affectedRows > 0) {
            return redirect()->back()->with('success', 'Semua status berhasil diperbarui menjadi Aktif.');
        }

        return redirect()->back()->withErrors(['msg' => 'Tidak ada data yang diperbarui.']);
    }

    public function updateStatusPerencanaan(Request $request, $id)
    {
        // Validasi ID level
        if ($id === '9cdfc238-3bf1-49de-a76e-e5a2749a5a1c') {
            return redirect()->back()->withErrors(['msg' => 'ID tidak diperbolehkan!']);
        }

        // Update semua user dengan id_level = '9cff8aee-6b6d-49e8-a1d4-cb44f96377d8'
        $affectedRows = User::where('id_level', $id)
            ->update(['id_fase' => '9cdfc24b-c25c-498c-b600-b5b52847e2b3']);

        if ($affectedRows > 0) {
            return redirect()->back()->with('success', 'Semua status berhasil diperbarui menjadi Perencanaan.');
        }

        return redirect()->back()->withErrors(['msg' => 'Tidak ada data yang diperbarui.']);
    }

    public function updateStatusPenetapan(Request $request, $id)
    {
        // Pastikan ID bukan ID yang tidak diperbolehkan
        if ($id === '9cdfc238-3bf1-49de-a76e-e5a2749a5a1c') {
            return redirect()->back()->withErrors(['msg' => 'ID ini tidak diperbolehkan!']);
        }

        // Update semua user dengan id_level selain yang dilarang
        $affectedRows = User::where('id_level', '<>', '9cdfc238-3bf1-49de-a76e-e5a2749a5a1c')
            ->where('id_level', $id)
            ->update(['id_fase' => '9cff7222-e08a-4231-b1c1-4515a716f41c']);

        if ($affectedRows > 0) {
            return redirect()->back()->with('success', 'Semua status berhasil diperbarui menjadi Penetapan.');
        }

        return redirect()->back()->withErrors(['msg' => 'Tidak ada data yang diperbarui.']);
    }


    public function updateStatusPerubahan(Request $request, $id)
    {
        // Pastikan ID bukan ID yang tidak diperbolehkan
        if ($id === '9cdfc238-3bf1-49de-a76e-e5a2749a5a1c') {
            return redirect()->back()->withErrors(['msg' => 'ID ini tidak diperbolehkan!']);
        }

        // Update semua user dengan id_level selain yang dilarang
        $affectedRows = User::where('id_level', '<>', '9cdfc238-3bf1-49de-a76e-e5a2749a5a1c')
            ->where('id_level', $id)
            ->update(['id_fase' => '9cff722d-ea60-4b22-9fd6-6f3fb130d90d']);

        if ($affectedRows > 0) {
            return redirect()->back()->with('success', 'Semua status berhasil diperbarui menjadi Penetapan.');
        }

        return redirect()->back()->withErrors(['msg' => 'Tidak ada data yang diperbarui.']);
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
