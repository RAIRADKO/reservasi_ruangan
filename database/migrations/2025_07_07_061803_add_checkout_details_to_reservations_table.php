<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Add a timestamp for when the checkout occurs.
            $table->timestamp('checked_out_at')->nullable()->after('rejection_reason');
        });

        // Modify the status column to include 'completed'.
        // Note: The specific command can vary by database (this is for MySQL).
        DB::statement("ALTER TABLE reservations MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'canceled', 'completed') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert status to its original definition
        DB::statement("ALTER TABLE reservations MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'canceled') NOT NULL DEFAULT 'pending'");
        
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('checked_out_at');
        });
    }
};