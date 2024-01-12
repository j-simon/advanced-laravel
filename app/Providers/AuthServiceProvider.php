<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;

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
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
        // 1. user zu resource unter einem begriffe der zu kontrolierenden tätigkeit anlegen
        // toggle zu post resource
        Gate::define('toggle-post', function (\App\Models\User $user, \App\Models\Post $post) {
            return $post->user->is($user); //return $user->id === $post->user_id;
        });
    }
}
