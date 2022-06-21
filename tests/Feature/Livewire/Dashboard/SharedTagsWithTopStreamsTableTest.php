<?php

namespace Tests\Feature\Livewire\Dashboard;

use App\Http\Livewire\SharedTagsWithTopStreamsTable;
use App\Models\Stream;
use App\Models\TagDescription;
use Livewire\Livewire;
use Tests\Feature\AbstractFeatureTest;

class SharedTagsWithTopStreamsTableTest extends AbstractFeatureTest
{
    /** @test */
    public function it_should_have_livewire_component()
    {
        $this->user->lastImport('followedStreams', true);

        $this->get(route('dashboard'))
            ->assertSeeLivewire(SharedTagsWithTopStreamsTable::class);
    }

    /** @test */
    public function it_should_show_shared_tags_user_is_following_with_top_1000_streams()
    {
        $allTagDescriptions = TagDescription::factory()->count(10)->create([
            'localization' => 'en-us',
        ]);

        Stream::factory()->count(10)->create();
        $streams = Stream::query()->get();
        $streams->each(function (Stream $stream) use ($allTagDescriptions) {
            $stream->tags()->sync($allTagDescriptions->random(2)->pluck('tag_id'));
        });

        $followedStreams = Stream::query()
            ->with(['tags', 'tags.descriptions'])
            ->whereIn('id', $streams->random(2)->pluck('id'))->get();
        $this->user->followedStreams()->sync($followedStreams->pluck('id'));
        $this->user->refresh();

        $followedDescriptions = collect(data_get($followedStreams, '*.tags.*.descriptions.*'));

        Livewire::test(SharedTagsWithTopStreamsTable::class, ['user' => $this->user->load('followedStreams')])
            ->assertHasNoErrors()
            ->assertOk()
            ->assertSee($followedDescriptions->pluck('name')->toArray())
            ->assertSee($followedDescriptions->pluck('description')->toArray());
    }
}
