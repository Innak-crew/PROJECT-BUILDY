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
        Schema::create('category_keys', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); 
            $table->string('general_key')->nullable(); 
            $table->timestamps();
        });

        Schema::create('designs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('category_id')->index();
            $table->unsignedBigInteger('category_key_id')->index();
            $table->string('image_url');
            $table->enum('type', ['Interior', 'Exterior', 'Both'])->default('Interior');
            $table->unsignedBigInteger('unit_id')->index();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('category_key_id')->references('id')->on('category_keys');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('designs');
        Schema::dropIfExists('category_keys');
    }
};
