<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Relations\HasMay;

class Supplier extends Model
{
    protected $table = 'suppliers';

    protected $primarykey = 'supplier_id';

    protected $fillable = [
        'supplier_code',
        'supplier_name',
        'contact_person',
        'phone',
        'email',
        'photo',
        'address',
    ];

    public function purchases(): HasMany
    {
        return $this->hasMany(
            Purchase::class,
            'supplier_id',
            'supplier_id'
        );
    }
}
