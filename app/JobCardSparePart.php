<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCardSparePart extends Model
{
    use HasFactory;

    protected $appends = ['category'];

    protected $hidden = ['labels'];
    public function labels(){
        return $this->belongsTo(SparePartLabel::class,'stock_label_id');
    }
    public function getCategoryAttribute()
    {
        return $this->labels->spare_part_type;
    }
    

}
