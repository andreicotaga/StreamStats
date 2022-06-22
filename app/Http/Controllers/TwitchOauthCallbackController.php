<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class TwitchOauthCallbackController extends Controller
{
    public function __invoke()
    {
        $twitchUser = Socialite::driver('twitch')->user();

        $user = User::where('twitch_id', $twitchUser->id)->first();

        if (!$user) {
            $user = User::create([
                'twitch_id'    => $twitchUser->id,
                'twitch_token' => $twitchUser->token,
                'name'         => $twitchUser->name,
                'email'        => $twitchUser->email,
                'password'     => Str::random(),
            ]);
        } else {
            $user->update([
                'twitch_token' => $twitchUser->token,
                'name'         => $twitchUser->name,
                'email'        => $twitchUser->email,
            ]);
        }

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
