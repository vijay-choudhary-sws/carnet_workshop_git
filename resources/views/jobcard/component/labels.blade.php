<option value="" selected disabled>-- Select {{ $cat }} -- </option>
@foreach ($labels as $item)
    {{-- <option value="{{ $item['label_id'] }}">{!! $item['label_name'].'<small>(Current Qty. '.$item['stock'].')</small>,<small class="text-danger">(Price - '.$item['price'].')</small>' !!}</option> --}}
    <option value="{{ $item['label_id'] }}" data-label-name="{{ $item['label_name'] }}" data-stock="{{ $item['stock'] }}"
        data-price="{{ $item['price'] }}">
        {{ $item['label_name'] }}
    </option>
@endforeach
