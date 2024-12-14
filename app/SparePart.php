<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SparePart extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'spare_parts';
    protected $dates = ['deleted_at'];
    protected $append = ['name'];

    public function unit(){
        return $this->belongsTo(Unit::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function label()
    {
        return $this->belongsTo(SparePartLabel::class,'label_id');
    }
    public function getNameAttribute()
    {
        return $this->label ? $this->label->title : null;
    }

}
