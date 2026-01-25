<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('shop_name');
            $table->string('shop_name_ar')->nullable();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('description_ar')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            
            // Contact Information
            $table->string('phone');
            $table->string('email');
            $table->text('address')->nullable();
            $table->text('address_ar')->nullable();
            
            // Business Information
            $table->string('tax_number')->nullable();
            $table->string('business_license')->nullable();
            
            // Commission & Financial
            $table->decimal('commission_rate', 5, 2)->default(10.00); // percentage
            $table->decimal('balance', 10, 2)->default(0);
            
            // Status
            $table->enum('status', ['pending', 'approved', 'rejected', 'suspended'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'approved_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
