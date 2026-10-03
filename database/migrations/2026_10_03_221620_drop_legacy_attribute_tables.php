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
        Schema::dropIfExists('product_attribute_values');
        Schema::dropIfExists('attribute_options');
        Schema::dropIfExists('attributes');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No revertiremos la estructura antigua.
    }
};
