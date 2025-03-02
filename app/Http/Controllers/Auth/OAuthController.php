<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\One\GoogleProvider;

class OAuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        if ($provider === 'google') {
            return Socialite::driver('google')
                ->scopes([
                    'openid',
                    'profile',
                    'email',
                    'https://www.googleapis.com/auth/user.addresses.read',
                    'https://www.googleapis.com/auth/user.phonenumbers.read',
                ])
                ->redirect();
        }

        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            $address = null;
            $phone = null;

            if (isset($socialUser->user['addresses']) && is_array($socialUser->user['addresses']) && count($socialUser->user['addresses']) > 0) {
                // Misalnya, ambil formattedValue dari address pertama
                $address = $socialUser->user['addresses'][0]['formattedValue'] ?? null;
            }
            if (isset($socialUser->user['phoneNumbers']) && is_array($socialUser->user['phoneNumbers']) && count($socialUser->user['phoneNumbers']) > 0) {
                // Ambil nilai nomor telepon dari phoneNumbers pertama
                $phone = $socialUser->user['phoneNumbers'][0]['value'] ?? null;
            }

            $user = User::where('provider', $provider)
                ->where('provider_id', $socialUser->getId())
                ->first();

            if (!$user) {
                $user = User::where('email', $socialUser->getEmail())->first();

                if ($user) {
                    $user->update([
                        'provider'    => $provider,
                        'provider_id' => $socialUser->getId(),
                        'avatar'      => $socialUser->getAvatar(),
                        'alamat'      => $address,
                        'no_hp'       => $phone,
                    ]);
                } else {
                    $user = User::create([
                        'name'        => $socialUser->getName(),
                        'email'       => $socialUser->getEmail(),
                        'password'    => Hash::make(rand(100000, 999999)), // Random password
                        'provider'    => $provider,
                        'provider_id' => $socialUser->getId(),
                        'avatar'      => $socialUser->getAvatar(),
                        'alamat'      => $address,
                        'no_hp'       => $phone,
                        'role'        => 'user',
                    ]);
                }
            }

            // Login user secara session-based
            Auth::login($user);

            // Redirect langsung ke dashboard
            return redirect('/dashboard');
        } catch (\Exception $e) {
            // Jika error, redirect ke halaman error atau kembali ke halaman login dengan pesan error
            return redirect('/')->with('error', $e->getMessage());
        }
    }
}
