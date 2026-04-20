<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->randomElement(['Electricity Bill', 'Water Bill', 'Internet', 'Office Rent', 'Medical Supplies', 'Staff Salary', 'Maintenance']),
            'description' => $this->faker->sentence(),
            'amount' => $this->faker->randomFloat(2, 50, 5000),
            'date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'category' => $this->faker->randomElement(['Utilities', 'Rent', 'Supplies', 'Salaries', 'Other']),
            'recorded_by' => \App\Models\User::factory(),
        ];
    }
}
