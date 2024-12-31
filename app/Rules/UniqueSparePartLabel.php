<?php
namespace App\Rules;

use App\SparePart;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UniqueSparePartLabel implements Rule
{
    protected $sparePartType;
    protected $spareId;

    public function __construct($sparePartType,$spareId)
    {
        $this->sparePartType = $sparePartType;
        $this->spareId = $spareId;
    }
    public function passes($attribute, $value)
    {
        
        $exists = SparePart::with('label')
        ->where('user_id', Auth::id())
        ->whereHas('label', function ($qry) use ($value){
            $qry->where('title', $value);
            $qry->where('spare_part_type', $this->sparePartType);
        })
        ->whereNot('id',$this->spareId)
        ->exists();

        return !$exists;
    }

    public function message()
    {
        return 'The :attribute has already been taken for the current user.';
    }
}
