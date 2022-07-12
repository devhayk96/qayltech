<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'address',
        'category_id',
        'country_id',
        'user_id'
    ];

    public static $relationNamesForProfileData = [
        'hospitals',
        'doctors',
        'patients',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->hasOne(Category::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
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
