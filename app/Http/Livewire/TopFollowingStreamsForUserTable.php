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

final class TopFollowingStreamsForUserTable extends PowerGridComponent
{
    use ActionButton, UpdatedFollowedStreams;

    public string $sortField = 'viewer_count';

    public string $sortDirection = 'desc';

    public User $user;

    public function setUp(): void
    {
        $this->showPerPage();
    }

    public function datasource(): ?Collection
    {
        return Stream::cachedTop1000()
            ->whereIn('id', $this->user->followedStreams->pluck('id')->toArray());
    }

    public function addColumns(): ?PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('user_name')
            ->addColumn('title')
            ->addColumn('viewer_count');
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
                ->title('Viewers')
                ->field('viewer_count')
                ->sortable()
        ];
    }
}
