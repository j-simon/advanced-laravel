<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\SocialAuth;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    // entweder so, oder im constructor
     protected $maxAttempts = 3; // Default is 3
     protected $decayMinutes = 10; // Default is 600
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        //$this->maxAttempts = '2';
        //$this->decayMinutes = '30'; // 1800 Sekunden
    }

    
    // von Laravel zu dem Socialite Provider
    public function redirectToProvider($provider) {
        
        return Socialite::driver($provider)->redirect();
        
    }
  

    // von Socialite Provider kommt das OK oder das nicht OK zurück zu uns(Laravel)
    public function handleProviderCallback($provider){
        $oauthUser = Socialite::driver($provider)->user();

        // integration in Laravel
        $oauthUser = $this->findOrCreateUser($oauthUser, $provider);
    
        // aktiv login!
        Auth::login($oauthUser, true);
    
        return redirect($this->redirectTo);
    }


    public function findOrCreateUser($oauthUser, $provider)
    {
        $existingOAuth = SocialAuth::where('provider_name', $provider)
            ->where('provider_id', $oauthUser->getId())
            ->first();
    
        if ($existingOAuth) {
            // Wiederholung bereits angemeldet
            return $existingOAuth->user;
        } else {
            // 1. Anlage
            $user = User::whereEmail($oauthUser->getEmail())->first();
    
            if (!$user) {
                $user = User::create([
                    'email' => $oauthUser->getEmail(),
                    'name'  => $oauthUser->getName(),
                ]);
            }
    
            $user->oauth()->create([
                'provider_id'   => $oauthUser->getId(),
                'provider_name' => $provider,
            ]);
    
            return $user;
        }
    }
}
