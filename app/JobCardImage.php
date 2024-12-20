<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 

class JobCardImage extends Model
{
    use HasFactory;
 
    protected $fillable = ['job_card_number','image_id'];
}
