<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('office_settings', function (Blueprint $table) {
            $table->unsignedTinyInteger('effective_work_days')->default(20)->after('work_end_time')
                ->comment('Hari kerja efektif per bulan untuk perhitungan payroll');
        });
    }

    public function down(): void
    {
        Schema::table('office_settings', function (Blueprint $table) {
            $table->dropColumn('effective_work_days');
        });
    }
};
