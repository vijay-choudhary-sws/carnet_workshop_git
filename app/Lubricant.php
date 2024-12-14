<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lubricant extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'spare_parts';
    protected $dates = ['deleted_at'];

    protected $append = ['name'];

    protected static function booted()
    {
        static::addGlobalScope('sparePartType', function (Builder $builder) {
            $builder->where('spare_part_type', 4);
        });
    }

    public function unit(){
        return $this->belongsTo(Unit::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
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
