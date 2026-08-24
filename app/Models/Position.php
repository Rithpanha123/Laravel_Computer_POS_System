<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends Model
{
    protected $table = 'positions';
    protected $primaryKey = 'position_id';

    protected $fillable = [
        'position_name',
        'description',
        'is_active',
    ];

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class, 'position_id', 'position_id');
    }
}