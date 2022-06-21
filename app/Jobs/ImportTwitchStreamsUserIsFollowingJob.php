<?php

namespace App\Jobs;

use App\Actions\UpdateOrCreateStreamFromApiData;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use romanzipp\Twitch\Facades\Twitch;

class ImportTwitchStreamsUserIsFollowingJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function uniqueId()
    {
        return $this->user->id;
    }

    public function handle()
    {
        if (!$this->user->twitch_token) {
            return;
        }

        $streamIds = [];
        do {
            $result = Twitch::withToken($this->user->twitch_token)->getFollowedStreams([
                'user_id' => $this->user->twitch_id,
            ], isset($result) ? $result->next() : null);

            if ($result->getStatus() === 401) {
                User::find($this->user->id)->update([
                    'twitch_token' => null,
                ]);

                return;
            }

            if (empty($result->data())) {
                break;
            }

            foreach ($result->data() as $stream) {
                $streamIds[] = $stream->id;

                UpdateOrCreateStreamFromApiData::execute($stream);
            }
        } while ($result->hasMoreResults());

        $this->user->followedStreams()->sync($streamIds);
        $this->user->lastImport('followedStreams', true);
        if (config('services.twitch.shuffle_streams')) {
            // As it shuffles data, it will change the attributes like title and the return would be weird
            cache()->tags('streams')->flush();
        }
    }
}
