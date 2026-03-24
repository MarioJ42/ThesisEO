<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('wedding_packages')->onDelete('cascade');
            $table->foreignId('vendor_category_id')->constrained('vendor_categories')->onDelete('cascade');

            // Waktu acara: Pagi/Subuh atau Malam
            $table->enum('session', ['morning', 'evening', 'all_day'])->default('all_day');

            // Nama peran spesifik (Contoh: "MUA & Hair Styling Bride's Mom")
            $table->string('role_detail');

            // Penanda apakah vendor ini dibayarkan oleh paket EO (true) atau bayar sendiri oleh Klien (false)
            $table->boolean('is_included')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_templates');
    }
};
