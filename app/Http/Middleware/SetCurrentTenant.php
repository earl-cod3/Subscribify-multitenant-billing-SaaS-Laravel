<?php
namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;

class SetCurrentTenant {
  public function handle(Request $request, Closure $next) {
    $slug = $request->route('tenant');
    abort_unless($slug, 404);
    $tenant = Tenant::where('slug', $slug)->firstOrFail();

    if (!auth()->check() || ! $tenant->users()->where('users.id', auth()->id())->exists()) {
      abort(403);
    }
    app()->instance('currentTenant', $tenant);
    return $next($request);
  }
}
