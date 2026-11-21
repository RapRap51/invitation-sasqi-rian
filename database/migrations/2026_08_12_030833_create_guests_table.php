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
        
        Schema::create('guests', function (Blueprint $table) {
        $table->id();

        $table->string('name');
        $table->string('phone')->nullable();

        $table->unsignedInteger('invitation_count')->default(1);

        $table->string('unique_code', 50)->unique();
        $table->string('qr_token', 100)->unique();

        $table->enum('rsvp_status', [
        'pending',
        'confirmed',
        'declined'
    ])->default('pending');

        $table->enum('checkin_status', [
        'not_arrived',
        'arrived'
    ])->default('not_arrived');

        $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
