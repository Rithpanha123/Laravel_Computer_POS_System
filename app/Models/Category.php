<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Product;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'cate_id';

    public $incrementing = true;
    protected $keyType = 'int';

    // ប្រាប់ Laravel ថាតារាងនេះគ្មាន column updated_at ឡើយ
    const UPDATED_AT = null;
    const CREATED_AT = 'created_at';

    protected $fillable = [
        'cate_name',
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
        return $this->hasMany(Product::class, 'category_id', 'cate_id');
    }
}