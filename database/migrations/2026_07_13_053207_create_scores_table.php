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
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('judge_id')->constrained()->onDelete('cascade');
            $table->foreignId('contestant_id')->constrained()->onDelete('cascade');
            $table->foreignId('criterion_id')->constrained()->onDelete('cascade');
            $table->foreignId('exposure_id')->constrained()->onDelete('cascade');
            $table->decimal('score', 8, 2);
            $table->boolean('is_final')->default(false);
            $table->timestamps();

            $table->unique(['judge_id', 'contestant_id', 'criterion_id', 'exposure_id'], 'unique_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
