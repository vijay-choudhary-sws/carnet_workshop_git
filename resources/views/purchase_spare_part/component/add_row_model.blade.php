<tr data-row="{{$row}}">
    <td style="width: 100px;">
        <div>
            <select class="form-control" name="category[]" onchange="getItem(this)"  required="true">
                <option value="" selected disabled>--Select Category --</option>
                <option value="1">Accessory</option>
                <option value="2">Parts</option>
                <option value="3">Tools</option>
                <option value="4">Lubes</option>
            </select>
        </div>
    </td>
    <td class="" style="width: 150px;">
       
            <select class="form-control " name="item[]" onchange="getStock(this)" required="true">
                <option value="" selected disabled>--Select Item --</option>
            </select>
        
    </td>
    <td>
        <input type="number" class="form-control" name="quantity[]" min="1" step="1" max="0" oninput="checkMax(this)" required="true">
    </td>
    <td>
        <input type="number" name="price[]" class="form-control bg-light" value="0"  readonly>
    </td>
    <td>
        <input type="number" class="form-control bg-light" value="0" name="total_price[]" readonly>
    </td>
    <td>
        <button type="button" class="btn btn-danger border-0 text-white rounded" onclick="deleteRow(this);">Delete</button>
    </td>
</tr>