<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Relations\HasMay;

class Gender extends Model
{
    protected $table = 'genders';

    protected $primarykey = 'gender_id';

    public $timestamps = false;

    protected $fillable = [
        'gender_name',
        'gender_code',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(
            User::class,
            'gender_id',
            'gender_id'
        );
    }

    public function staff(): HasMany
    {
        return $this->hasMany(
            Staff::class,
            'gender_id',
            'gender_id'
        );
    }
}
