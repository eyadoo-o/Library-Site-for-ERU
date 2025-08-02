<?php

namespace App\Providers;

use App\Models\Event;
use App\Policies\EventPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Event::class => EventPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define a gate for checking if user is an admin
        Gate::define('admin', function ($user) {
            return $user->is_admin;
        });
    }
}
