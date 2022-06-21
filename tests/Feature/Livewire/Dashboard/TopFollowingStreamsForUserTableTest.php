<?php

namespace Tests\Feature\Livewire\Dashboard;

use App\Http\Livewire\TopFollowingStreamsForUserTable;
use App\Models\Stream;
use Livewire\Livewire;
use Tests\Feature\AbstractFeatureTest;

class TopFollowingStreamsForUserTableTest extends AbstractFeatureTest
{
    /** @test */
    public function it_should_have_livewire_component()
    {
        $this->user->lastImport('followedStreams', true);

        $this->get(route('dashboard'))
            ->assertSeeLivewire(TopFollowingStreamsForUserTable::class);
    }

    /** @test */
    public function it_should_show_top_streams_user_is_following_in_desc_order_by_viewer_count()
    {
        Stream::factory()->count(10)->create();
        $groups = Stream::query()->get()->shuffle()->split(2);

        $streamsFollowing    = $groups->first()->sortByDesc('viewer_count');
        $streamsNotFollowing = $groups->last();

        $this->user->followedStreams()->sync($streamsFollowing->pluck('id'));

        Livewire::test(TopFollowingStreamsForUserTable::class, ['user' => $this->user->load('followedStreams')])
            ->assertHasNoErrors()
            ->assertOk()
            ->assertSeeInOrder($streamsFollowing->pluck('user_name')->toArray())
            ->assertSeeInOrder($streamsFollowing->pluck('viewer_count')->toArray())
            ->assertDontSee($streamsNotFollowing->pluck('user_name')->toArray());
    }
}
