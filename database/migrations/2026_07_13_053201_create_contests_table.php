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
        Schema::create('contests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['single', 'double', 'group'])->default('single');
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->string('tabulator_name')->nullable();
            $table->enum('status', ['pending', 'active', 'completed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contests');
    }
};
