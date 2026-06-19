<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        // ค่าเริ่มต้นถูก seed ใน DatabaseSeeder (ShippingProvider)
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_providers');
    }
};
