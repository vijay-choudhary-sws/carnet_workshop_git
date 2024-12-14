<tr data-row="{{$row}}">
    <td>
        <span>{!! $stock->title.'<small>(Current Qty. '.$stock->stock.')</small>' !!}</span>
        <input type="hidden" class="form-control" name="item_id[]" value="{{$stock->id}}">
    </td>
    <td><input type="number" class="form-control" name="quantity[]" value="0" min="1" max="" step="1" oninput="getPrice(this)"></td>
    <td><input type="number" class="form-control" name="price[]" value="0" min="1" step="1" oninput="getPrice(this)"></td>
    <td><input type="number" class="form-control bg-light" name="total_amount[]" readonly value="0" min="1"  step="1"></td>
    <td><input type="number" class="form-control" name="discount[]" value="0" min="0"  step="1" oninput="getPrice(this)"></td>
    <td><input type="number" class="form-control" name="final_amount[]" value="0" min="1"  step="1" readonly></td>
    <td>
        <select name="employee[]" class="select2">
            @foreach ($employee as $item)
                <option value="{{$item->id}}">{{$item->display_name}}</option>
            @endforeach
        </select>
    </td>
    <td><button class="btn btn-sm btn-danger rounded border-0 text-white" onclick="removeRow(this)">Remove</button></td>
</tr>