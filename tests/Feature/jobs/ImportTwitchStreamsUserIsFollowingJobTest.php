<?php

namespace Tests\Feature\jobs;

use App\Jobs\ImportTwitchStreamsUserIsFollowingJob;
use App\Models\Game;
use App\Models\Stream;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportTwitchStreamsUserIsFollowingJobTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_import_live_streams()
    {
        // Can't test it in workflow without sharing sensitive data
        if (!env('TEST_TWITCH_TOKEN')) {
            $this->assertTrue(true);
            return;
        }

        $user = User::factory()->create([
            'twitch_id'    => env('TEST_TWITCH_ID'),
            'twitch_token' => env('TEST_TWITCH_TOKEN'),
        ]);

        $job = new ImportTwitchStreamsUserIsFollowingJob($user);
        $job->handle();

        $this->assertTrue(Stream::count() > 0);
        $this->assertTrue(Game::count() > 0);
        $this->assertTrue(Tag::count() > 0);
        $this->assertTrue($user->followedStreams()->count() > 0);
    }
}
