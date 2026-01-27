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
        $adminEmail = ['info@sdssn.org', 'abdulsalamamtech@gmail.com'];
        foreach ($adminEmail as $email) {
            if (!User::where('email', $email)->exists()) {
                $user = User::create([
                    'name' => 'Admin User',
                    'email' => $email,
                    // Use bcrypt for password hashing by default - Reset password after access
                    'password' => bcrypt('password'),
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

                info('Admin user created with email: ' . $email);
            }
        }
    }
}
