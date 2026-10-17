<?php

namespace Database\Seeders;

use App\Models\MasterBackend\SettingUser\PositionAssignment;
use App\Models\MasterBackend\UserProfil\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PositionAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereNotNull('position_id')->get();

        foreach ($users as $user) {

            PositionAssignment::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'position_id' => $user->position_id,
                ],
                [
                    'id' => Str::uuid(),
                    'tanggal_mulai' => now()->toDateString(),
                    'tanggal_selesai' => null,
                    'is_active' => true,
                ]
            );
        }
    }
}
