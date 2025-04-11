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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')
                ->nullable()
                ->constrained('cars')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('username');
            $table->string('phone_number');
            $table->boolean('is_public')->default(false);
            $table->string('comment');
            $table->enum('status', ['pending', 'approved', 'rejected'])
                ->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
