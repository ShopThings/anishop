<?php

namespace App\Providers;

use App\Enums\Gates\RolesEnum;
use App\Models\FileManager;
use App\Models\User;
use App\Policies\FilePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        FileManager::class => FilePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Implicitly grant "Developer" role all permission checks using can()
        Gate::before(function (User $user, $ability) {
            return $user->hasAnyRole([RolesEnum::DEVELOPER->value]) ? true : null;
        });
    }
}
