<?php

namespace Database\Factories\Accounts;

use App\Models\Accounts\ParentAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class ParentAccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */

        protected $model = ParentAccount::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'code' => array_rand([10,20,30,40,50]),
            'account_group' => $this->faker->numberBetween(10,50),
            'title' => $this->faker->text(30),
            'status' => $this->faker->numberBetween(0,1)
        ];
    }
}
