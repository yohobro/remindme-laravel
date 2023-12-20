<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Alice',
            'email' => 'alice@mail.com',
            'password' => bcrypt('123456'),
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Bob',
            'email' => 'bob@mail.com',
            'password' => bcrypt('123456'),
            'email_verified_at' => now()
        ]);
    }
}
