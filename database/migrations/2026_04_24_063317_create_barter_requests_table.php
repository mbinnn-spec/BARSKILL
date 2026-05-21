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
Schema::create('barter_requests', function (Blueprint $table) {
    $table->id();

    $table->foreignId('skill_id')->constrained()->onDelete('cascade');

    $table->string('requester_name');

    $table->date('session_date');

    $table->integer('duration');

    $table->text('notes')->nullable();

    $table->enum('status', [
        'menunggu',
        'disetujui',
        'ditolak'
    ])->default('menunggu');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barter_requests');
    }
};
