<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RolePermission extends Model
{
    protected $table = 'role_permissions';
    
    protected $primarykey  = 'rp_id';

    public $timestamps = false;

    protected $fillable = [
        'role_id',
        'permission_id',
        'created_at',
        'updated_at',
    ];

    public $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(
            Role::class,
            'role_id',
            'role_id'
        );
    }

    public function permission(): BelongTo
    {
        return $this->belongsTo(
            Permission::class,
            'permission_id',
            'permission_id'
        );
    }
}
