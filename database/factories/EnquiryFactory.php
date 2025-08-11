<?php

namespace Database\Factories;

use App\Models\Enquiry;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnquiryFactory extends Factory
{
    protected $model = Enquiry::class;

    public function definition()
    {
        $startDate = $this->faker->dateTimeBetween('+1 week', '+1 month');
        $endDate = $this->faker->dateTimeBetween($startDate, '+2 months');
        
        $destinations = ['Sigiriya', 'Ella', 'Kandy', 'Colombo', 'Galle', 'Nuwara Eliya'];
        
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'travel_start_date' => $startDate,
            'travel_end_date' => $endDate,
            'number_of_people' => $this->faker->numberBetween(1, 10),
            'preferred_destinations' => $this->faker->randomElements($destinations, $this->faker->numberBetween(1, 3)),
            'budget' => $this->faker->numberBetween(500, 5000),
            'status' => $this->faker->randomElement(['pending', 'in-progress', 'converted', 'rejected']),
            'assigned_to' => function() {
                return User::where('role', 'agent')->inRandomOrder()->first()->id ?? 
                       User::factory()->create(['role' => 'agent'])->id;
            },
        ];
    }

    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
                'assigned_to' => null
            ];
        });
    }
}