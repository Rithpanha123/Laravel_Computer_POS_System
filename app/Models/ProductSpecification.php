<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    protected $table = 'product_specifications';

    protected $primarykey = 'ps_id';

    protected $fillable = [
        'product_id',
        'cpu',
        'ram',
        'storage',
        'gpu',
        'screen_size',
        'screen_resolution',
        'operating_system',
        'color',
        'other_specs',
    ];

    public function product(): BelongTo
    {
        return $this->belongsTo(
            Product::class,
            'product_id',
            'product_id'
        );
    }
}
