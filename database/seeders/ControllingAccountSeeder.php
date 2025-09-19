<?php

namespace Database\Seeders;

use App\Models\Accounts\ControllingAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ControllingAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ControllingAccount::factory()->times(500)->create();
    }
}
