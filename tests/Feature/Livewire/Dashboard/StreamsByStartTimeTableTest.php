<?php

namespace Tests\Feature\Livewire\Dashboard;

use App\Http\Livewire\StreamsByStartTimeTable;
use App\Models\Stream;
use Livewire\Livewire;
use Tests\Feature\AbstractFeatureTest;

class StreamsByStartTimeTableTest extends AbstractFeatureTest
{
    /** @test */
    public function it_should_have_livewire_component()
    {
        $this->get(route('dashboard'))
            ->assertSeeLivewire(StreamsByStartTimeTable::class);
    }

    /** @test */
    public function it_should_show_streams_grouped_hourly_order_by_start_at_and_display_streams_count()
    {
        Stream::factory()->create(['started_at' => '2020-01-01 12:50']);
        Stream::factory()->create(['started_at' => '2020-01-01 12:40']);
        Stream::factory()->create(['started_at' => '2020-01-01 12:30']);

        Stream::factory()->create(['started_at' => '2020-01-01 11:15']);
        Stream::factory()->create(['started_at' => '2020-01-01 11:05']);

        Stream::factory()->create(['started_at' => '2020-01-01 10:15']);
        Stream::factory()->create(['started_at' => '2020-01-01 10:20']);
        Stream::factory()->create(['started_at' => '2020-01-01 10:40']);
        Stream::factory()->create(['started_at' => '2020-01-01 10:50']);

        Livewire::test(StreamsByStartTimeTable::class)
            ->assertHasNoErrors()
            ->assertOk()
            ->assertSeeInOrder([
                '2020-01-01 12',
                3,
                '2020-01-01 11',
                2,
                '2020-01-01 10',
                4
            ]);
    }
}
