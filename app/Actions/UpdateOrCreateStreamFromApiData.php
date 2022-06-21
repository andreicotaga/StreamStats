<?php

namespace App\Actions;

use App\Models\Game;
use App\Models\Stream;
use App\Models\Tag;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/** @SuppressWarnings("UnusedPrivateMethod") */
class UpdateOrCreateStreamFromApiData
{
    use WithFaker;

    public static function execute(object $stream)
    {
        $self = new self();
        if ($stream->game_id) {
            Game::updateOrCreate([
                'id'   => $stream->game_id,
                'name' => $stream->game_name,
            ]);
        }

        $tags = [];
        if ($stream->tag_ids) {
            foreach ($stream->tag_ids as $tagUuid) {
                $tag = Tag::firstOrCreate([
                    'tag_uuid' => $tagUuid,
                ]);
                $tags[] = $tag->id;
            }
        }

        return $self->updateOrCreateShuffledStream($stream, $tags);
    }

    private function updateOrCreateShuffledStream(object $stream, array $tags): Stream
    {
        $faker = $this->makeFaker();

        Stream::query()->updateOrCreate([
            'id' => $stream->id,
        ], [
            'game_id'       => $stream->game_id ?: null,
            'user_id'       => config('services.twitch.shuffle_streams') ? $faker->randomNumber(8) : $stream->user_id,
            'user_login'    => config('services.twitch.shuffle_streams') ? $faker->slug(2) : $stream->user_login,
            'user_name'     => config('services.twitch.shuffle_streams') ? $faker->slug(2) : $stream->user_name,
            'game_name'     => $stream->game_name,
            'title'         => config('services.twitch.shuffle_streams') ? $faker->words(3, true) : $stream->title,
            'viewer_count'  => $stream->viewer_count,
            'started_at'    => Carbon::createFromFormat('Y-m-d\TH:i:s\Z', $stream->started_at),
            'language'      => $stream->language,
            'thumbnail_url' => config('services.twitch.shuffle_streams')
                ? Str::replace($stream->user_login, Str::random(6), $stream->thumbnail_url)
                : $stream->thumbnail_url,
        ]);

        $stream = Stream::find($stream->id);
        $stream->tags()->sync($tags);

        return $stream;
    }
}
