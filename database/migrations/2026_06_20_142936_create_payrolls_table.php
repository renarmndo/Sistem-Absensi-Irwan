<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('period_month');
            $table->year('period_year');
            $table->integer('total_attendance')->default(0);
            $table->integer('total_absence_alfa')->default(0);
            $table->decimal('basic_salary', 15, 2);
            $table->decimal('allowance', 15, 2)->default(0);
            $table->decimal('cuts', 15, 2)->default(0)->comment('Total potongan akibat alfa');
            $table->decimal('take_home_pay', 15, 2)->comment('Gaji bersih akhir');
            $table->timestamps();

            $table->unique(['user_id', 'period_month', 'period_year'], 'payrolls_user_month_year_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
