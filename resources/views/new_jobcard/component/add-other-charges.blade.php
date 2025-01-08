<tr>
    <td>
        <input type="text" class="form-control" name="label[]" placeholder="Enter Label Here"
            oninput="$(this).removeClass('is-invalid');" value="{{$title}}">
    </td>
    <td>
        <input type="number" class="form-control" oninput="getextracharges();$(this).removeClass('is-invalid');"
            name="charge[]" placeholder="Enter Charge Here" value="{{$price}}">
    </td>
    <td>
        <select name="employee[]" class="form-select" style="max-width: 200px !important;">
            @foreach ($employee as $item)
                <option value="{{ $item->id }}" @selected($loop->first)>{{ $item->display_name }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <button type="button" class="btn btn-danger btn-sm remove-field rounded text-white border-white"
            style="margin-top: 5px;" fdprocessedid="dak07">
            <i class="fa fa-trash" aria-hidden="true"></i>
        </button>
    </td>
</tr>
