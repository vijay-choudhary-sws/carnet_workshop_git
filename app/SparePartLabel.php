<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SparePartLabel extends Model
{
    use HasFactory,SoftDeletes;
protected $fillable = ['title','spare_part_type'];
    protected $dates = ['deleted_at'];

    public function productStocks()
    {
        return $this->hasMany(ProductStock::class, 'label_id');
    }
}
