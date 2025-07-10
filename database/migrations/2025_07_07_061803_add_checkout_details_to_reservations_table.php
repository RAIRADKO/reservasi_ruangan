<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->timestamp('checked_out_at')->nullable()->after('rejection_reason');
        });
        DB::statement("ALTER TABLE reservations MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'canceled', 'completed') NOT NULL DEFAULT 'pending'");
    }


    public function down(): void
    {
        DB::statement("ALTER TABLE reservations MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'canceled') NOT NULL DEFAULT 'pending'");
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('checked_out_at');
        });
    }
};