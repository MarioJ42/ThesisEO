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
        Schema::table('vendor_packages', function (Blueprint $table) {
            $table->decimal('net_price', 15, 2)->default(0)->after('price')->comment('Harga modal asli dari vendor');
        });

        Schema::table('event_vendor', function (Blueprint $table) {
            $table->decimal('net_price', 15, 2)->default(0)->after('deal_price')->comment('Harga modal vendor khusus untuk event ini');
        });

        Schema::table('wedding_packages', function (Blueprint $table) {
            $table->decimal('eo_fee', 15, 2)->default(0)->after('base_price')->comment('Jasa murni/Profit fix Fenix EO');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_packages', function (Blueprint $table) {
            $table->dropColumn('net_price');
        });

        Schema::table('event_vendor', function (Blueprint $table) {
            $table->dropColumn('net_price');
        });

        Schema::table('wedding_packages', function (Blueprint $table) {
            $table->dropColumn('eo_fee');
        });
    }
};
