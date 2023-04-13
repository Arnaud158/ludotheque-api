<?php

namespace App\Providers;

use App\Models\Adherent;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
//use Illuminate\Auth\Access\Gate;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('same-user', function (Adherent $user, Adherent $otherUser) {
            return $user->id == $otherUser->id || $user->roles()->where('nom', 'administrateur')->exists();
        });

        Gate::define('same-user-or-mod', function (Adherent $user, Adherent $otherUser) {
            return $user->id == $otherUser->id || $user->roles()->where('nom', 'commentaire-moderateur')->exists();
        });
    }
}
