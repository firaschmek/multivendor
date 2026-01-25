<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->foreignId('vendor_id')->constrained()->onDelete('restrict');
            
            // Product Snapshot (at time of order)
            $table->string('product_name');
            $table->string('product_name_ar')->nullable();
            $table->string('product_sku');
            
            // Pricing
            $table->decimal('price', 10, 2); // Price at time of order
            $table->integer('quantity');
            $table->decimal('subtotal', 10, 2);
            
            // Commission
            $table->decimal('commission_rate', 5, 2); // Percentage at time of order
            $table->decimal('commission_amount', 10, 2);
            $table->decimal('vendor_amount', 10, 2); // Amount vendor receives after commission
            
            // Status per item (vendor can mark individual items)
            $table->enum('status', [
                'pending',
                'confirmed',
                'processing',
                'shipped',
                'delivered',
                'cancelled',
                'refunded'
            ])->default('pending');
            
            $table->timestamps();
            
            $table->index(['order_id', 'vendor_id']);
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
