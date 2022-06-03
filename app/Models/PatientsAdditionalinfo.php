<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientsAdditionalinfo extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_id',
        'key',
        'value'
    ];
    protected $table = 'patient_additional_infos';

}
