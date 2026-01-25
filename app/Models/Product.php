<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vendor_id',
        'category_id',
        'name',
        'name_ar',
        'slug',
        'description',
        'description_ar',
        'sku',
        'price',
        'compare_price',
        'cost',
        'quantity',
        'low_stock_threshold',
        'track_inventory',
        'stock_status',
        'weight',
        'length',
        'width',
        'height',
        'meta_title',
        'meta_title_ar',
        'meta_description',
        'meta_description_ar',
        'meta_keywords',
        'is_active',
        'is_featured',
        'published_at',
        'views_count',
        'sales_count',
        'rating_avg',
        'rating_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'cost' => 'decimal:2',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'track_inventory' => 'boolean',
        'published_at' => 'datetime',
        'rating_avg' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
            if (empty($product->sku)) {
                $product->sku = 'PRD-' . strtoupper(Str::random(8));
            }
        });

        static::saving(function ($product) {
            // Update stock status based on quantity
            if ($product->track_inventory) {
                if ($product->quantity <= 0) {
                    $product->stock_status = 'out_of_stock';
                } else {
                    $product->stock_status = 'in_stock';
                }
            }
        });
    }

    // Relationships
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_status', 'in_stock');
    }

    public function scopeLowStock($query)
    {
        return $query->where('track_inventory', true)
                     ->whereColumn('quantity', '<=', 'low_stock_threshold')
                     ->where('quantity', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock_status', 'out_of_stock');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('name_ar', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%");
        });
    }

    // Helpers
    public function isInStock(): bool
    {
        return $this->stock_status === 'in_stock';
    }

    public function isLowStock(): bool
    {
        return $this->track_inventory && 
               $this->quantity <= $this->low_stock_threshold && 
               $this->quantity > 0;
    }

    public function hasDiscount(): bool
    {
        return $this->compare_price && $this->compare_price > $this->price;
    }

    public function getDiscountPercentage(): ?float
    {
        if (!$this->hasDiscount()) {
            return null;
        }
        return round((($this->compare_price - $this->price) / $this->compare_price) * 100);
    }

    public function decrementStock(int $quantity): bool
    {
        if (!$this->track_inventory) {
            return true;
        }

        if ($this->quantity < $quantity) {
            return false;
        }

        $this->decrement('quantity', $quantity);
        return true;
    }

    public function incrementStock(int $quantity): void
    {
        if ($this->track_inventory) {
            $this->increment('quantity', $quantity);
        }
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    public function updateRating(): void
    {
        $reviews = $this->approvedReviews;
        $this->rating_count = $reviews->count();
        $this->rating_avg = $reviews->avg('rating') ?? 0;
        $this->save();
    }
}
