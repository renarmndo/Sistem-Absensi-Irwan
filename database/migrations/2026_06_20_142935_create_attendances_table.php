<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();
            $table->string('lat_in', 20);
            $table->string('long_in', 20);
            $table->integer('distance_in')->comment('Jarak dari kantor saat absen masuk (meter)');
            $table->string('selfie_in')->comment('Nama file gambar selfie masuk');
            $table->string('lat_out', 20)->nullable();
            $table->string('long_out', 20)->nullable();
            $table->integer('distance_out')->nullable()->comment('Jarak dari kantor saat absen pulang (meter)');
            $table->string('selfie_out')->nullable()->comment('Nama file gambar selfie pulang');
            $table->enum('status', ['Hadir', 'Terlambat']);
            $table->timestamps();

            $table->unique(['user_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
