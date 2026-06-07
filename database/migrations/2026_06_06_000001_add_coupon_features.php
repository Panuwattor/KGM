<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->boolean('is_public')->default(true)->after('is_active');
        });

        Schema::create('coupon_products', function (Blueprint $table) {
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->primary(['coupon_id', 'product_id']);
        });

        Schema::create('coupon_categories', function (Blueprint $table) {
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->primary(['coupon_id', 'category_id']);
        });

        Schema::create('customer_coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->timestamp('collected_at')->useCurrent();
            $table->unique(['customer_id', 'coupon_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_coupons');
        Schema::dropIfExists('coupon_categories');
        Schema::dropIfExists('coupon_products');
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn('is_public');
        });
    }
};
