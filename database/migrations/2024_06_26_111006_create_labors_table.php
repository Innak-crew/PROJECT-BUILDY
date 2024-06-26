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

        Schema::create('labor_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        
        Schema::create('labors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->date('date');
            $table->unsignedBigInteger('labor_category_id');
            $table->integer('number_of_labors');
            $table->decimal('per_labor_amount', 8, 2);
            $table->decimal('total_amount', 10, 2);
            $table->timestamps();
        
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->foreign('labor_category_id')->references('id')->on('labor_categories')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labors');
        Schema::dropIfExists('labor_categories');
    }
};
