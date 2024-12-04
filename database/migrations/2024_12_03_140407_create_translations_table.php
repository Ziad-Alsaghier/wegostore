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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
              $table->string('locale')->index(); // e.g., 'en', 'ar'
              $table->morphs('translatable'); // Adds `translatable_type` and `translatable_id`
              $table->string('key'); // Translation key
              $table->text('value'); // Translated  value
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
