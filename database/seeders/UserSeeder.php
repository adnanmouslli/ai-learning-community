<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء حساب المسؤول
        User::create([
            'name' => 'المسؤول',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('asdasdasd'),
            'is_admin' => true,
            'points' => 1000,
            'rank' => 4,
            'email_verified_at' => now(),
        ]);

        // إنشاء بعض المستخدمين للاختبار
        User::create([
            'name' => 'مستخدم تجريبي',
            'username' => 'user1',
            'email' => 'user1@gmail.com',
            'password' => Hash::make('asdasdasd'),
            'is_admin' => false,
            'points' => 150,
            'rank' => 2,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'مطور',
            'username' => 'developer',
            'email' => 'developer@gmail.com',
            'password' => Hash::make('asdasdasd'),
            'is_admin' => false,
            'points' => 500,
            'rank' => 5,
            'email_verified_at' => now(),
        ]);

  
    }
}