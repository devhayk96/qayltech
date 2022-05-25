<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'profession',
        'hospital_id'
    ];

    public function hospital(){
        return $this->belongsTo(Hospital::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
