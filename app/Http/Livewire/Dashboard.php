<?php

namespace App\Http\Livewire;

use App\Jobs\ImportTwitchStreamsUserIsFollowingJob;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public User $user;

    public function mount(): void
    {
        $this->user = auth()->user()->load('followedStreams');
    }

    public function updateFollowedStreams()
    {
        if ($this->user->lastImport('followedStreams') < now()->subMinutes(15)) {
            ImportTwitchStreamsUserIsFollowingJob::dispatchSync($this->user);
            $this->user->refresh();
            $this->emit('updatedFollowedStreams');
        }
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
