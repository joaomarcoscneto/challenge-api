<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'number' => $this->faker->randomNumber(9),
            'value' => $this->faker->randomFloat(2, 10, 1000),
            'issuance_date' => $this->faker->date(),
            'sender_cnpj' => $this->faker->numerify('##############'),
            'sender_name' => $this->faker->company,
            'transporter_cnpj' => $this->faker->numerify('##############'),
            'transporter_name' => $this->faker->company,
        ];
    }
}
