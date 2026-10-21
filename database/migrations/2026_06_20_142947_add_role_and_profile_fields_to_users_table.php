<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->after('id')->constrained()->cascadeOnDelete();
            $table->string('employee_id', 30)->nullable()->unique()->after('role_id');
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('position', 100)->nullable()->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
            $table->dropColumn(['employee_id', 'phone', 'position']);
        });
    }
};
