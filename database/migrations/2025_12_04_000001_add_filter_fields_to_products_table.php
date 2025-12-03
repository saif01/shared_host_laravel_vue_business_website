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
        Schema::table('products', function (Blueprint $table) {
            // Brand field for filtering
            $table->string('brand')->nullable()->after('sku');
            
            // Availability status - enum for better performance
            $table->enum('availability', ['in_stock', 'out_of_stock', 'pre_order', 'coming_soon'])
                ->default('in_stock')
                ->after('stock');
            
            // Rating field (0-5 stars)
            $table->decimal('rating', 3, 2)->default(0)->after('availability');
            $table->integer('rating_count')->default(0)->after('rating');
            
            // Features as JSON array
            $table->json('features')->nullable()->after('specifications');
            
            // Discount fields
            $table->decimal('discount_percent', 5, 2)->default(0)->after('price');
            $table->decimal('discounted_price', 10, 2)->nullable()->after('discount_percent');
            
            // Add index for better query performance
            $table->index('brand');
            $table->index('availability');
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['brand']);
            $table->dropIndex(['availability']);
            $table->dropIndex(['rating']);
            
            $table->dropColumn([
                'brand',
                'availability',
                'rating',
                'rating_count',
                'features',
                'discount_percent',
                'discounted_price',
            ]);
        });
    }
};

