<?php

namespace Tests\Feature\Livewire\Dashboard;

use App\Http\Livewire\StreamDistanceToBeOnTopTable;
use App\Models\Stream;
use Livewire\Livewire;
use Tests\Feature\AbstractFeatureTest;

class StreamDistanceToBeOnTopTableTest extends AbstractFeatureTest
{
    /** @test */
    public function it_should_have_livewire_component()
    {
        $this->user->lastImport('followedStreams', true);

        $this->get(route('dashboard'))
            ->assertSeeLivewire(StreamDistanceToBeOnTopTable::class);
    }

    /** @test */
    public function it_should_show_lower_stream_user_is_following_with_diff_to_be_top_1000()
    {
        Stream::factory()->count(10)->create([
            'viewer_count' => 10000,
        ]);
        Stream::factory()->create([
            'viewer_count' => 100,
        ]);

        // Create cached data
        Stream::cachedTop1000();

        Stream::factory()->create([
            'viewer_count' => 60,
        ]);
        $lastStream = Stream::query()->orderBy('viewer_count')->first();

        $this->user->followedStreams()->sync($lastStream->id);
        $this->user->refresh();

        Livewire::test(StreamDistanceToBeOnTopTable::class, ['user' => $this->user->load('followedStreams')])
            ->assertHasNoErrors()
            ->assertOk()
            ->assertSee($lastStream->title)
            ->assertSee(40);
    }
}
