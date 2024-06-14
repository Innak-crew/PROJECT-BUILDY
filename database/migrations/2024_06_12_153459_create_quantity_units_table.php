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
        Schema::create('quantity_units', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('e.g., SQ.FT, EACH');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->string('dimensions')->nullable();
            $table->enum('type', ['Interior', 'Exterior', 'Both'])->default('Interior');
            $table->unsignedBigInteger('unit_id')->index();
            $table->decimal('rate_per', 10, 2);
            $table->timestamps();
            $table->foreign('unit_id')->references('id')->on('quantity_units');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('parent_id')->index()->nullable();
            $table->enum('type', ['Interior', 'Exterior', 'Both'])->default('Interior');
            $table->timestamps();
        
            $table->foreign('parent_id')->references('id')->on('categories');
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->enum('type', ['Interior', 'Exterior', 'Both'])->default('Interior');
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('customer_id')->index();
            $table->enum('status', ['ongoing', 'completed', 'cancelled'])->default('ongoing');
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->decimal('estimated_cost', 10, 2)->nullable();
            $table->decimal('deposit_received', 10, 2)->nullable();
            $table->timestamps();
        
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('customer_id')->references('id')->on('users');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedBigInteger('category_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->string('unit')->nullable();
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->decimal('discount_percentage', 5, 2)->default(0.00);
            $table->decimal('total', 10, 2);
            $table->timestamps();
        
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('product_id')->references('id')->on('products');
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('invoice_number')->unique();
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->decimal('discount_percentage', 5, 2)->default(0.00);
            $table->decimal('sub_total_amount', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('advance_pay_amount', 10, 2);
            $table->decimal('balance_amount', 10, 2);
            $table->enum('payment_status', ['pending', 'paid', 'partially_paid', 'late', 'overdue'])->default('pending');
            $table->enum('payment_method', ['cash', 'credit_card', 'bank_transfer', 'paypal', 'UPI', 'other'])->nullable();
            $table->text('payment_history')->nullable();
            $table->text('terms_and_conditions')->nullable();
            $table->timestamp('created_date')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->timestamps();
        
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('customer_id')->references('id')->on('customers');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quantity_units');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categorys');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('orders');
    }
};