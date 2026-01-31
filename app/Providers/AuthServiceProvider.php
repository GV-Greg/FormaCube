<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
//         'App\Models' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isMaster', function($user) {
            return $user->role === 'master';
        });

        Gate::define('isSuperAdmin', function($user) {
            return $user->role === 'superAdmin';
        });

        Gate::define('isAdmin', function($user) {
            return $user->role === 'admin';
        });

        Gate::define('isFormateur', function($user) {
            return $user->role === 'formateur';
        });
    }
}
