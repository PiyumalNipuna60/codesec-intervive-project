<?php

namespace Database\Factories;

use App\Models\Itinerary;
use App\Models\Quotation;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuotationFactory extends Factory
{
    protected $model = Quotation::class;

    public function definition()
    {
        return [
            'itinerary_id' => Itinerary::factory(),
            'title' => $this->faker->randomElement([
                'Basic Package', 
                'Standard Package', 
                'Deluxe Package'
            ]),
            'price_per_person' => $this->faker->numberBetween(100, 1000),
            'currency' => 'USD',
            'notes' => $this->faker->paragraph(),
            'is_final' => $this->faker->boolean(70), // 70% chance of being true
        ];
    }

    public function final()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_final' => true
            ];
        });
    }
}