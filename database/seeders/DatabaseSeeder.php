<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ១. បញ្ចូល Role (Admin)
        $roleId = DB::table('roles')->insertGetId([
            'role_name'  => 'Admin',
            'created_at' => now(),
            'updated_at' => now(),
        ], 'role_id');

        // ២. បញ្ចូល Gender (Male)
        $genderId = DB::table('genders')->insertGetId([
            'gender_name' => 'Male',
            'created_at'  => now(),
            'updated_at'  => now(),
        ], 'gender_id');

        // ៣. បញ្ចូល Admin User ឱ្យត្រូវតាម Schema នៃ users table
        DB::table('users')->insert([
            'username'      => 'admin',
            'password_hash' => Hash::make('password123'),
            'gender_id'     => $genderId,
            'role_id'       => $roleId,
            'full_name'     => 'System Admin',
            'phone'         => '012345678',
            'email'         => 'admin@pos.com',
            'is_active'     => true,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
}