<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSerial extends Model
{
    protected $table = 'product_serials';

    protected $primaryKey = 'serial_id';

    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'serial_number',
        'status',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(
            Product::class,
            'product_id',
            'product_id'
        );
    }

    public function warranty(): HasOne
    {
        return $this->hasOne(
            ProductWarranty::class,
            'serial_id',
            'serial_id'
        );
    }
}
