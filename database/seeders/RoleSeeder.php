<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => config('app.admin-name'),
            'email' => config('app.admin-email'),
            'password' => config('app.admin-password'),
        ]);

        $admin->assignRole('admin');
    }
}
