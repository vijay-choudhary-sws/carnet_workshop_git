@extends('layouts.app')
@section('content')
    <style>
        .select2-container--default .select2-selection--multiple,
        .select2-container--default .select2-selection--single {
            width: auto !important;
        }

        .table>tbody>tr>td {
            width: auto !important;
        }

        .itemname span.select2.select2-container.select2-container--default {
            width: 100% !important;
        }

        /* .toast {
                font-size: 16px;
                border-radius: 5px;
            } */
    </style>
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><a href="{{ route('purchase_spare_part.list') }}"
                                id=""><i class="">
                                    <img src="{{ URL::asset('public/supplier/Back Arrow.png') }}"
                                        class="back-arrow"></i><span class="titleup">
                                    {{ trans('message.Purchase Spare Parts') }}</span></a>
                        </div>
                        @include('dashboard.profile')
                    </nav>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                    <div class="x_content">
                        <div class="x_panel">
                            <br />
                            <form id="purchase-spare-part-Form" action="{{ route('purchase_spare_part.store') }}"
                                method="post" enctype="multipart/form-data" data-parsley-validate
                                class="form-horizontal form-label-left purchase-spare-partForm">

                                <div>
                                    <div class="mb-3">
                                        <button type="button" class="btn btn-success rounded" onclick="addRow()">Add
                                            Row</button>
                                    </div>
                                    <table class="table border">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 150px;">Category</th>
                                                <th class="text-center">Item</th>
                                                <th class="text-center" style="width: 100px;">Quantity</th>
                                                <th class="text-center" style="width: 100px;">Price</th>
                                                <th class="text-center" style="width: 100px;">Amount</th>
                                                <th class="text-center" style="width: 100px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr data-row="1">
                                                <td style="width: 150px;">
                                                    <div>
                                                        <select class="form-control select2" name="category[]"
                                                            onchange="getItem(this)" style="width:200px">
                                                            <option value="" selected disabled>--Select Category --
                                                            </option>
                                                            <option value="1">Accessory</option>
                                                            <option value="2">Parts</option>
                                                            <option value="3">Tools</option>
                                                            <option value="4">Lubes</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td class="itemname">
                                                    <div>
                                                        <select class="form-control select2 " name="item[]"
                                                            onchange="getStock(this)">
                                                            <option value="" selected disabled>--Select Item --
                                                            </option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" name="quantity[]"
                                                        min="1" step="1" max="0"
                                                        oninput="checkMax(this)">
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control bg-light" value="0"
                                                        name="price[]" readonly>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control bg-light" value="0"
                                                        name="total_price[]" readonly>
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" class="text-end">Total</td>
                                                <td>
                                                    <input type="text" class="form-control bg-light" name="total_amount"
                                                        value="0" id="totalAmount" readonly>
                                                </td>
                                                <td></td>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /page content -->



    <!-- Scripts starting -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>


    <script>
        $(document).ready(function() {

            $('.select2').select2();

            $('#purchase-spare-part-Form').on('submit', function(e) {
                e.preventDefault();

                let form = $(this);
                let url = form.attr('action');
                let formData = form.serialize();

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    success: function(response) {

                        toastr.clear();

                        if (response.status === 'success') {
                            toastr.success(response.message, 'Success');
                            window.location.replace('{{ route('purchase_spare_part.list') }}');
                        }
                    },
                    error: function(xhr) {

                        toastr.clear();

                        let errorMessages = '';
                        let errors = xhr.responseJSON.errors;
                        for (let field in errors) {
                            if (errors.hasOwnProperty(field)) {
                                let messages = errors[field];
                                messages.forEach(function(message) {
                                    errorMessages += message +
                                        '<br>';
                                });
                            }
                        }
                        toastr.error(errorMessages, 'Validation Errors');
                    }
                });
            });
        });

        function getItem(e) {
            let cateId = $(e).val();
            if (!cateId) {
                toastr.info("Please select a valid category.", 'INFO')
                return;
            }
            let row = $(e).parents('tr').attr('data-row');
            if (!row) {
                console.error("Row attribute is missing in the parent <tr>.");
                return;
            }

            let itemId = [];
            $('select[name="item[]"]').each(function() {
                let value = parseFloat($(this).val());
                if (!isNaN(value)) {
                    itemId.push(value);
                }
            });

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('purchase_spare_part.getItem') }}",
                method: "post",
                data: {
                    cat_id: cateId,
                    item_id: itemId
                },
                success: function(res) {
                    if (res.status == 1) {
                        $('select[name="item[]"]').eq(row - 1).html(res.html);
                    } else {
                        toastr.info(res.msg, "INFO");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error: " + error);
                    console.error("Status: " + status);
                    console.error("Response: " + xhr.responseText);
                }
            });
        }

        function getStock(e) {
            let stockId = $(e).val();
            if (!stockId) {
                toastr.info("Please select a valid item.", "INFO");
                return;
            }
            let row = $(e).parents('tr').attr('data-row');
            if (!row) {
                console.error("Row attribute is missing in the parent <tr>.");
                return;
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('purchase_spare_part.getAmount') }}",
                method: "post",
                data: {
                    stock_id: stockId,
                    row_id: row,
                },
                success: function(res) {
                    if (res.status == 1) {
                        $('input[name="quantity[]"]').eq(row - 1).attr('max', res.stock).val('1');
                        $('input[name="price[]"]').eq(row - 1).val(res.price);
                        $('input[name="total_price[]"]').eq(row - 1).val(res.price);
                        totalAmount();
                    } else {
                        toastr.info(res.msg, "INFO");
                    }
                },
                error: function(xhr, status, error) {
                    {{-- console.error("Error: " + error);
                    console.error("Status: " + status);
                    console.error("Response: " + xhr.responseText); --}}
                }
            });
        }

        function checkMax(e) {
            let value = parseInt($(e).val(), 10);
            let maxValue = parseInt($(e).attr('max'), 10);
            if (value < 0) {
                $(e).val(0);
            } else if (value > maxValue) {
                $(e).val(maxValue);
            }
            getPrice(e);
        }

        function getPrice(e) {

            let qty = parseInt($(e).val(), 10);

            if (isNaN(qty) || qty < 0) {
                toastr.info("Please enter a valid quantity.", "INFO");
                return;
            }

            let row = $(e).parents('tr').attr('data-row');
            if (!row) {
                console.error("Row attribute is missing in the parent <tr>.");
                return;
            }

            let price = parseFloat($('input[name="price[]"]').eq(row - 1).val());

            if (isNaN(price) || price < 0) {
                toastr.info("Price is invalid.", "INFO");
                return;
            }

            let totalPrice = price * qty;
            name = "total_price[]"
            $('input[name="total_price[]"]').eq(row - 1).val(totalPrice);

            totalAmount();
        }

        function totalAmount() {
            let totalAmount = 0;
            $('input[name="total_price[]"]').each(function() {
                let value = parseFloat($(this).val());
                if (!isNaN(value)) {
                    totalAmount += value;
                }
            });

            $('#totalAmount').val(totalAmount);
        }

        function addRow() {

            let isValid = true;

            $('select[name="category[]"]').each(function() {
                let value = $(this).val();
                if (!value) {
                    toastr.info('Category cannot be null.', "INFO");
                    isValid = false;
                    return false;
                }
            });

            $('select[name="item[]"]').each(function() {
                let value = $(this).val();
                if (!value) {
                    toastr.info('Item cannot be null.', "INFO");
                    isValid = false;
                    return false;
                }
            });

            $('select[name="quantity[]"]').each(function() {
                let value = $(this).val();
                if (!value) {
                    toastr.info('Qty cannot be null.', "INFO");
                    isValid = false;
                    return false;
                }
            });

            if (!isValid) {
                return;
            }

            let row = $('tbody tr').length + 1;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('purchase_spare_part.addRow') }}",
                method: "post",
                data: {
                    row: row
                },
                success: function(res) {
                    if (res.status == 1) {
                        $('table tbody').append(res.html);
                        $('.select2').select2();
                    } else {
                        toastr.info(res.msg, "INFO");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error: " + error);
                    console.error("Status: " + status);
                    console.error("Response: " + xhr.responseText);
                }
            });
        }

        function deleteRow(e) {
            $(e).parents('tr').remove();
            let $i = 1;
            $('tbody tr').each(function() {
                $(this).attr('data-row', $i++);
            });
            totalAmount();
        }
    </script>

    <!-- Form field validation -->
    {{-- {!! JsValidator::formRequest('App\Http\Requests\PurcahseSparePartRequest', '#purchase-spare-part-Form') !!}
    <script type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js') }}"></script> --}}
@endsection
