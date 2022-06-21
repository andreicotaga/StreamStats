<?php

namespace App\Http\Livewire;

use App\Models\Stream;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridEloquent;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;

final class TopStreamsByViewersTable extends PowerGridComponent
{
    use ActionButton;

    public string $sortField = 'viewer_count';

    public string $sortDirection = 'desc';

    public function setUp(): void
    {
        $this->showPerPage();
    }

    public function datasource(): ?Builder
    {
        return Stream::query()
            ->where('updated_at', '>=', Stream::lastImport('cachedTop1000'))
            ->limit(100);
    }

    public function addColumns(): ?PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('title')
            ->addColumn('viewer_count', fn (Stream $stream) => number_format($stream->viewer_count));
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
                ->field('user_name')
                ->sortable(),

            Column::add()
                ->title('Title')
                ->field('title'),

            Column::add()
                ->title('Viewers')
                ->field('viewer_count')
                ->sortable(),

        ];
    }
}
