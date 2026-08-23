<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $primarykey = 'permission_id';
    public $timestamps = false;

    protected $fillable = [
        'permission_name',
        'permission_code',
        'mudule',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function rolePermissions(): HasMany
    {
        return $this->hasMany(
            RolePermission::class,
            'permission_id',
            'permission_id'
        );
    }
}
