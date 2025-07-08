<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('room_infos', function (Blueprint $table) {
            // Tambahkan kolom untuk menyimpan tautan survei, bisa null
            $table->string('survey_link')->nullable()->after('qr_code_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_infos', function (Blueprint $table) {
            $table->dropColumn('survey_link');
        });
    }
};