<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $table = 'suppliers';
    protected $primaryKey = 'supplier_id';
    public $timestamps = false;

    protected $fillable = [
        'supplier_code',
        'supplier_name',
        'contact_person',
        'phone',
        'email',
        'photo',
        'address',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
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