<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name, // Rastgele bir isim oluşturur
            'annual_leave_days' => $this->faker->numberBetween(10, 20), // 10 ile 20 arasında rastgele bir yıllık izin günü
        ];
    }
}
