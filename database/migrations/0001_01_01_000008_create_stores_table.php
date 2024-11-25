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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('plan_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('store_name');
            $table->string('link_store')->nullable();
            $table->string('instgram_link')->nullable();
            $table->string('facebook_link')->nullable();
            $table->string('logo')->nullable();
            $table->string('phone')->nullable();
            $table->string('link_cbanal')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->foreignId('activities_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('deleted')->default(0);
            $table->enum('status',allowed: ['pending','approved','rejected'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
