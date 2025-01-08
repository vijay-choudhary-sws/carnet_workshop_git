<tr data-row="{{$row}}">
    <td>
        <span>{!! $mergedData['label_name'].'<small>(Current stock : <b>'.$mergedData['stock'].'</b>)</small>' !!}</span>
        <input type="hidden" class="form-control" name="jobcard_item_id[]" value="{{$mergedData['label_id']}}">
    </td>
    <td><input type="number" class="form-control" name="jobcard_quantity[]" value="1" min="1" max="" step="1" oninput="getJobCardPrice(this)"></td>
    <td><input type="number" class="form-control" name="jobcard_price[]" value="{{ $mergedData['price'] }}" min="1" step="1" oninput="getJobCardPrice(this)"></td>
    <td><input type="text" class="form-control bg-light" name="jobcard_total_amount[]" readonly value="{{ $mergedData['price'] }}" min="1"  step="1"></td>
    <td><input type="number" class="form-control" name="jobcard_discount[]" value="0" min="0"  step="1" oninput="getJobCardPrice(this)"></td>
    <td><input type="text" class="form-control bg-light" name="jobcard_final_amount[]" value="{{ $mergedData['price'] }}" min="1"  step="1" readonly></td>
    <td><button type="button"  class="btn btn-sm btn-danger rounded border-0 text-white" onclick="removeJobCardRow(this)"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
</tr>