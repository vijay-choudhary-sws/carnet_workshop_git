@extends('layouts.app')
@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
                            <a href="{{ route('sparepart.list') }}" id=""><span class="titleup"><img
                                        src="{{ URL::asset('public/supplier/Back Arrow.png') }}" class="back-arrow">
                                    {{ trans('message.Edit Spare Part') }}</span></a>
                        </div>
                        @include('dashboard.profile')
                    </nav>
                </div>
            </div>
            <div class="clearfix"></div>
            @include('success_message.message')
            <div class="row">
                <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                    <div class="x_content">
                        <div class="x_panel">
                            <br />
                            <form id="vehicleModelAdd-Form" action="{{ route('sparepart.update') }}" method="post"
                                enctype="multipart/form-data" data-parsley-validate
                                class="form-horizontal form-label-left vehicleModelAddForm">
                                <input type="hidden" name="id" value="{{$sparePart->id}}">

                                <div class="row row-mb-0">
                                    <label
                                        class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 text-center"
                                        for="name">{{ trans('message.Name') }} <label class="color-danger">*</label>
                                    </label>
                                    <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                                        <select class="form-control col-md-7 col-xs-12 select2-name" name="name" id="name">
                                            @foreach($sparePartLabels as $sparePartLabel)
                                                <option value="{{ $sparePartLabel->title }}" {{ $sparePartLabel->title == $sparePart->name ? 'selected' : '' }}>{{ $sparePartLabel->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 has-feedback">
                                        <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                            for="image">{{ trans('message.Image') }}</label>
                                        <div class="col-8 ps-3">
                                            <input type="file" id="image" name="image"
                                                class="form-control chooseImage">

                                            <img src="{{ asset('public/uploads/spare_parts/'.$sparePart->image) }}" id="imagePreview"
                                                alt="Spare Part Image" class="mt-2" style="width: 52px;">
                                        </div>
                                        <input type="hidden" name="image_old" value="{{$sparePart->image}}" >
                                    </div>
                                </div>
                                <div class="row row-mb-0">
                                    <label
                                        class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 text-center"
                                        for="part-no">{{ trans('message.Part no.') }}
                                    </label>
                                    <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                                        <input type="text" name="part_number" id="part-no"
                                            placeholder="{{ trans('message.Enter Part No.') }}"
                                            class="form-control col-md-7 col-xs-12" maxlength="30"
                                            value="{{ $sparePart->part_number ?? '' }}">
                                    </div>

                                    <label
                                        class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 text-center"
                                        for="unit">
                                        {{ trans('message.Unit') }}
                                        <span class="color-danger">*</span>
                                    </label>

                                    <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                                        <select name="unit_id" id="unit"
                                            class="form-control col-md-7 col-xs-12 vehicle_types form-select">
                                            @if (!empty($units))
                                                @foreach ($units as $unit)
                                                    <option value="{{ $unit->id }}"
                                                        {{ $sparePart->unit_id == $unit->id ? 'selected' : '' }}>
                                                        {{ $unit->title }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                </div>

                                <div class="row row-mb-0">
                                    <label
                                        class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 text-center"
                                        for="brand">{{ trans('message.Brand') }} 
                                    </label>
                                    <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                                        <input type="text" name="brand" id="brand"
                                            placeholder="{{ trans('message.Enter Brand') }}"
                                            class="form-control col-md-7 col-xs-12" maxlength="30"
                                            value="{{ $sparePart->brand ?? '' }}">
                                    </div>
                                    <label
                                        class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 text-center"
                                        for="suitable-for">{{ trans('message.Suitable For') }} 
                                    </label>
                                    <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                                        <input type="text" name="suitable_for" id="suitable for"
                                            placeholder="{{ trans('message.Enter Suitable For') }}"
                                            class="form-control col-md-7 col-xs-12" maxlength="30"
                                            value="{{ $sparePart->suitable_for ?? '' }}">

                                    </div>
                                </div>

                                <div class="row row-mb-0">
                                    <label
                                        class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 text-center"
                                        for="price">{{ trans('message.Price') }} <label class="color-danger">*</label>
                                    </label>
                                    <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                                        <input type="number" name="price" id="price"
                                            placeholder="{{ trans('message.Enter Price') }}"
                                            class="form-control col-md-7 col-xs-12" maxlength="30"
                                            value="{{ $sparePart->price ?? '' }}">
                                    </div>
                                    <label
                                        class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 text-center"
                                        for="discount">{{ trans('message.Discount') }} 
                                    </label>
                                    <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                                        <input type="number" name="discount" id="discount"
                                            placeholder="{{ trans('message.Enter Discount') }}"
                                            class="form-control col-md-7 col-xs-12" maxlength="30"
                                            value="{{ $sparePart->discount ?? '' }}">

                                    </div>
                                </div>

                                <div class="row row-mb-0">
                                    <label
                                        class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 text-center"
                                        for="stock">{{ trans('message.Stock') }} <label class="color-danger">*</label>
                                    </label>
                                    <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                                        <input type="number" name="stock" id="stock"
                                            placeholder="{{ trans('message.Enter Stock') }}"
                                            class="form-control col-md-7 col-xs-12" maxlength="30"
                                            value="{{ $sparePart->stock ?? '' }}">
                                    </div>
                                    <label
                                        class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 text-center"
                                        for="sp_type">{{ trans('message.SP Type') }} 
                                    </label>
                                    <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                                        <select name="sp_type" id="sp_type"
                                            class="form-control col-md-7 col-xs-12 vehicle_types form-select">
                                            <option value="0" {{ $sparePart->sp_type == 0 ? 'selected' : '' }}>
                                                {{ trans('message.New') }}</option>
                                            <option value="1" {{ $sparePart->sp_type == 1 ? 'selected' : '' }}>
                                                {{ trans('message.Used') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row row-mb-0">
                                    <label
                                        class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 text-center"
                                        for="description">{{ trans('message.description') }} 
                                    </label>
                                    <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                                        <textarea name="description" id="description" placeholder="{{ trans('message.Enter Description') }}"
                                            class="form-control col-md-7 col-xs-12" maxlength="30">{{ $sparePart->description ?? '' }}</textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 my-2 mx-0">
                                        <button type="submit"
                                            class="btn btn-success vehicleModelAddSubmitButton">{{ trans('message.SUBMIT') }}</button>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {

             $('.select2-name').select2({
                tags: true,
                placeholder: 'Select a Name or add a new one',
                allowClear: true,
                createTag: function(params) {
                    var term = params.term;
                    if (term === '') {
                        return null;
                    }
                    return {
                        id: term,
                        text: term
                    };
                },
                minimumInputLength: 1
            });

            /*Form submit time check validation for Custom Fields */
            $('body').on('click', '.vehicleModelAddSubmitButton', function(e) {
                $('#vehicleModelAdd-Form input, #vehicleModelAdd-Form select, #vehicleModelAdd-Form textarea')
                    .each(

                        function(index) {
                            var input = $(this);

                            if (input.attr('name') == "vehicaltypes" || input.attr('name') ==
                                "vehicalbrand") {
                                if (input.val() == "") {
                                    return false;
                                }
                            } else if (input.attr('isRequire') == 'required') {
                                var rowid = (input.attr('rows_id'));
                                var labelName = (input.attr('fieldnameis'));

                                if (input.attr('type') == 'textbox' || input.attr('type') == 'textarea') {
                                    if (input.val() == '' || input.val() == null) {
                                        $('.common_value_is_' + rowid).val("");
                                        $('#common_error_span_' + rowid).text(labelName + " : " + msg1);
                                        $('#common_error_span_' + rowid).css({
                                            "display": ""
                                        });
                                        $('.error_customfield_main_div_' + rowid).addClass('has-error');
                                        e.preventDefault();
                                        return false;
                                    } else if (!input.val().replace(/\s/g, '').length) {
                                        $('.common_value_is_' + rowid).val("");
                                        $('#common_error_span_' + rowid).text(labelName + " : " + msg2);
                                        $('#common_error_span_' + rowid).css({
                                            "display": ""
                                        });
                                        $('.error_customfield_main_div_' + rowid).addClass('has-error');
                                        e.preventDefault();
                                        return false;
                                    } else if (!input.val().match(/^[(a-zA-Z0-9\s)\p{L}]+$/u)) {
                                        $('.common_value_is_' + rowid).val("");
                                        $('#common_error_span_' + rowid).text(labelName + " : " + msg3);
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
                                        $('#common_error_span_' + rowid).text(labelName + " : " + msg1);
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
                                        $('#common_error_span_' + rowid).text(labelName + " : " + msg1);
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
                            $('#common_error_span_' + rowid).text(labelName + " : " + msg1);
                            $('#common_error_span_' + rowid).css({
                                "display": ""
                            });
                            $('.error_customfield_main_div_' + rowid).addClass('has-error');
                        } else if (valueIs.match(/^\s+/)) {
                            $('.common_value_is_' + rowid).val("");
                            $('#common_error_span_' + rowid).text(labelName + " : " + msg4);
                            $('#common_error_span_' + rowid).css({
                                "display": ""
                            });
                            $('.error_customfield_main_div_' + rowid).addClass('has-error');
                        } else if (!valueIs.match(/^[(a-zA-Z0-9\s)\p{L}]+$/u)) {
                            $('.common_value_is_' + rowid).val("");
                            $('#common_error_span_' + rowid).text(labelName + " : " + msg3);
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
                            $('#common_error_span_' + rowid).text(labelName + " : " + msg1);
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
                                $('#common_error_span_' + rowid).text(labelName + " : " + msg4);
                                $('#common_error_span_' + rowid).css({
                                    "display": ""
                                });
                                $('.error_customfield_main_div_' + rowid).addClass('has-error');
                            } else if (!valueIs.match(/^[(a-zA-Z0-9\s)\p{L}]+$/u)) {
                                $('.common_value_is_' + rowid).val("");
                                $('#common_error_span_' + rowid).text(labelName + " : " + msg3);
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
                        $('#common_error_span_' + rowid).text(labelName + " : " + msg1);
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
                        $('#common_error_span_' + rowid).text(labelName + " : " + msg1);
                        $('#common_error_span_' + rowid).css({
                            "display": ""
                        });
                        $('.error_customfield_main_div_' + rowid).addClass('has-error');
                    }
                }
            });
            $("#image").change(function() {
                readUrl(this);
                $("#imagePreview").css("display", "block");
            });


            $('body').on('change', '.chooseImage', function() {
                var imageName = $(this).val();
                var imageExtension = /(\.jpg|\.jpeg|\.png)$/i;

                if (imageExtension.test(imageName)) {
                    $('.imageHideShow').css({
                        "display": ""
                    });
                } else {
                    $('.imageHideShow').css({
                        "display": "none"
                    });
                }
            });
        });

        function readUrl(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#imagePreview').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <!-- Form field validation -->
    {!! JsValidator::formRequest('App\Http\Requests\SparePartRequest', '#vehicleModelAdd-Form') !!}
    <script type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js') }}"></script>

@endsection
