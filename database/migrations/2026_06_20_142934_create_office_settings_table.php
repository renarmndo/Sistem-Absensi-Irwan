<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('office_settings', function (Blueprint $table) {
            $table->id();
            $table->string('office_name', 100);
            $table->string('latitude', 20);
            $table->string('longitude', 20);
            $table->integer('radius_meter')->default(50);
            $table->time('work_start_time');
            $table->time('work_end_time');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('office_settings');
    }
};
