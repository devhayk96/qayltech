<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientAdditionalInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'workout_id',
        'key',
        'value'
    ];

    protected $table = 'patient_additional_infos';

    const KEYS = [
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

}
