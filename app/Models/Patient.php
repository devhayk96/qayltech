<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'country_id',
        'first_name',
        'last_name',
        'birth_date',
        'disability_date',
        'disability_reason',
        'disability_category',
        'injury',
        'workout_begin',
        'is_individual',
        'image',
        'pdf',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}
