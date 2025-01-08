<div class="modal-header">
    <h4 class="modal-title" id="myLargeModalLabel">Create Purchase Order</h4>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="purchase-spare-part-Form" action="{{ route('purchase_spare_part.store') }}" onsubmit="submitFormAjax(event, 'purchase-spare-part-Form')" method="post" class="form-horizontal form-label-left purchase-spare-partForm">
    <div>
        <div class="mb-3 text-end pe-3 pt-3">
            <button type="button" class="btn btn-success rounded" onclick="addRowmodel()">Add
                Row</button>
        </div>
        <table class="table border" id="create-order-sparepart">
            <thead>
                <tr>
                    <th class="text-center" style="width: 100px;">Category</th>
                    <th class="text-center">Item</th>
                    <th class="text-center" style="width: 150px;">Quantity</th>
                    <th class="text-center" style="width: 100px;">Price</th>
                    <th class="text-center" style="width: 100px;">Amount</th>
                    <th class="text-center" style="width: 100px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr data-row="1">
                    <td style="width: 100px;">
                        <div>
                            <select class="form-control" name="category[]" onchange="getItem(this)">
                                <option value="" selected disabled>--Select Category --
                                </option>
                                <option value="1">Accessory</option>
                                <option value="2">Parts</option>
                                <option value="3">Tools</option>
                                <option value="4">Lubes</option>
                            </select>
                        </div>
                    </td>
                    <td style="width: 150px;">

                        <select class="form-control w-100 " name="item[]" onchange="getStock(this)">
                            <option value="" selected disabled>--Select Item --
                            </option>
                        </select>
                    </td>
                    <td>
                        <input type="number" class="form-control" name="quantity[]" min="1" step="1"
                            max="0" oninput="checkMax(this)">
                    </td>
                    <td>
                        <input type="number" class="form-control bg-light" value="0" name="price[]" readonly>
                    </td>
                    <td>
                        <input type="number" class="form-control bg-light" value="0" name="total_price[]"
                            readonly>
                    </td>
                    <td>

                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end">Total</td>
                    <td>
                        <input type="text" class="form-control bg-light" name="total_amount" value="0"
                            id="totalAmount" readonly>
                        </td>
                        <td>
                            <a href="javascript:void(0)" id="pay-for-PO" class="btn btn-success btn-sm border-0 text-white" onclick="createCustomer()" style="display: none;">Pay Now</a>
                            <input type="hidden" name="customer_id" id="customer_id" value="">
                            <input type="hidden" name="payment_id" id="payment_id" value="">
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>


    <div class="row">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 my-2 mx-0">
            <button type="submit"
                class="btn btn-success purchase-spare-partSubmitButton">{{ trans('message.SUBMIT') }}</button>
        </div>
    </div>

</form>
