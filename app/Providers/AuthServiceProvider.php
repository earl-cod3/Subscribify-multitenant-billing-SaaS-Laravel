<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

// ✅ Models
use App\Models\Tag;
use App\Models\User;
use App\Models\Item;
use App\Models\Role;
use App\Models\Category;

// ✅ Policies
use App\Policies\TagPolicy;
use App\Policies\UserPolicy;
use App\Policies\ItemPolicy;
use App\Policies\RolePolicy;
use App\Policies\CategoryPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Tag::class      => TagPolicy::class,
        User::class     => UserPolicy::class,
        Item::class     => ItemPolicy::class,
        Role::class     => RolePolicy::class,
        Category::class => CategoryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // === Existing app gates (map to your UserPolicy methods) ===
        Gate::define('manage-items', [UserPolicy::class, 'manageItems']);
        Gate::define('manage-users', [UserPolicy::class, 'manageUsers']);

        // === New: Tenant-aware billing gate (OWNER/ADMIN only) ===
        Gate::define('manage-billing', function ($user) {
            // Ensure helper exists and tenant is bound by middleware
            if (!function_exists('tenant') || !tenant()) {
                return false;
            }
            $role = tenant()->users()
                ->where('users.id', $user->id)
                ->value('role');

            return in_array($role, ['OWNER', 'ADMIN'], true);
        });
    }
}
