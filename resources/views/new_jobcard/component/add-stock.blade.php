    <div class="modal-header">
        <h4 class="modal-title" id="myLargeModalLabel">Add Stock</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <style>
        #searchResults {
            position: absolute;
            z-index: 1000;
            width: 100%;
            background: white;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
    <form id="addStock-Form" action="{{ route('stock.bulk.store') }}" onsubmit="submitAddStockForm(event, 'addStock-Form')"
        method="post" class="form-horizontal form-label-left addStockForm">
        <div>
            <table class="table border" id="create-order-sparepart">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 100px;">Category</th>
                        <th class="text-center">Item</th>
                        <th class="text-center" style="width: 150px;">stock</th>
                        <th class="text-center" style="width: 100px;">Price</th>
                        <th class="text-center" style="width: 100px;">Action</th>
                    </tr>
                </thead>
                <tbody>
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
                            <input type="text" name="stock_item[]" class="form-control searchInput"
                                placeholder="Enter Label Here..." oninput="getSearchData(this)">
                            <div class="dropdown-menu show searchResults"
                                style="display: none; max-height: 200px; overflow-y: auto;">
                            </div>
                        </td>
                        <td>
                            <input type="number" class="form-control" name="stock_quantity[]" min="1"
                                step="1" value="1">
                        </td>
                        <td>
                            <input type="number" class="form-control" value="1" name="stock_price[]">
                        </td>
                        <td>

                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td>
                            <button type="button" class="btn btn-success rounded" onclick="addStockRowmodel()">Add
                                Row</button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>


        <div class="row">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 my-2 mx-0">
                <button type="submit"
                    class="btn btn-success addStockSubmitButton">{{ trans('message.SUBMIT') }}</button>
            </div>
        </div>

    </form>
    <script>
        function submitAddStockForm(event, formId) {
            event.preventDefault();

            let form = $('#' + formId);
            let url = form.attr('action');
            let formData = form.serialize();

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                success: function(response) {

                    toastr.clear();

                    if (response.status == 1) {
                        toastr.success(response.message, 'Success');
                        $("#bs-example-modal-xl").modal("hide");
                        $(".modal-body-data").html('');
                        getJobCardLabel();
                    }

                },
                error: function(xhr) {
                    toastr.clear();

                    let errorMessages = '';
                    let errors = xhr.responseJSON?.errors || {};
                    for (let field in errors) {
                        if (errors.hasOwnProperty(field)) {
                            let messages = errors[field];
                            messages.forEach(function(message) {
                                errorMessages += message + '<br>';
                            });
                        }
                    }
                    toastr.error(errorMessages, 'Validation Errors');
                }
            });
        }
    </script>
