<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // ลูกค้าที่ไม่ได้สมัครสมาชิก: customer_id เป็น null, ใช้ token เป็นกุญแจเข้าดูออเดอร์
            $table->boolean('is_guest')->default(false)->after('customer_id');
            $table->string('guest_token', 64)->nullable()->unique()->after('is_guest');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['guest_token']);
            $table->dropColumn(['is_guest', 'guest_token']);
        });
    }
};
