<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewJobCard extends Model
{
    use HasFactory,SoftDeletes;

    protected $dates = ['deleted_at'];

    public function jobCardSpareParts(){
        return $this->hasMany(JobCardSparePart::class,'jobcard_id');
    }
    
    public function user(){
        return $this->belongsTo(User::class,'customer_id');
    }
    
    public function exitNote(){
        return $this->hasOne(ExitNote::class);
    }

}
