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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name', 180);
            $table->string('slug', 200)->unique();
            $table->string('sku', 120)->nullable()->unique();

            $table->string('short_description', 500)->nullable();
            $table->longText('description')->nullable();

            $table->decimal('price', 12, 2)->nullable();
            $table->decimal('compare_at_price', 12, 2)->nullable();

            $table->boolean('track_stock')->default(false);
            $table->unsignedInteger('stock')->nullable();
            $table->boolean('allow_backorder')->default(false);

            $table->enum('status', [
                'draft',
                'active',
                'inactive',
            ])->default('draft');

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_quotable')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->string('seo_title', 180)->nullable();
            $table->string('seo_description', 320)->nullable();
            $table->json('seo_keywords')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'is_featured']);
            $table->index(['is_quotable', 'status']);
            $table->index(['track_stock', 'stock']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
