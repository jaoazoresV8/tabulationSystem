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
        Schema::create('exposures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contest_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['preliminary', 'final'])->default('preliminary');
            $table->integer('order')->default(1);
            $table->decimal('weight', 5, 2)->default(100.00);
            $table->integer('top_n')->nullable();
            $table->boolean('carry_over')->default(false);
            $table->enum('status', ['locked', 'active', 'completed'])->default('locked');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exposures');
    }
};
