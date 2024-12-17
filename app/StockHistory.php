<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    use HasFactory;

    public function label()
    {
        return $this->belongsTo(SparePartLabel::class,'label_id');
    }

}
