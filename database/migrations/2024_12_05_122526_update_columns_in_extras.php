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
        Schema::table('extras', function (Blueprint $table) {
            //
                   $table->dropColumn('semi_annual');
                   $table->float('semi_annual')->nullable()->after('quarterly');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('extras', function (Blueprint $table) {
            //
                   $table->dropColumn('semi_annual');
                   $table->float('semi_annual')->nullable();
        });
    }
};
