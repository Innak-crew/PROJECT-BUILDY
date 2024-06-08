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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('role');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('managers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->enum('role', ['manager'])->default('manager');
            $table->timestamps();
          });

        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); 
            $table->string('state');
            $table->string('city');
            $table->timestamps();
          });

        Schema::create('branch_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('branch_name');
            $table->string('address_1');
            $table->string('address_2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('postal_code');
            $table->timestamps();
          });

        Schema::create('schedule', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamp('start');
            $table->timestamp('end')->nullable();
            $table->string('level')->nullable();
            $table->enum('status', ['scheduled', 'canceled'])->default('scheduled');
            $table->enum('visibility', ['public', 'private', 'admin', 'manager'])->default('private');
            $table->boolean('is_editable')->default(true);
            $table->unsignedBigInteger('updater_admin_or_manager_id')->nullable(); 
            $table->timestamps();
        });

        // Table to store notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('type');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->string('title')->nullable();
            $table->json('data')->nullable();
            $table->string('link')->nullable();
            $table->string('icon')->nullable();
            $table->string('attachment')->nullable();
            $table->timestamps();
        });

        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('reminder_time');
            $table->boolean('is_completed')->default(false);  
            $table->integer('priority')->nullable();          
            $table->string('category')->nullable();
            $table->string('repeat')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
        

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('customer_id');
            $table->enum('status', ['ongoing', 'completed'])->default('ongoing');
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('invoice_number')->unique();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('advance_pay_amount', 10, 2);
            $table->decimal('balance_amount', 10, 2);
            $table->enum('payment_status', ['pending', 'paid', 'partially_paid', 'late', 'overdue'])->default('pending');
            $table->enum('payment_method', ['cash', 'credit_card', 'bank_transfer', 'paypal' , 'UPI', 'other'])->nullable();
            $table->text('payment_history')->nullable();
            $table->timestamp('created_date')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->timestamps();
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->string('description');
            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity');
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('permissions'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('reminders');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('schedule');
        Schema::dropIfExists('branch_addresses');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('managers');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
    
};
