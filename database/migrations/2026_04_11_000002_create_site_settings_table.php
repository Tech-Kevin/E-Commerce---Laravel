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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('store_name')->default('Ekka_Lv');
            $table->string('store_tagline')->default('Online Store');
            $table->string('vendor_primary_color', 7)->default('#e67e4d');
            $table->string('vendor_secondary_color', 7)->default('#f2af78');
            $table->string('vendor_background_color', 7)->default('#f8f6f3');
            $table->string('store_primary_color', 7)->default('#e67e4d');
            $table->string('store_secondary_color', 7)->default('#f3b37a');
            $table->string('delivery_primary_color', 7)->default('#e67e4d');
            $table->string('delivery_secondary_color', 7)->default('#f2af78');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
