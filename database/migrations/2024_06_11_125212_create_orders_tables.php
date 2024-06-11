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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable()->index();
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
        });

        Schema::create('order_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->index(); 
            $table->unsignedBigInteger('user_id')->index(); 
            $table->unsignedBigInteger('customer_id')->index();
            $table->text('url');
            $table->string('type');
            $table->string('caption')->nullable(); 
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders');
          });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orders_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('invoice_number')->unique();
            $table->decimal('sub_total_amount', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('advance_pay_amount', 10, 2);
            $table->decimal('balance_amount', 10, 2);
            $table->enum('payment_status', ['pending', 'paid', 'partially_paid', 'late', 'overdue'])->default('pending');
            $table->enum('payment_method', ['cash', 'credit_card', 'bank_transfer', 'paypal' , 'UPI', 'other'])->nullable();
            $table->text('payment_history')->nullable();
            $table->text('terms_and_conditions')->nullable();
            $table->timestamp('created_date')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->timestamps();
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id')->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->decimal('discount_amount', 10, 2)->nullable();
            $table->decimal('total', 10, 2);
            $table->timestamps();
            $table->foreign('invoice_id')->references('id')->on('invoices');
          });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_images');
        Schema::dropIfExists('customers');
    }
};
