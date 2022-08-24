<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'hospital_id',
        'organization_id',
        'country_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function doctors()
    {
        return $this->belongsToMany(
            Doctor::class,
            'doctor_patient',
            'doctor_id'
        );
    }

    public function patients()
    {
        return $this->belongsToMany(
            Patient::class,
            'doctor_patient',
            'patient_id'
        );
    }



}
