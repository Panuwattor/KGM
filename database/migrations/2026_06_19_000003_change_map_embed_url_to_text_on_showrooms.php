<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('showrooms', function (Blueprint $table) {
            // เก็บได้ทั้งลิงก์และโค้ด <iframe> ของ Google Maps ที่ยาวเกิน 255 ตัวอักษร
            $table->text('map_embed_url')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('showrooms', function (Blueprint $table) {
            $table->string('map_embed_url')->nullable()->change();
        });
    }
};
