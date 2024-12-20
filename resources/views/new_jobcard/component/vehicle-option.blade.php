@foreach ($vehicles as $item)
    <option value="{{$item->id}}" @checked($loop->first)>{{getvehicleBrand($item->vehiclebrand_id).'/'.$item->modelname.'/'.$item->number_plate.'/'.$item->id}}</option>
@endforeach