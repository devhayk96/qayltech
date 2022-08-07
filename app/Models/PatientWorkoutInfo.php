<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientWorkoutInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'device_id',
        'game',
        'status',
    ];

    protected $table = 'patient_workout_infos';

    public function additionalInfos()
    {
        return $this->hasMany(
            PatientAdditionalInfo::class,
            'workout_id'
        );
    }


}
