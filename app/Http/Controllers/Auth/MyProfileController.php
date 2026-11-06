<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MyProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', [
            'user' => auth()->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nip' => ['required', 'string', 'max:255', Rule::unique('users', 'nip')->ignore($user->getKey())],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->getKey())],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->getKey())],
            'current_password' => ['required_with:password', 'nullable', 'current_password'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.confirmed' => 'Konfirmasi password baru belum cocok.',
        ]);

        $user->nama = $validated['nama'];
        $user->nip = $validated['nip'];
        $user->email = $validated['email'] ?? null;
        $user->username = $validated['username'];
        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        session(['nama' => $user->nama, 'nip' => $user->nip]);

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
