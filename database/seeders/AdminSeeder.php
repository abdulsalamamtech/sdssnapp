<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                // Application admin
        if (!User::where('email', 'info@sdssn.org')->exists()) {
            $user = User::create([
                'name' => 'Admin User',
                'email' => 'info@sdssn.org',
                'password' => bcrypt('password'), // Use bcrypt for password hashing by default
                'email_verified_at' => now(),
                'first_name' => 'Admin',
                'last_name' => 'User',
                'security_question' => 'What is your favorite color?',
                'answer' => 'Blue',
                'phone_number' => '1234567890',
                'address' => '123 Admin St, Admin City, Admin Country',
                'state' => 'Admin State',
                'country' => 'Admin Country',
            ]);
            $user->assignRole('super-admin');
            $user->assignRole('admin');

            info('Admin user created with email: info@sdssn.org');
        }
    }
}
