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
            $table->enum('promo_status', ['fixed', 'unlimited']);
            $table->enum('calculation_method',allowed: ['percentage','amount']);
            $table->integer('usage')->nullable();
            $table->integer('user_usage')->nullable();
            $table->enum('user_type',['first_usage','renueve']);
            $table->date('start_date');
            $table->date('end_date');
            $table->float('amount')->nullable();
            $table->boolean('quarterly')->nullable();
            $table->boolean('semi_annual')->nullable();
            $table->boolean('yearly')->nullable();
            $table->boolean('monthly')->nullable();
            $table->enum('promo_type', ['plan', 'extra', 'domain']);
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
