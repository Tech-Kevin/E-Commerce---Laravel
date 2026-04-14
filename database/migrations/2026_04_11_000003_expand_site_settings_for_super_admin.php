<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('logo_path')->nullable()->after('store_tagline');
            $table->string('favicon_path')->nullable()->after('logo_path');
            $table->string('hero_image_path')->nullable()->after('favicon_path');
            $table->string('hero_title')->nullable()->after('hero_image_path');
            $table->string('hero_subtitle')->nullable()->after('hero_title');
            $table->string('hero_cta_text')->nullable()->after('hero_subtitle');
            $table->string('hero_cta_url')->nullable()->after('hero_cta_text');

            $table->string('contact_email')->nullable()->after('hero_cta_url');
            $table->string('contact_phone')->nullable()->after('contact_email');
            $table->string('contact_address')->nullable()->after('contact_phone');

            $table->string('facebook_url')->nullable()->after('contact_address');
            $table->string('instagram_url')->nullable()->after('facebook_url');
            $table->string('twitter_url')->nullable()->after('instagram_url');
            $table->string('youtube_url')->nullable()->after('twitter_url');

            $table->text('footer_about')->nullable()->after('youtube_url');
            $table->string('footer_copyright')->nullable()->after('footer_about');

            $table->boolean('maintenance_mode')->default(false)->after('footer_copyright');
            $table->text('maintenance_message')->nullable()->after('maintenance_mode');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'logo_path', 'favicon_path', 'hero_image_path',
                'hero_title', 'hero_subtitle', 'hero_cta_text', 'hero_cta_url',
                'contact_email', 'contact_phone', 'contact_address',
                'facebook_url', 'instagram_url', 'twitter_url', 'youtube_url',
                'footer_about', 'footer_copyright',
                'maintenance_mode', 'maintenance_message',
            ]);
        });
    }
};
