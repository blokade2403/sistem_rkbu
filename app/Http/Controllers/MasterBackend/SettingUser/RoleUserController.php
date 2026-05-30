<?php

namespace App\Http\Controllers\MasterBackend\SettingUser;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingUser\Role;
use App\Models\MasterBackend\UserProfil\User;
use Illuminate\Http\Request;

class RoleUserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();

        return view('master_backend.role_user.index', compact('users', 'roles'));
    }

    public function assign(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'roles' => 'required|array',
        ]);

        $user = User::findOrFail($request->user_id);

        // Sync roles (hapus lama, insert baru)
        $user->roles()->sync($request->roles);

        return back()->with('success', 'Role berhasil diupdate untuk user');
    }

    public function remove($user_id, $role_id)
    {
        $user = User::findOrFail($user_id);

        $user->roles()->detach($role_id);

        return back()->with('success', 'Role berhasil dilepas');
    }
}
