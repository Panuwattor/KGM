<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consent_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('session_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->boolean('analytics_consent')->default(false);
            $table->boolean('marketing_consent')->default(false);
            $table->boolean('necessary_consent')->default(true);
            $table->string('consent_version')->default('1.0');
            $table->timestamp('consented_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consent_logs');
    }
};
