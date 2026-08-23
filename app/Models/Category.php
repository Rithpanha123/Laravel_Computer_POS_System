<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Relations\HasMany;

class Category extends Model
{
    protected $table = 'categories';

    protected $primarykey = 'cate_id';

    protected $fillable = [
        'cate_name',
        'description',
        'is_active',
        'created_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(
            'category_id',
            'cate_id'
        );
    }
}
