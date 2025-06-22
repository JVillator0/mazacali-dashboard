<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@test.com',
            ],
            [
                'name' => 'Normal',
                'email' => 'normal@test.com',
            ],

        ];

        foreach ($users as $user) {
            $user = User::firstOrCreate([
                'email' => $user['email'],
            ], [
                'name' => $user['name'],
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);

            setPermissionsTeamId(1);
            $user->syncRoles('admin');
        }
    }
}
