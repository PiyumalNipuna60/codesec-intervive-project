<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Quotation;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        return [
            'quotation_id' => Quotation::factory(),
            'amount' => $this->faker->numberBetween(100, 2000),
            'payment_method' => $this->faker->randomElement([
                'cash', 
                'credit_card', 
                'bank_transfer'
            ]),
            'transaction_reference' => 'PAY-' . $this->faker->unique()->randomNumber(6),
        ];
    }
}