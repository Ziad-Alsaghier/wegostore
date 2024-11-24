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
            $table->string('name',255);
            $table->text('title')->nullable();
            $table->enum('fixed',[0,1])->default(1);
            $table->integer('limet_store')->nullable();
            $table->longText('image')->nullable();
            $table->longText('description');
            $table->float('setup_fees');
            $table->enum('app',[0,1]); // Have Application or No
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
