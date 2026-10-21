<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->index(['user_id', 'date']);
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->index(['user_id', 'period_month', 'period_year']);
        });

        Schema::table('leave_requests', function (Blueprint $table) {
            $table->index(['user_id', 'status']);
        });

        Schema::table('salaries', function (Blueprint $table) {
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'date']);
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'period_month', 'period_year']);
        });

        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
        });

        Schema::table('salaries', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });
    }
};
