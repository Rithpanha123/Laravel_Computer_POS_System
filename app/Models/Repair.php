<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    'deposit_amount',
    'due_amount',
    'final_cost',
    'payment_status',
    'status',
    'received_at',
    'completed_at',
    'notes',
    'created_at',
];

    protected $casts = [
        'estimated_cost' => 'decimal:2',
        'final_cost'     => 'decimal:2',
        'received_at'    => 'datetime',
        'completed_at'   => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'technician_id', 'staff_id');
    }
}