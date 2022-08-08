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
        'status',
        'game',
        'walk_count',
        'steps_count',
        'steps_opening',
        'speed',
        'passed_way',
        'calories',
        'spent_time',
        'key1',
        'key2',
        'key3',
    ];

    protected $table = 'patient_workout_infos';


}
