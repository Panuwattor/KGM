<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->enum('status', [
                'pending_payment', 'payment_uploaded', 'payment_verified',
                'processing', 'shipped', 'delivered', 'cancelled', 'refunded'
            ])->default('pending_payment');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('vat_amount', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->boolean('needs_tax_invoice')->default(false);
            $table->string('tax_id')->nullable();
            $table->string('tax_branch')->nullable();
            $table->string('tax_company_name')->nullable();
            $table->text('tax_address')->nullable();
            $table->string('coupon_code')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->string('ship_name');
            $table->string('ship_phone');
            $table->text('ship_address');
            $table->string('ship_district');
            $table->string('ship_amphoe');
            $table->string('ship_province');
            $table->string('ship_postcode', 10);
            $table->string('shipping_provider')->nullable();
            $table->string('tracking_number')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->string('payment_slip')->nullable();
            $table->timestamp('payment_uploaded_at')->nullable();
            $table->timestamp('payment_verified_at')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->text('admin_note')->nullable();
            $table->text('customer_note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
