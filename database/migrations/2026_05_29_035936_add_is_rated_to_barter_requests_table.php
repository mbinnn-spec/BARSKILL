<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barter_requests', function (Blueprint $table) {
            $table->boolean('is_rated')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barter_requests', function (Blueprint $table) {
            $table->dropColumn('is_rated');
        });
    }
};
