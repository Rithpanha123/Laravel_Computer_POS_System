<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'customer_id';
    public $timestamps = false;

    protected $fillable = [
        'customer_code',
        'customer_name',
        'phone',
        'email',
        'address',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'customer_id', 'customer_id');
    }

    public function repairs(): HasMany
    {
        return $this->hasMany(Repair::class, 'customer_id', 'customer_id');
    }
}