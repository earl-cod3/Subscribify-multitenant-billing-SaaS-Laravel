<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TenantSetupController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();

        // Create first org/tenant for this user
        $tenant = Tenant::create([
            'name'          => "{$user->name}'s Org",
            'slug'          => Str::slug($user->name . '-org-' . Str::random(5)),
            'owner_user_id' => $user->id,
        ]);

        // Attach membership with OWNER role
        $tenant->users()->syncWithoutDetaching([$user->id => ['role' => 'OWNER']]);

        return redirect()->route('tenant.dashboard', ['tenant' => $tenant->slug]);
    }
}
