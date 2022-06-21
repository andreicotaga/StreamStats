<?php

namespace App\Http\Livewire;

use App\Models\Game;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridEloquent;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;

final class TopGamesByViewerTable extends PowerGridComponent
{
    use ActionButton;

    public string $sortField = 'streams_sum_viewer_count';

    public string $sortDirection = 'desc';

    public function setUp(): void
    {
        $this->showPerPage();
    }

    public function datasource(): ?Builder
    {
        return Game::query()
            ->withCount('streams')
            ->withSum('streams', 'viewer_count')
            ->withAvg('streams', 'viewer_count');
    }

    public function addColumns(): ?PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('id')
            ->addColumn('name')
            ->addColumn('streams_count')
            ->addColumn('streams_sum_viewer_count', function (Game $game) {
                return number_format($game->streams_sum_viewer_count);
            })
            ->addColumn('streams_avg_viewer_count', function (Game $game) {
                return number_format($game->streams_avg_viewer_count);
            });
    }

    /**
    * PowerGrid Columns.
    *
    * @return array<int, Column>
    */
    public function columns(): array
    {
        return [
            Column::add()
                ->title('Name')
                ->field('name'),

            Column::add()
                ->title('Viewers')
                ->field('streams_sum_viewer_count')
                ->sortable(),

            Column::add()
                ->title('Streams')
                ->field('streams_count')
                ->sortable(),

            Column::add()
                ->title('Avg Viewers per Stream')
                ->field('streams_avg_viewer_count')
                ->sortable(),
        ];
    }
}
