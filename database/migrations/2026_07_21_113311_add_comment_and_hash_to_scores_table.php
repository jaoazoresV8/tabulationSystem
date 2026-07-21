<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->text('comment')->nullable()->after('score');
            $table->string('ballot_hash')->nullable()->after('is_final');
            $table->integer('change_count')->default(0)->after('ballot_hash');
        });
    }

    public function down(): void
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->dropColumn(['comment', 'ballot_hash', 'change_count']);
        });
    }
};
