<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use App\Policies\UserPolicy;
use Illuminate\Auth\Access\Response;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        //
        // 1. user zu resource unter einem begriffe der zu kontrolierenden tätigkeit anlegen
        // toggle zu post resource
        Gate::define('toggle-post', function (\App\Models\User $user, \App\Models\Post $post) {
            return $post->user->is($user); //return $user->id === $post->user_id;
        });

        // uebung_11:
        Gate::before(function ($user, $ability) {
            if ($user->abilities()->contains($ability)) {
                return true;
            }
        });

        Gate::define('delete-user', function (\App\Models\User $auth, $user) {
            //echo $auth->id,$user->id;
            //dump ($auth->abilities());
            return $auth->id === $user->id || $auth->abilities()->contains('delete-user') 
            ? Response::allow()
            : Response::deny('You cannot delete this User because its not yours');
        });
    }
}
