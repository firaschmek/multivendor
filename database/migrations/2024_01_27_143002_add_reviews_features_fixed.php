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
        // Add review-related columns to products table
        Schema::table('products', function (Blueprint $table) {
            // Check if columns don't already exist before adding
            if (!Schema::hasColumn('products', 'average_rating')) {
                $table->decimal('average_rating', 3, 2)->default(0);
            }
            if (!Schema::hasColumn('products', 'reviews_count')) {
                $table->unsignedInteger('reviews_count')->default(0);
            }
        });

        // Add review-related columns to reviews table if it exists
        if (Schema::hasTable('reviews')) {
            Schema::table('reviews', function (Blueprint $table) {
                if (!Schema::hasColumn('reviews', 'verified_purchase')) {
                    $table->boolean('verified_purchase')->default(false);
                }
                if (!Schema::hasColumn('reviews', 'helpful_count')) {
                    $table->unsignedInteger('helpful_count')->default(0);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('reviews')) {
            Schema::table('reviews', function (Blueprint $table) {
                if (Schema::hasColumn('reviews', 'verified_purchase')) {
                    $table->dropColumn('verified_purchase');
                }
                if (Schema::hasColumn('reviews', 'helpful_count')) {
                    $table->dropColumn('helpful_count');
                }
            });
        }

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'average_rating')) {
                $table->dropColumn('average_rating');
            }
            if (Schema::hasColumn('products', 'reviews_count')) {
                $table->dropColumn('reviews_count');
            }
        });
    }
};
