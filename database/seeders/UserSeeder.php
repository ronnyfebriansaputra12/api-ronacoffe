<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {

    $data = [
        [
            'nama_user' => 'Administrator',
            'email' => 'admin@gmail.com',
            'avatar' => null,
            'email_verified_at' => now(),
            'password' => Hash::make('admin12'),
            'password_confirmation' => Hash::make('admin12'),
            'posisi' => 'Administrator',
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'nama_user' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'avatar' => null,
            'email_verified_at' => now(),
            'password' => Hash::make('superadmin12'),
            'password_confirmation' => Hash::make('superadmin12'),
            'posisi' => 'superadmin',
            'role' => 'superadmin',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'nama_user' => 'karyawan',
            'email' => 'karyawan@gmail.com',
            'avatar' => null,
            'email_verified_at' => now(),
            'password' => Hash::make('karyawan12'),
            'password_confirmation' => Hash::make('karyawan12'),
            'posisi' => 'karyawan',
            'role' => 'karyawan',
            'created_at' => now(),
            'updated_at' => now()
        ]
    ];

        User::insert($data);
    }
}
