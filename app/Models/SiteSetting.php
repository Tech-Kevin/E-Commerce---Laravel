<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'store_name',
        'store_tagline',
        'vendor_primary_color',
        'vendor_secondary_color',
        'vendor_background_color',
        'store_primary_color',
        'store_secondary_color',
        'delivery_primary_color',
        'delivery_secondary_color',

        'logo_path',
        'favicon_path',
        'hero_image_path',
        'hero_title',
        'hero_subtitle',
        'hero_cta_text',
        'hero_cta_url',

        'contact_email',
        'contact_phone',
        'contact_address',

        'facebook_url',
        'instagram_url',
        'twitter_url',
        'youtube_url',

        'footer_about',
        'footer_copyright',

        'maintenance_mode',
        'maintenance_message',
    ];

    protected $casts = [
        'maintenance_mode' => 'boolean',
    ];

    public static function defaultAttributes(): array
    {
        return [
            'store_name' => 'Ekka_Lv',
            'store_tagline' => 'Online Store',
            'vendor_primary_color' => '#e67e4d',
            'vendor_secondary_color' => '#f2af78',
            'vendor_background_color' => '#f8f6f3',
            'store_primary_color' => '#e67e4d',
            'store_secondary_color' => '#f3b37a',
            'delivery_primary_color' => '#e67e4d',
            'delivery_secondary_color' => '#f2af78',
        ];
    }

    public static function getSettings(): self
    {
        return static::query()->firstOrCreate([], static::defaultAttributes());
    }
}
