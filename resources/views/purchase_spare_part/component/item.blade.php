<option value="" selected disabled>-- Select Item --</option>
@foreach($items as $value)
    <option value="{{ $value->id }}">{{ $value->name.', '.'Stock='.$value->stock.', Price='.$value->price }}</option>
@endforeach