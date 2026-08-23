<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairPayment extends Model
{
    protected $table = 'repair_payments';

    protected $primaryKey = 'rp_id';

    public $timestamps = false;

    protected $fillable = [
        'repair_id',
        'payment_method',
        'amount',
        'payment_date',
        'reference_no',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    public function repair(): BelongsTo
    {
        return $this->belongsTo(
            Repair::class,
            'repair_id',
            'repair_id'
        );
    }
}
