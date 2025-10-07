<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BillingController extends Controller
{
    public function index(Request $request, string $tenant)
    {
        // Basic authz: only OWNER/ADMIN should view billing
        Gate::authorize('manage-billing');

        $t = tenant();
        return view()->exists('pages.account.billing')
            ? view('pages.account.billing', ['tenant' => $t])
            : view('pages.account.billing-placeholder', ['tenant' => $t]); // create if needed
    }

    public function checkout(Request $request, string $tenant)
    {
        Gate::authorize('manage-billing');

        $request->validate([
            'price_id' => ['required', 'string'], // Stripe price_XXX
        ]);

        $t = tenant();

        // If you haven’t configured Cashier yet, this will error; wire STRIPE env first.
        // ENV required: STRIPE_KEY, STRIPE_SECRET, STRIPE_WEBHOOK_SECRET, CASHIER_MODEL=App\Models\Tenant
        return $t->newSubscription('default', $request->string('price_id'))
            ->checkout([
                'success_url' => route('tenant.billing', ['tenant' => $t->slug]) . '?success=1',
                'cancel_url'  => route('tenant.billing', ['tenant' => $t->slug]) . '?canceled=1',
            ]);
    }
}
