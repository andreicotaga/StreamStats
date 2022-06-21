<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;

class TwitchOauthRedirectController extends Controller
{
    public function __invoke()
    {
        return Socialite::driver('twitch')
            ->scopes(config('services.twitch.scopes'))
            ->redirect();
    }
}
