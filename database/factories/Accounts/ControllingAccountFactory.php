<?php

namespace Database\Factories\Accounts;

use App\Models\Accounts\ControllingAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class ControllingAccountFactory extends Factory
{

    protected $model = ControllingAccount::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'parent_account_id' => rand(10,50),
            'code' => array_rand([10,20,30,40,50]),
            'title' => $this->faker->text(30),
            'status' => $this->faker->numberBetween(0,1)
        ];
    }
}
