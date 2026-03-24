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
        Schema::table('products', function (Blueprint $table) {
             $table->foreignId('category_id')->nullable()->after('stock')->constrained()->nullOnDelete();
            $table->foreignId('subcategory_id')->nullable()->after('category_id')->constrained()->nullOnDelete();

            if (Schema::hasColumn('products', 'category')) {
                $table->dropColumn('category');
            }
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('category')->nullable();
            $table->dropConstrainedForeignId('category_id');
            $table->dropConstrainedForeignId('subcategory_id');
        });
       
    }
};
