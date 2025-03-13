<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect to social provider
     */
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle social login callback
     */
    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();

            $user = User::updateOrCreate(
                ['email' => $socialUser->getEmail()],
                [
                    'name' => $socialUser->getName(),
                    'password' => bcrypt(uniqid()),
                    $provider . '_id' => $socialUser->getId(),
                ]
            );

            // Check if the user is logging in for the first time
            $firstLogin = $user->wasRecentlyCreated;

            Auth::login($user);

            // Store the first login flag in the session
            session(['first_time' => $firstLogin]);
            return redirect('/products')->with('success', 'Login successful!');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Something went wrong!');
        }
    }
}
