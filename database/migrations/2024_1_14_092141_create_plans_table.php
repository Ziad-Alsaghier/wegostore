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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->longText('name');
            $table->text('title');
            $table->date('duration');
            $table->enum('fixed',[0,1]);
            $table->integer('limet_store')->nullable();
            $table->longText('description');
            $table->float('setup_fees');
            $table->float('price_per_month');
            $table->float('price_per_year');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
