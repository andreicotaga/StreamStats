<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class TwitchOauthCallbackController extends Controller
{
    /**
     * @throws Exception
     */
    public function __invoke()
    {
        $twitchUser = Socialite::driver('twitch')->user();

        $user = User::where('twitch_id', $twitchUser->id)->first();

        try {
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
        } catch (QueryException $e) {
            throw new Exception('Please verify your twitch account email address.', $e->getMessage());
        }

        auth()->login($user);

        return redirect()->route('dashboard');
    }
}
