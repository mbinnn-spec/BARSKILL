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
    $table->foreignId('from_user_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('to_user_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('skill_id')->constrained()->cascadeOnDelete();

    $table->date('date');
    $table->time('time');
    $table->integer('duration'); // menit
    $table->text('note')->nullable();

    $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');

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
