<tr>
    <td style="width: 100px;">
        <div>
            <select class="form-control" name="stock_category[]">
                <option value="" selected disabled>--Select Category --</option>
                <option value="1">Accessory</option>
                <option value="2">Parts</option>
                <option value="3">Tools</option>
                <option value="4">Lubes</option>
            </select>
        </div>
    </td>
    <td style="width: 150px;" class="position-relative">
        <input type="text" name="stock_item[]" class="form-control searchInput" placeholder="Enter Label Here..." oninput="getSearchData(this)">
        <div class="dropdown-menu show searchResults"
            style="display: none; max-height: 200px; overflow-y: auto;">
        </div>
    </td>
    <td>
        <input type="number" class="form-control" name="stock_quantity[]" min="1" step="1" value="1">
    </td>
    <td>
        <input type="number" class="form-control" value="1" name="stock_price[]">
    </td>
    <td>
        <button class="btn btn-sm btn-danger rounded border-0 text-white" onclick="removeAddStockRow(this)">
            Remove
        </button>
    </td>
</tr>
