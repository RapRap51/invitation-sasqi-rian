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
        Schema::create('checkins', function (Blueprint $table) {
        $table->id();

        $table->foreignId('guest_id')
        ->constrained('guests')
        ->cascadeOnDelete();

        $table->foreignId('checked_in_by')
        ->constrained('users')
        ->cascadeOnDelete();

        $table->timestamp('checked_in_at');

        $table->timestamps();

        $table->unique('guest_id');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkins');
    }
};
