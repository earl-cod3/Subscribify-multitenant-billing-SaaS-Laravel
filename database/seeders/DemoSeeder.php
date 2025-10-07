<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder {
  public function run(): void {
    $owner = User::firstOrCreate(
      ['email' => 'owner@example.com'],
      ['name' => 'Demo Owner','password' => bcrypt('password')]
    );
    $tenant = Tenant::firstOrCreate(
      ['slug' => 'demo-inc'],
      ['name' => 'Demo Inc','owner_user_id' => $owner->id]
    );
    $tenant->users()->syncWithoutDetaching([$owner->id => ['role' => 'OWNER']]);

    \DB::table('features')->upsert([
      ['slug' => 'reports'],
      ['slug' => 'api'],
      ['slug' => 'seats_5'],
    ], ['slug'], ['slug']);

    $id = fn($slug) => \DB::table('features')->where('slug',$slug)->value('id');
    \DB::table('plan_features')->upsert([
      ['plan_code'=>'free',     'feature_id'=>$id('seats_5')],
      ['plan_code'=>'pro',      'feature_id'=>$id('seats_5')],
      ['plan_code'=>'pro',      'feature_id'=>$id('reports')],
      ['plan_code'=>'business', 'feature_id'=>$id('reports')],
      ['plan_code'=>'business', 'feature_id'=>$id('api')],
    ], ['plan_code','feature_id'], ['plan_code','feature_id']);
  }
}
