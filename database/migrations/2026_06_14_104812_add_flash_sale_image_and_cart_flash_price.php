<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flash_sales', function (Blueprint $table) {
            $table->string('image')->nullable()->after('name');
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->decimal('flash_sale_price', 10, 2)->nullable()->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('flash_sales', function (Blueprint $table) {
            $table->dropColumn('image');
        });
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('flash_sale_price');
        });
    }
};
