@extends('layouts.app')
@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><a href="{!! url('/expense/list') !!}"
                                id=""><i class=""><img
                                        src="{{ URL::asset('public/supplier/Back Arrow.png') }}"
                                        class="back-arrow"></i><span class="titleup">
                                    {{ trans('message.Add Expense') }}</span></a>
                        </div>
                        @include('dashboard.profile')
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_content">
                        <form id="expenseAddForm" method="post" action="{{ route('expense.store') }}"
                            enctype="multipart/form-data" class="form-horizontal upperform addExpenseForm">
                            <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                                <h4><b>{{ trans('message.EXPENSE DETAILS') }}</b></h4>
                                <hr style="margin-top:0px;">
                                <p class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12"></p>
                            </div>

                            <div class="row mt-3 row-mb-0">

                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                        for="category-id">{{ trans('message.Category') }} <label class="color-danger">*</label>
                                    </label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <select name="category_id" id="category-id" class="form-control form-select select2"
                                            required>
                                            <option value="" selected disabled>{{ trans('message.Select Category') }}
                                            </option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-primary btn-sm border-0 text-white"
                                        data-bs-toggle="modal" data-bs-target="#categoryModal">Add</button>
                                </div>
                            </div>
                            <div class="row mt-3 row-mb-0">

                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                        for="supplier">Supplier Name <label class="color-danger">*</label></label>

                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <select class="form-control form-select select2" name="supplier_id">
                                            <option value="" selected disabled>-- select supplier --</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">
                                                    {{ $supplier->name . ' ' . $supplier->lastname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row row-mb-0">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                        for="bill_number">Bill Number<label class="color-danger">*</label>
                                    </label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 date ">
                                        <input type="text" id="bill_number" name="bill_number" class="form-control"
                                            value="" placeholder="Enter Bill Number Here..." required />
                                    </div>
                                </div>
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                        for="date">Expense Date<label class="color-danger">*</label>
                                    </label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 date ">
                                        <input type="text" id="outdate_gatepass" name="date" autocomplete="off"
                                            class="form-control expenseDate datepicker" value=""
                                            placeholder="<?php echo getDatepicker(); ?>" onkeypress="return false;" required />
                                    </div>
                                </div>
                            </div>
                            <div class="row row-mb-0">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                        for="total-amount">Total Amount<label class="color-danger">*</label>
                                    </label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 date ">
                                        <input type="number" id="total-amount" class="form-control text-input"
                                            value="0" name="total_amount" placeholder="Enter Total Amount Here..."
                                            maxlength="10" required oninput="getTotal()">
                                    </div>
                                </div>
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                        for="paid-amount">Paid Amount<label class="color-danger">*</label>
                                    </label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 date ">
                                        <input type="number" id="paid-amount" class="form-control text-input"
                                            value="0" name="paid_amount" placeholder="Enter Paid Amount Here..."
                                            maxlength="10" required oninput="getTotal()">
                                    </div>
                                </div>
                            </div>
                            <div class="row row-mb-0">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                        for="balance-amount">Balance Amount</label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 date ">
                                        <input type="number" id="balance-amount"
                                            class="form-control text-input bg-secondary text-white " value="0"
                                            name="balance_amount" placeholder="Enter Balance Amount Here..." readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row row-mb-0">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                        for="description">Description</label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 date ">
                                        <textarea name="description" class="form-control" id="description" placeholder="Enter Description Here..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div
                                    class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 my-1 mx-0 pl-40 pr-15">
                                    <button type="submit"
                                        class="btn btn-success addExpenseSubmitButton">{{ trans('message.SUBMIT') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /page content -->

    <!-- Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="categoryModalLabel">Add Category</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" method="post">
                        @csrf
                        <label for="category" class="form-label">Category Label</label>
                        <input type="text" name="category" id="category" class="form-control"
                            placeholder="Enter Category Label Here...">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm border-0 text-white"
                        data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-sm border-0 text-white"
                        onclick="addCategory()">Save Category</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
            /*JQuery for create extra textbox and extra textbox also remove*/

            $("body").on("click", ".del", function() {
                $(this).parents('.remove_fields').remove();
            });

            $('.datepicker').datetimepicker({
                format: "<?php echo getDatepicker(); ?>",
                todayBtn: true,
                autoclose: 1,
                minView: 2,
                startDate: new Date(),
                language: "{{ getLangCode() }}",
            });



            /*If select box have value then error msg and has error class remove*/
            $('body').on('change', '.expenseDate', function() {

                var dateValue = $(this).val();

                if (dateValue != null) {
                    $('#outdate_gatepass-error').css({
                        "display": "none"
                    });
                }

                if (dateValue != null) {
                    $(this).parent().parent().removeClass('has-error');
                }
            });



            $('body').on('keyup', '.mainLabel', function() {

                var mainLabelName = $(this).val();

                if (!mainLabelName.replace(/\s/g, '').length) {
                    $(this).val("");
                }
            });


            $('body').on('keyup', '.extraExtenseTextbox', function() {

                var extraexpenseVal = $(this).val();

                if (!extraexpenseVal.replace(/\s/g, '').length) {
                    $(this).val(0);
                }
            });


            /*Custom Field manually validation*/
            var msg31 = "{{ trans('message.field is required') }}";
            var msg32 = "{{ trans('message.Only blank space not allowed') }}";
            var msg33 = "{{ trans('message.Special symbols are not allowed.') }}";
            var msg34 = "{{ trans('message.At first position only alphabets are allowed.') }}";

            /*Form submit time check validation for Custom Fields */
            $('body').on('click', '.addExpenseSubmitButton', function(e) {
                $('#expenseAddForm input, #expenseAddForm select, #expenseAddForm textarea').each(

                    function(index) {
                        var input = $(this);

                        if (input.attr('name') == "main_label" || input.attr('name') == "status" ||
                            input.attr('name') ==
                            "date") {
                            if (input.val() == "") {
                                return false;
                            }
                        } else if (input.attr('isRequire') == 'required') {
                            var rowid = (input.attr('rows_id'));
                            var labelName = (input.attr('fieldnameis'));

                            if (input.attr('type') == 'textbox' || input.attr('type') == 'textarea') {
                                if (input.val() == '' || input.val() == null) {
                                    $('.common_value_is_' + rowid).val("");
                                    $('#common_error_span_' + rowid).text(labelName + " : " + msg31);
                                    $('#common_error_span_' + rowid).css({
                                        "display": ""
                                    });
                                    $('.error_customfield_main_div_' + rowid).addClass('has-error');
                                    e.preventDefault();
                                    return false;
                                } else if (!input.val().replace(/\s/g, '').length) {
                                    $('.common_value_is_' + rowid).val("");
                                    $('#common_error_span_' + rowid).text(labelName + " : " + msg32);
                                    $('#common_error_span_' + rowid).css({
                                        "display": ""
                                    });
                                    $('.error_customfield_main_div_' + rowid).addClass('has-error');
                                    e.preventDefault();
                                    return false;
                                } else if (!input.val().match(/^[(a-zA-Z0-9\s)\p{L}]+$/u)) {
                                    $('.common_value_is_' + rowid).val("");
                                    $('#common_error_span_' + rowid).text(labelName + " : " + msg33);
                                    $('#common_error_span_' + rowid).css({
                                        "display": ""
                                    });
                                    $('.error_customfield_main_div_' + rowid).addClass('has-error');
                                    e.preventDefault();
                                    return false;
                                }
                            } else if (input.attr('type') == 'checkbox') {
                                var ids = input.attr('custm_isd');
                                if ($(".required_checkbox_" + ids).is(':checked')) {
                                    $('#common_error_span_' + rowid).css({
                                        "display": "none"
                                    });
                                    $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                                    $('.required_checkbox_parent_div_' + ids).css({
                                        "color": ""
                                    });
                                    $('.error_customfield_main_div_' + ids).removeClass('has-error');
                                } else {
                                    $('#common_error_span_' + rowid).text(labelName + " : " + msg31);
                                    $('#common_error_span_' + rowid).css({
                                        "display": ""
                                    });
                                    $('.error_customfield_main_div_' + rowid).addClass('has-error');
                                    $('.required_checkbox_' + ids).css({
                                        "outline": "2px solid #a94442"
                                    });
                                    $('.required_checkbox_parent_div_' + ids).css({
                                        "color": "#a94442"
                                    });
                                    e.preventDefault();
                                    return false;
                                }
                            } else if (input.attr('type') == 'date') {
                                if (input.val() == '' || input.val() == null) {
                                    $('.common_value_is_' + rowid).val("");
                                    $('#common_error_span_' + rowid).text(labelName + " : " + msg31);
                                    $('#common_error_span_' + rowid).css({
                                        "display": ""
                                    });
                                    $('.error_customfield_main_div_' + rowid).addClass('has-error');
                                    e.preventDefault();
                                    return false;
                                } else {
                                    $('#common_error_span_' + rowid).css({
                                        "display": "none"
                                    });
                                    $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                                }
                            }
                        } else if (input.attr('isRequire') == "") {
                            //Nothing to do
                        }
                    }
                );
            });


            /*Anykind of input time check for validation for Textbox, Date and Textarea*/
            $('body').on('keyup', '.common_simple_class', function() {

                var rowid = $(this).attr('rows_id');
                var valueIs = $('.common_value_is_' + rowid).val();
                var requireOrNot = $('.common_value_is_' + rowid).attr('isrequire');
                var labelName = $('.common_value_is_' + rowid).attr('fieldnameis');
                var inputTypes = $('.common_value_is_' + rowid).attr('type');

                if (requireOrNot != "") {
                    if (inputTypes != 'radio' && inputTypes != 'checkbox' && inputTypes != 'date') {
                        if (valueIs == "") {
                            $('.common_value_is_' + rowid).val("");
                            $('#common_error_span_' + rowid).text(labelName + " : " + msg31);
                            $('#common_error_span_' + rowid).css({
                                "display": ""
                            });
                            $('.error_customfield_main_div_' + rowid).addClass('has-error');
                        } else if (valueIs.match(/^\s+/)) {
                            $('.common_value_is_' + rowid).val("");
                            $('#common_error_span_' + rowid).text(labelName + " : " + msg34);
                            $('#common_error_span_' + rowid).css({
                                "display": ""
                            });
                            $('.error_customfield_main_div_' + rowid).addClass('has-error');
                        } else if (!valueIs.match(/^[(a-zA-Z0-9\s)\p{L}]+$/u)) {
                            $('.common_value_is_' + rowid).val("");
                            $('#common_error_span_' + rowid).text(labelName + " : " + msg33);
                            $('#common_error_span_' + rowid).css({
                                "display": ""
                            });
                            $('.error_customfield_main_div_' + rowid).addClass('has-error');
                        } else {
                            $('#common_error_span_' + rowid).css({
                                "display": "none"
                            });
                            $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                        }
                    } else if (inputTypes == 'date') {
                        if (valueIs != "") {
                            $('#common_error_span_' + rowid).css({
                                "display": "none"
                            });
                            $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                        } else {
                            $('.common_value_is_' + rowid).val("");
                            $('#common_error_span_' + rowid).text(labelName + " : " + msg31);
                            $('#common_error_span_' + rowid).css({
                                "display": ""
                            });
                            $('.error_customfield_main_div_' + rowid).addClass('has-error');
                        }
                    } else {
                        //alert("Yes i am radio and checkbox");
                    }
                } else {
                    if (inputTypes != 'radio' && inputTypes != 'checkbox' && inputTypes != 'date') {
                        if (valueIs != "") {
                            if (valueIs.match(/^\s+/)) {
                                $('.common_value_is_' + rowid).val("");
                                $('#common_error_span_' + rowid).text(labelName + " : " + msg34);
                                $('#common_error_span_' + rowid).css({
                                    "display": ""
                                });
                                $('.error_customfield_main_div_' + rowid).addClass('has-error');
                            } else if (!valueIs.match(/^[(a-zA-Z0-9\s)\p{L}]+$/u)) {
                                $('.common_value_is_' + rowid).val("");
                                $('#common_error_span_' + rowid).text(labelName + " : " + msg33);
                                $('#common_error_span_' + rowid).css({
                                    "display": ""
                                });
                                $('.error_customfield_main_div_' + rowid).addClass('has-error');
                            } else {
                                $('#common_error_span_' + rowid).css({
                                    "display": "none"
                                });
                                $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                            }
                        } else {
                            $('#common_error_span_' + rowid).css({
                                "display": "none"
                            });
                            $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                        }
                    }
                }
            });


            /*For required checkbox checked or not*/
            $('body').on('click', '.checkbox_simple_class', function() {

                var rowid = $(this).attr('rows_id');
                var requireOrNot = $('.common_value_is_' + rowid).attr('isrequire');
                var labelName = $('.common_value_is_' + rowid).attr('fieldnameis');
                var inputTypes = $('.common_value_is_' + rowid).attr('type');
                var custId = $('.common_value_is_' + rowid).attr('custm_isd');

                if (requireOrNot != "") {
                    if ($(".required_checkbox_" + custId).is(':checked')) {
                        $('.required_checkbox_' + custId).css({
                            "outline": ""
                        });
                        $('.required_checkbox_' + custId).css({
                            "color": ""
                        });
                        $('#common_error_span_' + rowid).css({
                            "display": "none"
                        });
                        $('.required_checkbox_parent_div_' + custId).css({
                            "color": ""
                        });
                        $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                    } else {
                        $('#common_error_span_' + rowid).text(labelName + " : " + msg31);
                        $('.required_checkbox_' + custId).css({
                            "outline": "2px solid #a94442"
                        });
                        $('.required_checkbox_' + custId).css({
                            "color": "#a94442"
                        });
                        $('#common_error_span_' + rowid).css({
                            "display": ""
                        });
                        $('.required_checkbox_parent_div_' + custId).css({
                            "color": "#a94442"
                        });
                        $('.error_customfield_main_div_' + rowid).addClass('has-error');
                    }
                }
            });


            $('body').on('change', '.date_simple_class', function() {

                var rowid = $(this).attr('rows_id');
                var valueIs = $('.common_value_is_' + rowid).val();
                var requireOrNot = $('.common_value_is_' + rowid).attr('isrequire');
                var labelName = $('.common_value_is_' + rowid).attr('fieldnameis');
                var inputTypes = $('.common_value_is_' + rowid).attr('type');
                var custId = $('.common_value_is_' + rowid).attr('custm_isd');

                if (requireOrNot != "") {
                    if (valueIs != "") {
                        $('#common_error_span_' + rowid).css({
                            "display": "none"
                        });
                        $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                    } else {
                        $('#common_error_span_' + rowid).text(labelName + " : " + msg31);
                        $('#common_error_span_' + rowid).css({
                            "display": ""
                        });
                        $('.error_customfield_main_div_' + rowid).addClass('has-error');
                    }
                }
            });
        });

        function getTotal() {
            let amount = parseFloat($('#total-amount').val()) || 0;
            let paid = parseFloat($('#paid-amount').val()) || 0;

            let balance = amount - paid;

            $('#balance-amount').val(balance.toFixed(2));

        }

        function addCategory() {
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('expense.add.category') }}",
                method: "post",
                data: {
                    title: $('#category').val(),
                },
                success: function(res) {
                    if(res.status == 1){
                        $('#categoryModal').modal('hide');
                        $('#category').val('');
                        $('#category-id').append(res.html);
                        $('.select2').select2();
                        Swal.fire({
                            title: "Saved!",
                            text: res.msg,
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }else{
                        Swal.fire({
                            title: "Error!",
                            text: res.msg,
                            icon: "error",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                            title: "Error!",
                            text: "Something Error Found! Please try again.",
                            icon: "error",
                            showConfirmButton: false,
                            timer: 1500
                        });
                }
            });
        }
    </script>


    <!-- Form field validation -->
    {!! JsValidator::formRequest('App\Http\Requests\StoreExpenseAddEditFormRequest', '#expenseAddForm') !!}
    <script type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js') }}"></script>
@endsection
