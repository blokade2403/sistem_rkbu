<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'Super Admin', 'Administrator', 'Operator', 'Validator', 'Viewer',
            'Admin Pengadaan', 'Pendukung PPK', 'Pengurus Barang',
        ];

        foreach ($roles as $role) {
            $id = DB::table('roles')->where('nama_role', $role)->value('id') ?? Str::uuid();
            DB::table('roles')->updateOrInsert(
                ['nama_role' => $role],
                [
                    'id' => $id,
                    'nama_role' => $role,
                ],
            );
        }
    }
}
