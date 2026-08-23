<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    protected $table = 'repairs';

    protected $primaryKey = 'repair_id';

    public $timestamps = false;

    protected $fillable = [
        'repair_no',
        'customer_id',
        'technician_id',
        'device_name',
        'serial_number',
        'problem_description',
        'diagnosis',
        'estimated_cost',
        'final_cost',
        'status',
        'received_at',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'estimated_cost' => 'decimal:2',
        'final_cost' => 'decimal:2',
        'received_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(
            Customer::class,
            'customer_id',
            'customer_id'
        );
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(
            Staff::class,
            'technician_id',
            'staff_id'
        );
    }

    public function items(): HasMany
    {
        return $this->hasMany(
            RepairItem::class,
            'repair_id',
            'repair_id'
        );
    }

    public function payments(): HasMany
    {
        return $this->hasMany(
            RepairPayment::class,
            'repair_id',
            'repair_id'
        );
    }
}
