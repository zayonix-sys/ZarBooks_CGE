<?php

namespace Database\Seeders;

use App\Models\Accounts\ParentAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParentAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ParentAccount::factory()->times(500)->create();
    }
}
