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
                ->nullOnDelete();
            $table->string('full_name');
            $table->string('phone_number');
            $table->string('email');
            $table->string('subject');
            $table->text('comment');
            $table->boolean('is_public')->default(false);
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
