<?php

namespace App\Http\Controllers\MasterBackend\SettingUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingUser\LevelUser;

class LevelUserController extends Controller
{
    public function index()
    {
        $level_users = LevelUser::all();
        return view('master_backend.setting_users.level_user.index', [
            'level_users' => $level_users,
            'title'         => 'Halaman Level User',
            'title2'        => 'Level User',
            'routePrefix'   => 'level_users'
        ]);
    }

    public function create()
    {
        return view('master_backend.setting_users.level_user.create', [
            'title'         => 'Halaman Level User',
            'title2'        => 'Level User',
            'routePrefix'   => 'level_users'
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            ['nama_level_user' => 'required'],
            ['nama_level_user' => 'Nama level_user belum diisi']
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessage   = implode(' ', $errors->all());

            return redirect()->back()->withInput()->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali:', $errorMessage);
        }

        $level_user = new LevelUser([
            'nama_level_user'  => $request->nama_level_user
        ]);

        $level_user->save();
        return redirect()->route('level_users.index')->with('success', 'Data Struktur level_user Berhasil di Tambahkan');
    }

    public function show(LevelUser $level_user)
    {
        //
    }

    public function edit(LevelUser $level_user)
    {
        return view('master_backend.setting_users.level_user.edit', [
            'level_users'   => $level_user,
            'title'         => 'Halaman Level User',
            'title2'        => 'Level User',
            'routePrefix'   => 'level_users'
        ]);
    }


    public function update(Request $request, LevelUser $level_user)
    {
        $validator = Validator::make(
            $request->all(),
            ['nama_level_user' => 'required'],
            ['nama_level_user' => 'Nama level_user belum diisi']
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessage   = implode(' ', $errors->all());

            return redirect()->back()->withInput()->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali:', $errorMessage);
        }
        // dd($level_user);

        $level_user->update($request->all());
        return redirect()->route('level_users.index')->with('success', 'Data Struktur level_user Berhasil di Update');
    }

    public function destroy($id)
    {

        $level_user = LevelUser::findOrFail($id);
        $level_user->delete();

        return redirect()->route('level_users.index')->with('success', 'Data level_user Berhasil dihapus.');
    }
}
