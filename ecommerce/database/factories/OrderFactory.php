<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            
            'number' => $this->faker->randomNumber(3, true),
            'user_id' => User::inRandomOrder()->first()->id,
            'product_id' => Product::inRandomOrder()->first()->id,
            'quantity' => $this->faker->randomDigitNotNull(),
            'address_id' => Address::inRandomOrder()->first()->id,
            'status' => $this->faker->randomElement(['shipped', 'pending', 'canceled', 'delivered', 'pending_delete']),
            'created_at' => $this->faker->dateTimeThisYear('now', null)
        ];
    }
}
