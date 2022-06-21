<?php

namespace Tests\Feature\Livewire\Dashboard;

use App\Http\Livewire\TopStreamsByViewersTable;
use App\Models\Stream;
use Livewire\Livewire;
use Tests\Feature\AbstractFeatureTest;

class TopStreamsByViewersTableTest extends AbstractFeatureTest
{
    /** @test */
    public function it_should_have_livewire_component()
    {
        $this->get(route('dashboard'))
            ->assertSeeLivewire(TopStreamsByViewersTable::class);
    }

    /** @test */
    public function it_should_show_top_games_in_order_with_viewers_sum()
    {
        Stream::factory()->count(5)->create();

        $streams = Stream::query()->orderByDesc('viewer_count')->get();

        Livewire::test(TopStreamsByViewersTable::class)
            ->assertHasNoErrors()
            ->assertOk()
            ->assertSeeInOrder($streams->pluck('title')->toArray())
            ->assertSeeInOrder($streams->pluck('viewer_count')->map(function (int $viewer_count) {
                return number_format($viewer_count);
            })->toArray());
    }
}
