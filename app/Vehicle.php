<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Vehicle extends Model
{
    //For 
    protected $table = 'tbl_vehicles';



    public function scopeGetByUser($query, $id)
    {
        $role = getUsersRole(Auth::User()->role_id);
        if (isAdmin(Auth::User()->role_id)) {
            return $query;
        } else {
            return $query->where('id', Auth::User()->id);
        }
    }

    public function notes()
    {
        return $this->morphMany(Notes::class, 'entity', 'entity_type', 'entity_id');
    }

    public function brand(){
        return $this->belongsTo(Vehiclebrand::class, 'vehiclebrand_id');
    }
}
