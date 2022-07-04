<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Country;
use App\Models\Hospital;
use App\Models\Organization;
use App\Models\Patient;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'string'
    ];

    const DEFAULT_IMAGE_PATH = 'images/default-avatar.png';

    public function role()
    {
        return $this->roles()->first();
    }

    public function setPasswordAttribute($value): string
    {
        return $this->attributes['password'] = Hash::make($value ?? Str::random(10));
    }

    public function country()
    {
        return $this->hasOne(Country::class);
    }

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    public function organization()
    {
        return $this->hasOne(Organization::class);
    }

    public function hospital()
    {
        return $this->hasOne(Hospital::class);
    }

}
