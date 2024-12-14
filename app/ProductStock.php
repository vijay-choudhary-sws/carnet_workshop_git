<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    use HasFactory;
    protected $table='stocks';

    protected $fillable = [
        'label_id',
        'category_id',
        'stock',
        'user_id',
    ];
    
}

