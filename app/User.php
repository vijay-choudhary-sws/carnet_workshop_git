<?php

namespace App;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable; // Add this line

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'mobile_no', 'otp',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /*Code for New Accessrights*/
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_users');
    }

    public function scopeGetByUser($query, $id)
    {
        $role = getUsersRole(Auth::User()->role_id);
        if (isAdmin(Auth::User()->role_id)) {
            return $query;
        } else {
            return $query->where('id', Auth::User()->id);
        }
    }

    /*Give permission to access rights*/
    public function hasAccess(array $permissions)
    {
        foreach ($this->roles as $role) {
            if ($role->hasAccess($permissions)) {
                return true;
            }
        }
        return false;
    }

    public function notes()
    {
        return $this->morphMany(Notes::class, 'entity', 'entity_type', 'entity_id');
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'customer_id');
    }
 
}
