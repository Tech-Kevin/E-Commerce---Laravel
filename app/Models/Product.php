<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;

use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Conversions\Manipulations;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use App\Models\Category;
use App\Models\Subcategory;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = [
        'name',
        'description',
        'full_description',
        'sku',
        'price',
        'sale_price',
        'stock',
        'category_id',
        'subcategory_id',
        'brand',
        'image',
        'is_active',
        'is_featured',
        'shipping_charge',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'gallery' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('product_image')->singleFile();
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items', 'product_id', 'order_id')->withPivot('quantity');
    }

    public function category()
    {
        return $this->belongsTo(Category::class , 'category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function activeSale()
    {
        return $this->hasOne(Sale::class)
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    public function getSalePriceAttribute($value)
    {
        if ($this->relationLoaded('activeSale') && $this->activeSale) {
            return $this->activeSale->sale_price;
        }
        return $value;
    }
}
