<?php

namespace Database\Seeders;

use App\Models\Itinerary;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItinerariesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 15 itineraries
        Itinerary::factory()->count(15)->create();
    }
}
