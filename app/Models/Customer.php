<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Relations\HasMany;

class Customer extends Model
{
    protected $table = 'customers';

    protected $primarykey = 'customer_id';

    protected $fillable = [
        'customer_code',
        'customer_name',
        'customer_code',
        'phone',
        'email',
        'address',
        'photo',
    ];

    public function sale(): HasMany
    {
        return $this->hasMany(
            Sale::class,
            'customer_id',
            'customer_id'
        );
    }

    public function repair(): HasMany
    {
        return $this->hasMany(
            Repair::class,
            'customer_id',
            'customer_id'
        );
    }
}
