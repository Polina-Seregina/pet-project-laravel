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
        $admin = User::firstOrCreate(
            [
            'email' => config('app.admin-email')],
            ['name' => config('app.admin-name'), 'password' => config('app.admin-password')]
        );

        $admin->profile()->firstOrCreate([
            'nickname' => 'admin',
        ]);

        $admin->wallet()->firstOrCreate(['balance' => 0]);

        $admin->assignRole('admin');
    }
}
