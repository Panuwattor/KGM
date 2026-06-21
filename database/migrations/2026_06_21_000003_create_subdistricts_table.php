<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subdistricts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('name_in_thai');
            $table->string('name_in_english')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('zip_code')->nullable();
            $table->foreignId('district_id')->constrained('districts')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subdistricts');
    }
};
