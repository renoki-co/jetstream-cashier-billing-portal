<?php

namespace Database\Factories\RenokiCo\BillingPortal\Test\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Name'.Str::random(5),
            'email' => Str::random(5).'@gmail.com',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            $user->subscriptions()->create([
                'name' => 'main',
                'stripe_id' => 'stripe_id_here',
                'stripe_status' => 'active',
                'stripe_plan' => 'plan-123',
                'quantity' => 1,
            ]);
        });
    }
}
