<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $table = 'roles';
    protected $primarykey = 'role_id';
    protected $fillable =[
        'role_name',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(
            User::class,
            'role_id', 
            'role_id'
        );
    }
    public function rolePermissions():HasMany
    {
        return $this->hasMany(
            RolePermission::class,
            'role_id',
            'role_id'
        );
    }
}
