<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'address',
        'category_id',
        'organization_id',
        'country_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->hasOne(Category::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}
