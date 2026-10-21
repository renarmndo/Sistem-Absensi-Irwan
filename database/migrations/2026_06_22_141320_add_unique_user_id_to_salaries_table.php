<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $keepIds = DB::select('SELECT MAX(id) as keep_id FROM salaries GROUP BY user_id');

        if ($keepIds !== []) {
            $ids = array_map(fn ($row) => $row->keep_id, $keepIds);
            DB::table('salaries')->whereNotIn('id', $ids)->delete();
        }

        Schema::table('salaries', function (Blueprint $table) {
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('salaries', function (Blueprint $table) {
            $table->dropUnique(['user_id']);
        });
    }
};
