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
        // Add verified_purchase to reviews table if not exists
        if (!Schema::hasColumn('reviews', 'verified_purchase')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->boolean('verified_purchase')->default(false)->after('rating');
            });
        }

        // Add helpful_count to reviews table if not exists
        if (!Schema::hasColumn('reviews', 'helpful_count')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->integer('helpful_count')->default(0)->after('verified_purchase');
            });
        }

        // Add average_rating to products table if not exists
        if (!Schema::hasColumn('products', 'average_rating')) {
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('average_rating', 3, 2)->default(0)->after('quantity');
                $table->integer('reviews_count')->default(0)->after('average_rating');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['verified_purchase', 'helpful_count']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['average_rating', 'reviews_count']);
        });
    }
};
