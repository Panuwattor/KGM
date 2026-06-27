<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->boolean('embroidery')->default(false)->after('variant_id');
            $table->text('embroidery_text')->nullable()->after('embroidery');
            $table->decimal('embroidery_price', 10, 2)->default(0)->after('embroidery_text');
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn(['embroidery', 'embroidery_text', 'embroidery_price']);
        });
    }
};
