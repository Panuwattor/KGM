<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('delivery_method')->default('ship')->after('status'); // ship | pickup
            $table->unsignedBigInteger('pickup_showroom_id')->nullable()->after('delivery_method');
            $table->dateTime('picked_up_at')->nullable()->after('delivered_at');

            // ออเดอร์รับเองที่ร้านไม่ต้องมีที่อยู่จัดส่ง -> อนุญาตให้ว่างได้
            $table->text('ship_address')->nullable()->change();
            $table->string('ship_district')->nullable()->change();
            $table->string('ship_amphoe')->nullable()->change();
            $table->string('ship_province')->nullable()->change();
            $table->string('ship_postcode', 10)->nullable()->change();

            $table->foreign('pickup_showroom_id')->references('id')->on('showrooms')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['pickup_showroom_id']);
            $table->dropColumn(['delivery_method', 'pickup_showroom_id', 'picked_up_at']);
        });
    }
};
