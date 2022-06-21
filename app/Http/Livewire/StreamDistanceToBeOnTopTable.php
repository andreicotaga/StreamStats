<?php

namespace App\Http\Livewire;

use App\Models\Stream;
use App\Models\User;
use App\Traits\PowerGridTables\UpdatedFollowedStreams;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridEloquent;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;

final class StreamDistanceToBeOnTopTable extends PowerGridComponent
{
    use ActionButton, UpdatedFollowedStreams;

    public User $user;

    public int $topList = 1000;

    public function datasource(): ?Collection
    {
        $lessViewers = Stream::cachedTop1000()->min('viewer_count');

        $lastStream = $this->user->followedStreams->sortByDesc('viewer_count')->last();

        if (!$lastStream) {
            return collect([]);
        }

        $lastStream->quantity_distance_to_top = $lessViewers - $lastStream->viewer_count;
        if ($lastStream->quantity_distance_to_top < 0) {
            $lastStream->quantity_distance_to_top = 0;
        }

        return collect([$lastStream]);
    }

    public function setUp(): void
    {
        $this->showPerPage();
    }

    public function addColumns(): ?PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('user_name')
            ->addColumn('title')
            ->addColumn('quantity_distance_to_top');
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
                ->field('user_name'),

            Column::add()
                ->title('Title')
                ->field('title'),

            Column::add()
                ->title('Need more Viewers')
                ->field('quantity_distance_to_top')
        ];
    }
}
