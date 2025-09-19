<?php

namespace Database\Seeders;

use App\Models\Auth\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Muhammad Zeeshan',
            'email' => 'zeeshan@cge.com.pk',
            'password' => bcrypt('zeeshan1989'),
            'email_verified_at' => now(),
            'role_id' => 1,
        ]);
    }
}
