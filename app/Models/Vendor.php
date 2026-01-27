<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'shop_name',
        'shop_name_ar',
        'slug',
        'description',
        'description_ar',
        'logo',
        'banner',
        'phone',
        'email',
        'address',
        'address_ar',
        'tax_number',
        'business_license',
        'commission_rate',
        'balance',
        'status',
        'approved_at',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'balance' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transactions()
    {
        return $this->hasMany(VendorTransaction::class);
    }

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($vendor) {
            if (empty($vendor->slug)) {
                $vendor->slug = Str::slug($vendor->shop_name);
            }
        });
    }

    // Status checks
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isActive(): bool
    {
        return $this->status === 'approved' && $this->user->isActive();
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'approved')
            ->whereHas('user', function($q) {
                $q->where('status', 'active');
            });
    }

    // Helpers
    public function getTotalSales()
    {
        return $this->orderItems()
            ->whereHas('order', function($q) {
                $q->where('payment_status', 'paid');
            })
            ->sum('vendor_amount');
    }

    public function getTotalCommission()
    {
        return $this->orderItems()
            ->whereHas('order', function($q) {
                $q->where('payment_status', 'paid');
            })
            ->sum('commission_amount');
    }
}
