<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Product; // <--- ត្រូវប្រាកដថាបាន import Product model

class Brand extends Model
{
    use HasFactory;

    protected $table = 'brands';
    protected $primaryKey = 'brand_id';

    public $incrementing = true;
    protected $keyType = 'int';

    const UPDATED_AT = null;
    const CREATED_AT = 'created_at';

    protected $fillable = [
        'brand_name',
        'description',
        'is_active',
        'created_at',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'created_at' => 'datetime',
    ];

    /**
     * Relationship ទៅកាន់ Products
     */
    public function products(): HasMany
    {
        // ប៉ារ៉ាម៉ែត្រទី ១: Model Class (Product::class)
        // ប៉ារ៉ាម៉ែត្រទី ២: Foreign Key លើតារាង products (brand_id)
        // ប៉ារ៉ាម៉ែត្រទី ៣: Local Key លើតារាង brands (brand_id)
        return $this->hasMany(Product::class, 'brand_id', 'brand_id');
    }
}