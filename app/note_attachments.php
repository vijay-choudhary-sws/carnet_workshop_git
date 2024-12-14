<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class note_attachments extends Model
{
    use HasFactory;

    protected $fillable = ['entity_id', 'attachment'];

    public function note()
    {
        return $this->belongsTo(Notes::class, 'entity_id');
    }

}
