<?php

namespace App\Http\Controllers\MasterBackend\ValidatorRole;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\UserProfil\User;
use App\Models\UserValidatorRole;
use App\Models\ValidatorRole;
use Illuminate\Http\Request;

class UserValidatorRoleController extends Controller
{
    public function index()
    {
        $users = User::with('position')
            ->orderBy('nama')
            ->get();

        $validatorRoles = ValidatorRole::where('is_active', 1)
            ->orderBy('nama_validator')
            ->get();

        $data = UserValidatorRole::with([
            'user.unit',
            'user.position',
            'validatorRole'
        ])->get();

        return view(
            'master_backend.user_profil.user_validator_role.index',
            compact('data', 'users', 'validatorRoles'),
            [
                'title' => 'User Validator Role',
            ]
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'validator_role_id' => 'required',
        ]);

        $exists = UserValidatorRole::where('user_id', $request->user_id)
            ->where('validator_role_id', $request->validator_role_id)
            ->exists();

        if ($exists) {
            return back()->with(
                'error',
                'User dan validator role sudah terdaftar'
            );
        }

        UserValidatorRole::create([
            'user_id' => $request->user_id,
            'validator_role_id' => $request->validator_role_id,
            'is_active' => true,
        ]);

        return back()->with(
            'success',
            'User validator berhasil ditambahkan'
        );
    }

    public function edit($id)
    {
        $data = UserValidatorRole::findOrFail($id);

        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required',
            'validator_role_id' => 'required',
        ]);

        $data = UserValidatorRole::findOrFail($id);

        $exists = UserValidatorRole::where('user_id', $request->user_id)
            ->where('validator_role_id', $request->validator_role_id)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return back()->with(
                'error',
                'User dan validator role sudah digunakan'
            );
        }

        $data->update([
            'user_id' => $request->user_id,
            'validator_role_id' => $request->validator_role_id,
            'is_active' => $request->is_active ?? true,
        ]);

        return back()->with(
            'success',
            'Data berhasil diperbarui'
        );
    }

    public function destroy($id)
    {
        $data = UserValidatorRole::findOrFail($id);

        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
