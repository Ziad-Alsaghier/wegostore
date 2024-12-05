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
        Schema::create('welcome_offers', function (Blueprint $table) {
            $table->id();
            $table->string('ar_image')->nullable();
            $table->string('en_image')->nullable(); 
            $table->foreignId('plan_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('duration');
            $table->float('price');
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('welcome_offers');
    }
};
