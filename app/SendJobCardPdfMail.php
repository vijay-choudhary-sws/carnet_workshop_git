<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SendJobCardPdfMail extends Model
{
    use HasFactory;

    protected $fillable = ['path','is_generated'];

    public function jobCard(){
        return $this->belongsTo(NewJobCard::class,'jobcard_id');
    }
}
