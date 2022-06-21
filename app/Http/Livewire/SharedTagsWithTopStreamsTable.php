<?php

namespace App\Http\Livewire;

use App\Models\Stream;
use App\Models\User;
use App\Traits\PowerGridTables\UpdatedFollowedStreams;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridEloquent;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;

final class SharedTagsWithTopStreamsTable extends PowerGridComponent
{
    use ActionButton, UpdatedFollowedStreams;

    public User $user;

    public function setUp(): void
    {
        $this->showPerPage();
    }

    public function datasource(): ?Collection
    {
        $topStreams = Stream::cachedTop1000();

        return collect(data_get($this->user->query()->with('followedStreams.tags.descriptions')
            ->whereHas('followedStreams.tags.descriptions', function (Builder $query) use ($topStreams) {
                return $query->whereIn('tag_id', $topStreams->pluck('tags.*.id')->flatten()->toArray())
                    ->where('localization', '=', 'en-us');
            })->get(), '*.followedStreams.*.tags.*.descriptions.*'))->unique();
    }

    public function addColumns(): ?PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('name')
            ->addColumn('description');
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
                ->title('Description')
                ->field('description'),
        ];
    }
}
