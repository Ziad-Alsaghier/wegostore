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
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('title');
            $table->enum('calculation_method',allowed: ['percentage','amount']);
            $table->integer('usage');
            $table->integer('user_usage');
            $table->enum('user_type',['first_usage','renueve']);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('quarterly')->nullable();
            $table->boolean('semi-annual')->nullable();
            $table->boolean('yearly')->nullable();
            $table->boolean('monthly')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_codes');
    }
};
