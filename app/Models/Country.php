<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'user_id'
    ];

    public static $relationNamesForProfileData = [
        'organizations',
        'hospitals',
        'doctors',
        'patients',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organizations()
    {
        return $this->hasMany(Organization::class);
    }

    public function hospitals()
    {
        return $this->hasMany(Hospital::class);
    }

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

}
