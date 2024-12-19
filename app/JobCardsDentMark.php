<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCardsDentMark extends Model
{
    use HasFactory;
 
    protected $fillable = ['jobcard_number','file_id'];
}
