<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';

    protected $primaryKey = 'staff_id';

    protected $fillable = [
        'staff_code',
        'first_name',
        'last_name',
        'full_name',
        'gender_id',
        'phone',
        'email',
        'address',
        'city',
        'province',
        'date_of_birth',
        'nationality',
        'position_id',
        'hire_date',
        'resign_date',
        'salary',
        'employment_status',
        'photo',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hire_date' => 'date',
        'resign_date' => 'date',
        'salary' => 'decimal:2',
    ];

    public function gender(): BelongsTo
    {
        return $this->belongsTo(
            Gender::class,
            'gender_id',
            'gender_id'
        );
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(
            Position::class,
            'position_id',
            'position_id'
        );
    }
}
