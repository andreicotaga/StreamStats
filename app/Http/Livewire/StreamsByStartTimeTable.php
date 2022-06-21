<?php

namespace App\Http\Livewire;

use App\Models\Stream;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridEloquent;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;

final class StreamsByStartTimeTable extends PowerGridComponent
{
    use ActionButton;

    public string $sortField = 'started_at_hourly';

    public string $sortDirection = 'desc';

    public function setUp(): void
    {
        $this->showPerPage();
    }

    public function datasource(): ?Builder
    {
        return Stream::query()
            ->where('updated_at', '>=', Stream::lastImport('cachedTop1000'))
            ->addSelect(DB::raw('DATE_FORMAT(started_at, "%Y-%c-%e %H") as started_at_hourly'))
            ->addSelect(DB::raw('COUNT(*) as streams_count'))
            ->addSelect(DB::raw('SUM(viewer_count) as streams_sum_viewer_count'))
            ->groupBy('started_at_hourly');
    }

    public function addColumns(): ?PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('started_at_hourly_formatted', function (Stream $model) {
                return Carbon::createFromFormat('Y-m-d H', $model->started_at_hourly)->format('Y-m-d H\h');
            })
            ->addColumn('streams_sum_viewer_count', function (Stream $model) {
                return number_format($model->streams_sum_viewer_count);
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
                ->title('Started At')
                ->field('started_at_hourly_formatted', 'started_at_hourly')
                ->sortable(),

            Column::add()
                ->title('Streams')
                ->field('streams_count'),

            Column::add()
                ->title('Viewers')
                ->field('streams_sum_viewer_count'),
        ];
    }
}
