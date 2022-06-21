<div class="space-y-4" wire:init="updateFollowedStreams">
    <div class="space-y-4">
        <x-card title="Top Games">
            <livewire:top-games-by-viewer-table/>
        </x-card>

        <x-card title="Top Streams">
            <livewire:top-streams-by-viewers-table/>
        </x-card>

        <x-card title="Streams By Start Time">
            <livewire:streams-by-start-time-table/>
        </x-card>

        @if($this->user->lastImport('followedStreams') > now()->subMinutes(20))
            <x-card title="Top 1000 Streams you are Following">
                <livewire:top-following-streams-for-user-table :user="$user"/>
            </x-card>

            <x-card title="How Distance to be on Top 1000">
                <livewire:stream-distance-to-be-on-top-table :user="$user"/>
            </x-card>

            <x-card title="Your Shared Tags with Top 1000 Streams">
                <livewire:shared-tags-with-top-streams-table :user="$user"/>
            </x-card>
        @endif
    </div>
</div>
