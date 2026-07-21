<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('contests', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id')->unique();
        });

        // Fill existing records
        $contests = DB::table('contests')->get();
        foreach ($contests as $contest) {
            DB::table('contests')->where('id', $contest->id)->update(['uuid' => (string) Str::uuid()]);
        }

        Schema::table('contests', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contests', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
