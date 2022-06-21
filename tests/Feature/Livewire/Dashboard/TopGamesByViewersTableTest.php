<?php

namespace Tests\Feature\Livewire\Dashboard;

use App\Http\Livewire\TopGamesByViewerTable;
use App\Models\Game;
use App\Models\Stream;
use Illuminate\Database\Eloquent\Collection;
use Livewire;
use Tests\Feature\AbstractFeatureTest;

class TopGamesByViewersTableTest extends AbstractFeatureTest
{
    /** @test */
    public function it_should_have_livewire_component()
    {
        $this->get(route('dashboard'))
            ->assertSeeLivewire(TopGamesByViewerTable::class);
    }

    /** @test */
    public function it_should_show_top_games_in_order_with_viewers_sum()
    {
        /** @var Collection $games */
        Game::factory()->count(5)->create();
        $games = Game::query()->get();

        $games->sortBy('id')
            ->each(function (Game $game, int $index) {
                Stream::factory()->count(5)->create([
                    'viewer_count' => 1000 * ($index + 1),
                    'game_id'      => $game->id,
                    'game_name'    => $game->name,
                ]);
            });

        $games = $games->sortByDesc('id');

        Livewire::test(TopGamesByViewerTable::class)
            ->assertHasNoErrors()
            ->assertOk()
            ->assertSeeInOrder($games->pluck('name')->toArray())
            ->assertSeeInOrder([
                '25,000',
                '20,000',
                '15,000',
                '10,000',
                '5,000',
            ]);
    }
}
