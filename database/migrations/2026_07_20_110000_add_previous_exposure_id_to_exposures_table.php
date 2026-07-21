<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exposures', function (Blueprint $table) {
            $table->foreignId('previous_exposure_id')->nullable()->after('contest_id')->constrained('exposures')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('exposures', function (Blueprint $table) {
            $table->dropConstrainedForeignId('previous_exposure_id');
        });
    }
};
