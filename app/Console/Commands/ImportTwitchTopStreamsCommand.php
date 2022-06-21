<?php

namespace App\Console\Commands;

use App\Jobs\ImportTwitchTopLiveStreamsJob;
use Exception;
use Illuminate\Console\Command;

class ImportTwitchTopStreamsCommand extends Command
{
    protected $signature = 'twitch:import-top-streams {--quantity=1000 : Hot many streams to import}';

    protected $description = 'Import Twitch live top streams';

    public function handle()
    {
        $quantity = intval($this->option('quantity'));

        $this->info("Starting import $quantity Streams...");

        try {
            ImportTwitchTopLiveStreamsJob::dispatchSync($quantity);
            $this->info('Imported successfully');
        } catch (Exception $e) {
            $this->error('Sorry, fail on import data.');

            throw $e;
        }
    }
}
