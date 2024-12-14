<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UniqueSparePartLabel implements Rule
{
    public function passes($attribute, $value)
    {
        $exists = DB::table('spare_part_labels')
            ->join('spare_parts', 'spare_parts.label_id', '=', 'spare_part_labels.id')
            ->where('spare_part_labels.title', $value)
            ->where('spare_parts.user_id', Auth::id())
            // ->where('spare_parts.spare_part_type', 1)
            ->exists();

        return !$exists;
    }

    public function message()
    {
        return 'The :attribute has already been taken for the current user.';
    }
}
