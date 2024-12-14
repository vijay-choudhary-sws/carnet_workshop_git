<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    //For 
    protected $table = 'tbl_invoices';

    public function notes()
    {
        return $this->morphMany(Notes::class, 'entity', 'entity_type', 'entity_id');
    }
}
