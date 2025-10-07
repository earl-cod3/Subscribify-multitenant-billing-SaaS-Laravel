<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! $this->indexExists('roles', 'roles_name_unique')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->unique('name'); // creates roles_name_unique
            });
        }
    }

    public function down(): void
    {
        if ($this->indexExists('roles', 'roles_name_unique')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropUnique('roles_name_unique');
            });
        }
    }

    private function indexExists(string $table, string $index): bool
    {
        $rows = DB::select('SHOW INDEX FROM `'.$table.'` WHERE Key_name = ?', [$index]);
        return !empty($rows);
    }
};
