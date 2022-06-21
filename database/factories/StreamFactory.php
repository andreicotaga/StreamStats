<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Stream;
use Illuminate\Database\Eloquent\Factories\Factory;

class StreamFactory extends Factory
{
    protected $model = Stream::class;

    public function definition()
    {
        if (Game::query()->count() < 5) {
            Game::factory()->create();
        }
        $game = Game::query()->get()->random();

        return [
            'id'            => $this->faker->unique()->randomNumber(8),
            'game_id'       => $game->id,
            'user_id'       => $this->faker->randomNumber(8),
            'user_login'    => $this->faker->userName(),
            'user_name'     => $this->faker->userName(),
            'game_name'     => $game->name,
            'title'         => $this->faker->word(),
            'viewer_count'  => $this->faker->randomNumber(),
            'started_at'    => $this->faker->dateTimeThisYear(),
            'language'      => $this->faker->languageCode(),
            'thumbnail_url' => $this->faker->url(),
        ];
    }
}
