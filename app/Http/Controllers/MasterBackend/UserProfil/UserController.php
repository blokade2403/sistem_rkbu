<?php

namespace App\Http\Controllers\MasterBackend\UserProfil;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingUser\Fase;
use App\Models\MasterBackend\SettingUser\Position;
use App\Models\MasterBackend\SettingUser\Role;
use App\Models\MasterBackend\SettingUser\Unit;
use App\Models\MasterBackend\UserProfil\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $positions = Position::all();
        $roles = Role::all();
        $units = Unit::all();
        $fases = Fase::all();
        $users = User::with(['position', 'roles', 'unit'])->get();
        return view(
            'master_backend.user_profil.user.index',
            compact('users', 'positions', 'roles', 'units', 'fases'),
            [
                'title' => 'Halaman User',
                'title2' => 'User',
                'id' => 'user',
                'routePrefix' => 'users',
            ],
        );
    }

    public function create()
    {
        $positions = Position::all();
        $roles = Role::all();
        $units = Unit::all();
        $fases = Fase::all();
        return view('master_backend.user.create', compact('positions', 'roles', 'units', 'fases'), [
            'title' => 'Halaman Create User',
            'title2' => 'User',
            'routePrefix' => 'users',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:users,nip',
            'nama' => 'required|string|max:255',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'unit_id' => 'required',
            'position_id' => 'required',
            'fase_id' => 'nullable',
            'status_user' => 'required',
            'status_edit' => 'required',
            'roles' => 'required|array',
        ]);

        $user = User::create([
            'id' => Str::uuid(),
            'nip' => $request->nip,
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'unit_id' => $request->unit_id,
            'position_id' => $request->position_id,
            'fase_id' => $request->fase_id,
            'status_user' => $request->status_user,
            'status_edit' => $request->status_edit,
        ]);

        $user->roles()->attach($request->roles);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $positions = Position::all();
        $roles = Role::all();
        $units = Unit::all();
        $fases = Fase::all();

        return view(
            'master_backend.user.edit',
            compact('user', 'positions', 'roles', 'units', 'fases'),
        );
    }

    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->status_edit = $request->status_edit;
        $user->save();

        return back()->with('success', 'Status berhasil diupdate');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nip' => 'required|unique:users,nip,' . $user->id,
            'nama' => 'required|string|max:255',
            'username' => 'required|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'unit_id' => 'required',
            'position_id' => 'required',
            'fase_id' => 'nullable',
            'status_user' => 'required',
            'status_edit' => 'required',
            'roles' => 'required|array',
        ]);

        $data = [
            'nip' => $request->nip,
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'unit_id' => $request->unit_id,
            'position_id' => $request->position_id,
            'fase_id' => $request->fase_id,
            'status_user' => $request->status_user,
            'status_edit' => $request->status_edit,
        ];

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        $user->roles()->sync($request->roles);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui');
    }
}
