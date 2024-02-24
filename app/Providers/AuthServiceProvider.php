<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\EmdPermission;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

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
        Gate::define('super_admin', function (User $user) {
            return $user->admin_level == User::ADMIN;
        });
        if (Schema::hasTable('emd_permissions')) {
            $permissions = EmdPermission::select("id", "key")->get();
            foreach ($permissions as $permission) {
                Gate::define($permission->key, function (User $user) use ($permission) {
                    return $user->admin_level == User::ADMIN ? true : $user->emd_permission->contains($permission->id);
                });
            }
        }
    }
}
