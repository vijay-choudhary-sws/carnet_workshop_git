<option value="" selected disabled>-- Select Spare Part -- </option>
@foreach ($labels as $item)
    <option value="{{ $item->id }}">{!! $item->title.'<small>(Current Qty. '.$item->stock.')</small>' !!}</option>
@endforeach