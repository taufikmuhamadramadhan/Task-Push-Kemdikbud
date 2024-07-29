<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => (string) Str::uuid(),
            'nama' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'), // Ganti dengan password yang diinginkan
            'is_login' => false,
            'last_login' => now(),
        ]);
    }
}
