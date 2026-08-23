<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $table = 'products';

    protected $primarykey = 'product_id';

    protected $fillable = [
        'sku',
        'barcode',
        'product_name',
        'category_id',
        'brand_id',
        'photo',
        'description',
        'cost_price',
        'selling_price',
        'stock_quantity',
        'reorder_level',
        'is_serialized',
        'is_active',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'stock_quantity' => 'interger',
        'reoder_level' => 'interger',
        'is_serialized' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function category(): BelongTo
    {
        return $this->belongsTo(
            Category::class,
            'categoty_id',
            'cate_id'
        );
    }

    public function brand(): BelongTo
    {
        return $this->belongsTo(
            Brand::class,
            'brand_id',
            'brand_id'
        );
    }

    public function specification(): HasOne
    {
        return $this->hasOne(
            ProductSpecification::class,
            'product_id',
            'product_id'
        );
    }

    public function serials(): HasMany
    {
        return $this->hasMany(
            ProductSerial::class,
            'product_id',
            'product_id'
        );
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(
            SaleItem::class,
            'product_id',
            'product_id'
        );
    }

    public function purchaseItems(): HasMany
    {
        return $this->hasMany(
            PurchaseItem::class,
            'product_id',
            'product_id'
        );
    }

    public function repairItems(): HasMany
    {
        return $this->hasMany(
            RepairItem::class,
            'product_id',
            'product_id'
        );
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(
            StockMovement::class,
            'product_id',
            'product_id'
        );
    }
}
