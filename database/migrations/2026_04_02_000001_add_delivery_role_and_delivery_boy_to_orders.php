<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add 'delivery' to the role enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','customer','vendor','delivery') DEFAULT 'customer'");

        // Add delivery statuses (picked_up, on_the_way) to orders status enum
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending','processing','shipped','arriving','picked_up','on_the_way','delivered','cancelled') DEFAULT 'pending'");

        // Add delivery_boy_id to orders
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('delivery_boy_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
        });

        // Create delivery_otps table
        Schema::create('delivery_otps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('otp', 6);
            $table->timestamp('expires_at');
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_otps');

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['delivery_boy_id']);
            $table->dropColumn('delivery_boy_id');
        });

        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending','processing','shipped','arriving','delivered','cancelled') DEFAULT 'pending'");

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','customer','vendor') DEFAULT 'customer'");
    }
};
