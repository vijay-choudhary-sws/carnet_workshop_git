<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    //For 
    protected $table = 'tbl_services';

    public function notes()
    {
        return $this->morphMany(Notes::class, 'entity', 'entity_type', 'entity_id');
    }
}
