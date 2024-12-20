<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCardsInspection extends Model
{
    use HasFactory;
 
    protected $fillable = ['jobcard_number','customer_voice','is_customer_voice'];
}
