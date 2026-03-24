<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_vendor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('vendor_category_id')->constrained('vendor_categories')->onDelete('cascade');
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->onDelete('set null');
            $table->foreignId('vendor_contact_id')->nullable()->constrained('vendor_contacts')->onDelete('set null');
            $table->foreignId('vendor_package_id')->nullable()->constrained('vendor_packages')->onDelete('set null');
            $table->enum('session', ['morning', 'evening', 'all_day'])->default('all_day');
            $table->string('role_detail');
            $table->boolean('is_included')->default(false);
            $table->decimal('deal_price', 15, 2)->default(0);
            $table->integer('meal_crew')->default(0);
            $table->enum('status', ['unassigned', 'reviewing', 'verified', 'rejected'])->default('unassigned');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_vendor');
    }
};
