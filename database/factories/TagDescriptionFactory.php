<?php

namespace Database\Factories;

use App\Models\Tag;
use App\Models\TagDescription;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagDescriptionFactory extends Factory
{
    protected $model = TagDescription::class;

    public function definition(): array
    {
        return [
            'tag_id'       => Tag::factory(),
            'localization' => 'en-us',
            'name'         => $this->faker->name(),
            'description'  => $this->faker->text(),
        ];
    }
}
