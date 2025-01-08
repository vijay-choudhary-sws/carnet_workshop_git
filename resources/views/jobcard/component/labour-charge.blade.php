<option value="" selected disabled>-- Select Charges -- </option>
@foreach ($labourCharges as $item)
    <option value="{{ $item->id }}" data-title="{{ $item->title }}" data-price="{{ $item->price }}">
        {{ $item->title.'(price: '.$item->price.')' }}
    </option>
@endforeach
