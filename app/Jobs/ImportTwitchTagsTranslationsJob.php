<?php

namespace App\Jobs;

use App\Models\Tag;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use romanzipp\Twitch\Facades\Twitch;
use Throwable;

class ImportTwitchTagsTranslationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array|string[]
     */
    private array $tagUuids;
    /**
     * @var array|string[]
     */
    private array $localization;

    public function __construct(array $tagUuids, array $localization = ['en-us'])
    {
        $this->tagUuids     = $tagUuids;
        $this->localization = $localization;
    }

    /**
     * @throws Throwable
     */
    public function handle()
    {
        DB::transaction(function () {
            do {
                $result = Twitch::getAllStreamTags([
                    'first'  => 100,
                    'tag_id' => $this->tagUuids,
                ], isset($result) ? $result->next() : null);

                foreach ($result->data() as $tag) {
                    $tagModel = Tag::query()->firstWhere('tag_uuid', '=', $tag->tag_id);

                    if (!$tagModel) {
                        continue;
                    }

                    foreach ($this->localization as $localization) {
                        if (!isset($tag->localization_names->{$localization})) {
                            continue;
                        }

                        $tagModel->descriptions()->updateOrCreate([
                            'localization' => $localization,
                        ], [
                            'name'        => $tag->localization_names->{$localization},
                            'description' => $tag->localization_descriptions->{$localization},
                        ]);
                    }
                }
            } while ($result->hasMoreResults());
        });
    }
}
