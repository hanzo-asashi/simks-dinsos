<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'Spatie\Permission\Models\Role' => 'App\Policies\RolePolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Model::preventsAccessingMissingAttributes(!$this->app->environment('production'));
        Model::unguard();
        Model::preventsLazyLoading(!$this->app->environment('production'));
    }
}
