<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'brands';

    protected $primarykey = 'brand_id';

    protected $fillable = [
        'brand_name',
        'description',
        'is_active',
        'created_at',
    ];

    protected $casts = [
        'is_acive' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(
            'brand_id',
            'brand_id'
        );
    }
}
