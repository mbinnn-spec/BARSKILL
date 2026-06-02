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
        // Using raw SQL because ENUM updates are tricky in Laravel migrations across different DB engines
        DB::statement("ALTER TABLE barter_requests MODIFY COLUMN status ENUM('menunggu', 'disetujui', 'ditolak', 'berjalan', 'selesai') DEFAULT 'menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE barter_requests MODIFY COLUMN status ENUM('menunggu', 'disetujui', 'ditolak') DEFAULT 'menunggu'");
    }
};
