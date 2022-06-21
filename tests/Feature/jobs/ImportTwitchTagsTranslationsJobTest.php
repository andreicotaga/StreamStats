<?php

namespace Tests\Feature\jobs;

use App\Jobs\ImportTwitchTagsTranslationsJob;
use App\Models\Stream;
use App\Models\Tag;
use App\Models\TagDescription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportTwitchTagsTranslationsJobTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_import_tag_descriptions()
    {
        // Can't test it in workflow without sharing sensitive data
        if (!config('twitch-api.client_id')) {
            $this->assertTrue(true);
            return;
        }

        $validTagUuids = [
            '6f70af0e-2ac0-4939-9ec9-31a770ea1ed3',
            '9166ad14-41f1-4b04-a3b8-c8eb838c6be6',
            'af40eaba-004c-4f85-b33f-e4af89e98d5a',
        ];

        Stream::factory()->create();

        $stream = Stream::query()->first();
        foreach ($validTagUuids as $tagUuid) {
            $stream->tags()->create([
                'tag_uuid' => $tagUuid,
            ]);
        }
        $stream->load('tags')->refresh();

        $job = new ImportTwitchTagsTranslationsJob($stream->tags->pluck('tag_uuid')->toArray());
        $job->handle();

        $this->assertEquals(3, Tag::count());
        $this->assertEquals(3, TagDescription::count());
    }
}
