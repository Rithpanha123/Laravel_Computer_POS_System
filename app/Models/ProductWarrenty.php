<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductWarrenty extends Model
{
    protected $table = 'product_warranties';

    protected $primaryKey = 'pw_id';

    public $timestamps = false;

    protected $fillable = [
        'serial_id',
        'sale_id',
        'warranty_start',
        'warranty_end',
        'warranty_period_months',
        'terms',
        'status',
        'created_at',
    ];

    protected $casts = [
        'warranty_start' => 'date',
        'warranty_end' => 'date',
        'warranty_period_months' => 'integer',
        'created_at' => 'datetime',
    ];

    public function serial(): BelongsTo
    {
        return $this->belongsTo(
            ProductSerial::class,
            'serial_id',
            'serial_id'
        );
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(
            Sale::class,
            'sale_id',
            'sale_id'
        );
    }
}
