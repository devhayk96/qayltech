<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'user_id',
        'country_id',
        'hospital_id',
        'first_name',
        'last_name',
        'birth_date',
        'disability_date',
        'disability_reason',
        'disability_category',
        'organization_id',
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
        return $this->belongsToMany(Doctor::class, 'doctor_patient');
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function additionalInfos()
    {
        return $this->hasMany(PatientsAdditionalinfo::class);
    }

    public function workoutInfos()
    {
        return $this->hasMany(PatientsWorkoutinfo::class);
    }

    public function getImagePathAttribute()
    {
        $imageName = $this->image ?? User::DEFAULT_IMAGE_PATH;
        return config('app.url') . $imageName;
    }

    public function getPdfPathAttribute()
    {
        return $this->pdf ? config('app.url') . $this->pdf : null;
    }


}
