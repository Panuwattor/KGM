<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('name_in_thai');
            $table->string('name_in_english')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provinces');
    }
};
