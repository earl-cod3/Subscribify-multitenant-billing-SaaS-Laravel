<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Billable; // keep even if not using Stripe yet

class Tenant extends Model {
  use Billable;

  protected $fillable = ['name','slug','owner_user_id'];
  public function users() {
    return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps();
  }
  public function owner() { return $this->belongsTo(User::class, 'owner_user_id'); }

  public function hasFeature(string $slug): bool {
    // replace this with a real plan_code later (from subscription or column)
    $plan = $this->plan_code ?? 'free';
    return \DB::table('plan_features')
      ->join('features','features.id','=','plan_features.feature_id')
      ->where('plan_features.plan_code',$plan)
      ->where('features.slug',$slug)->exists();
  }
}
