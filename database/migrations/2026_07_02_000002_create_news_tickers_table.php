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
        Schema::create('news_tickers', function (Blueprint $table) {
            $table->id();
            $table->text('plain_text')->nullable()->comment('ข้อความเฉยๆ');
            $table->string('link_text')->nullable()->comment('ข้อความที่กดได้');
            $table->string('link_url')->nullable()->comment('ลิงก์');
            $table->boolean('is_active')->default(true)->comment('สถานะการแสดงผล');
            $table->integer('order')->default(0)->comment('ลำดับการแสดงผล');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_tickers');
    }
};
