<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('order_item_id')->nullable()->constrained()->onDelete('set null');
            
            $table->enum('type', ['sale', 'commission', 'withdrawal', 'refund', 'adjustment'])->default('sale');
            $table->decimal('amount', 10, 2);
            $table->decimal('balance_after', 10, 2);
            
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            
            $table->timestamps();
            
            $table->index(['vendor_id', 'type', 'status']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_transactions');
    }
};
