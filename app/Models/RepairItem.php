<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairItem extends Model
{
    protected $table = 'repair_items';

    protected $primaryKey = 'ri_id';

    public $timestamps = false;

    protected $fillable = [
        'repair_id',
        'product_id',
        'description',
        'quantity',
        'unit_price',
        'total',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function repair(): BelongsTo
    {
        return $this->belongsTo(
            Repair::class,
            'repair_id',
            'repair_id'
        );
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(
            Product::class,
            'product_id',
            'product_id'
        );
    }
}
