<?php

namespace App\Providers;

use App\Models\Action;
use App\Models\Cafe;
use App\Models\Company;
use App\Models\User;
use App\Policies\ActionPolicy;
use App\Policies\CafePolicy;
use App\Policies\CompanyPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Cafe::class => CafePolicy::class,
        Action::class => ActionPolicy::class,
        Company::class => CompanyPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
    }
}
