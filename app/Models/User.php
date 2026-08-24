<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    // ដាក់ឈ្មោះ Column Primary Key ដែលឃើញនៅក្នុង pgAdmin
    // ប្រសិនបើក្នុង pgAdmin ឈ្មោះ "user_id" ដាក់ 'user_id'
    // ប្រសិនបើក្នុង pgAdmin ឈ្មោះ "id" ដាក់ 'id'
    protected $primaryKey = 'user_id'; 

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'username',
        'password_hash',
        'gender_id',
        'role_id',
        'full_name',
        'phone',
        'email',
        'profile_picture',
        'is_active',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class, 'gender_id', 'gender_id');
    }
}