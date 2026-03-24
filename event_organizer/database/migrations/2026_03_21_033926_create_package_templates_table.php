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
            $table->enum('session', ['morning', 'evening', 'all_day'])->default('all_day');
            $table->string('role_detail');
            $table->boolean('is_included')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_templates');
    }
};
