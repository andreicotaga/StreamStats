<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    protected $model = Game::class;

    public function definition()
    {
        return [
            'id'          => $this->faker->unique()->randomNumber(),
            'name'        => $this->faker->name(),
            'box_art_url' => $this->faker->url(),
        ];
    }
}
