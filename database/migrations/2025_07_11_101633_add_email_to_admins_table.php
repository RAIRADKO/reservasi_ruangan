<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            // Tambahkan kolom email terlebih dahulu tanpa constraint unique
            $table->string('email')->after('username')->nullable();
        });

        // Beri nilai unik sementara untuk data admin yang sudah ada
        if (DB::getDriverName() !== 'sqlite') {
            $admins = Admin::whereNull('email')->orWhere('email', '')->get();
            foreach ($admins as $admin) {
                $admin->email = 'admin' . $admin->id . '@example.com';
                $admin->save();
            }
        }

        // Sekarang, ubah kolom untuk menjadi not nullable dan tambahkan constraint unique
        Schema::table('admins', function (Blueprint $table) {
            $table->string('email')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
};