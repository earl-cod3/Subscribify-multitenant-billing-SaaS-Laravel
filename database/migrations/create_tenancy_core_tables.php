<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('tenants', function (Blueprint $t) {
      $t->id();
      $t->string('name');
      $t->string('slug')->unique();
      $t->foreignId('owner_user_id')->constrained('users');
      // Stripe / Cashier columns (even if we hook later)
      $t->string('stripe_id')->nullable()->index();
      $t->string('pm_type')->nullable();
      $t->string('pm_last_four', 4)->nullable();
      $t->timestamp('trial_ends_at')->nullable();
      $t->timestamps();
    });

    Schema::create('tenant_user', function (Blueprint $t) {
      $t->id();
      $t->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
      $t->foreignId('user_id')->constrained('users')->cascadeOnDelete();
      $t->string('role')->default('USER'); // OWNER, ADMIN, STAFF, USER
      $t->timestamps();
      $t->unique(['tenant_id','user_id']);
    });

    Schema::create('features', function (Blueprint $t) {
      $t->id();
      $t->string('slug')->unique(); // e.g., reports, api
    });

    Schema::create('plan_features', function (Blueprint $t) {
      $t->id();
      $t->string('plan_code');      // free|pro|business
      $t->foreignId('feature_id')->constrained('features')->cascadeOnDelete();
      $t->unique(['plan_code','feature_id']);
    });

    Schema::create('audits_extra', function (Blueprint $t) {
      $t->id();
      $t->foreignId('tenant_id')->nullable()->constrained('tenants')->nullOnDelete();
      $t->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
      $t->string('action');
      $t->json('meta')->nullable();
      $t->timestamps();
      $t->index(['tenant_id','created_at']);
    });
  }
  public function down(): void {
    Schema::dropIfExists('audits_extra');
    Schema::dropIfExists('plan_features');
    Schema::dropIfExists('features');
    Schema::dropIfExists('tenant_user');
    Schema::dropIfExists('tenants');
  }
};
