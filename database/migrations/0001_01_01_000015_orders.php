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
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); 
            $table->integer('order_number')->unique()->default(999);  // Default value
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('extra_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();    
            $table->foreignId('payment_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();         
            $table->foreignId('plan_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('package', ['1', '3', '6', 'yearly'])->nullable();
            $table->date('expire_date')->nullable();
            $table->foreignId('domain_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->enum('price_cycle',['quarterly','semi_annual','yearly','monthly']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
