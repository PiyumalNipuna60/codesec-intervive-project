<?php

namespace Database\Factories;

use App\Models\Enquiry;
use App\Models\Itinerary;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItineraryFactory extends Factory
{
    protected $model = Itinerary::class;

    public function definition()
    {
        $days = [];
        $numDays = $this->faker->numberBetween(3, 10);
        $locations = ['Colombo', 'Kandy', 'Sigiriya', 'Ella', 'Galle'];
        $activities = [
            'City Tour', 'Temple Visit', 'Hiking', 'Beach Relaxation', 
            'Shopping', 'Cultural Show', 'Wildlife Safari'
        ];
        
        for ($i = 1; $i <= $numDays; $i++) {
            $days[] = [
                'day' => $i,
                'location' => $this->faker->randomElement($locations),
                'activities' => $this->faker->randomElements($activities, $this->faker->numberBetween(1, 3)),
            ];
        }
        
        return [
            'enquiry_id' => Enquiry::factory(),
            'title' => $this->faker->sentence(3),
            'notes' => $this->faker->paragraph(),
            'days' => $days,
        ];
    }
}