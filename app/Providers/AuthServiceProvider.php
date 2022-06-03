<?php

namespace App\Providers;

use App\Policies\CountryPolicy;
use App\Policies\DoctorPolicy;
use App\Policies\HospitalPolicy;
use App\Policies\OrganizationPolicy;
use App\Policies\PatientPolicy;
use Carbon\Carbon;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
//         'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\CountryPolicy' => CountryPolicy::class,
        'App\Models\OrganizationPolicy' => OrganizationPolicy::class,
        'App\Models\HospitalPolicy' => HospitalPolicy::class,
        'App\Models\DoctorPolicy' => DoctorPolicy::class,
        'App\Models\PatientPolicy' => PatientPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function () {
            if (is_super_admin()) {
                return true;
            }
        });

        Passport::routes();

        Passport::tokensExpireIn(Carbon::now()->addDays(15));

        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));

//        Passport::loadKeysFrom(__DIR__.'/../secrets/oauth');
    }
}
