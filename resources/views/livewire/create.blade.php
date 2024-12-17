 <div class="x_panel AddSeviceDescription mb-0">
                <div class="x_content">
                    <div class="panel panel-default">
                        <div class="panel-heading step1 titleup">{{ trans('message.STEP - 1 : ADD SERVICE DETAILS...') }}
                            <p class="col-md-12 col-xs-12 col-sm-12 ln_solid"></p>
                        </div>
                        <form id="ServiceAdd-Form" method="post" action="{{ url('/service/store') }}" enctype="multipart/form-data" class="form-horizontal upperform serviceAddForm" border="10">

                            <div class="row row-mb-0">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.Jobcard Number') }} <label class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="text" id="jobno" name="jobno" class="form-control" value="{{ $code }}" readonly>
                                    </div>
                                </div>

                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.Customer Name') }} <label class="color-danger">*</label></label>
                                    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                        <select name="Customername" id="cust_id" class="form-control select_vhi select_customer_auto_search form-select" wire:change="get_vehicle_name(9)" required>
                                            <option value="">{{ trans('message.Select Customer') }}</option>
                                            @if (!empty($customer))
                                            @foreach ($customer as $customers)
                                            <option value="{{ $customers->id }}" {{ request()->input('c_id') == $customers->id ? 'selected' : '' }}>
                                                {{ getCustomerName($customers->id) }}
                                            </option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 addremove customerAddModel mt-0">
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#myModal" class="btn btn-outline-secondary fl margin-left-0">{{ trans('+') }}</button>
                                    </div>
                                </div>
                            </div>

                            <div class="row row-mb-0">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="last-name">{{ trans('message.Vehicle Name') }} <label class="color-danger">*</label></label>
                                    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                        <select name="vehicalname" id="vhi" class="form-control modelnameappend select_vehicle form-select w-115" free_url="{!! url('service/get_free_service') !!}" required>

                                            <!-- <select name="vehicalname" class="form-control modelnameappend" id="vhi" required> -->
                                            <option value="">{{ trans('message.Select vehicle Name') }}</option>
                                            @if (!empty($vehicles))
                                            @foreach ($vehicles as $vehicle)
                                            <option value="{{ $vehicle->vehicle_id }}" class="modelnms">
                                             {{ $vehicle->name }}   
                                            </option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 addremove">
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#vehiclemymodel" class="btn btn-outline-secondary vehiclemodel fl margin-left-0">{{ trans('+') }}
                                        </button>
                                    </div>
                                </div>

                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="Date">{{ trans('message.Date') }} <label class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8  date">
                                        <input type='text' class="form-control datepicker" name="date" autocomplete="off" id='p_date' placeholder="<?php echo getDatepicker();
                                                                                                                                                    echo ' hh:mm:ss'; ?>" value="{{ !empty(request()->input('date')) ? request()->input('date') : now()->setTimezone($timezone)->format('Y-m-d H:i:s') }}" onkeypress="return false;" required />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.Repair Category') }} <label class="color-danger">*</label></label>
                                    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                        <select name="repair_cat" class="form-control repair_category form-select w-115" required>
                                            <option value="">{{ trans('message.-- Select Repair Category--') }}
                                            </option>

                                            @if (!empty($repairCategoryList))
                                            @foreach ($repairCategoryList as $repairCategoryListData)
                                            <option value="<?php echo $repairCategoryListData->slug; ?>">
                                                {{ $repairCategoryListData->repair_category_name }}
                                            </option>
                                            @endforeach
                                            @endif

                                        </select>
                                    </div>

                                    <div class="col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 addremove">
                                        <button type="button" data-bs-target="#responsive-modal-color" data-bs-toggle="modal" class="btn btn-outline-secondary fl margin-left-0">{{ trans('+') }}</button>
                                    </div>
                                </div>

                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="last-name">{{ trans('message.Assign To') }} <label class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <select id="AssigneTo" name="AssigneTo" class="form-control form-select" required>
                                            <option value="">-- {{ trans('message.Select Assign To') }} --
                                            </option>
                                            @if (!empty($employee))
                                            @foreach ($employee as $employees)
                                            <option value="{{ $employees->id }}">{{ $employees->name }} {{ $employees->lastname }}
                                            </option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row row-mb-0">

                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-labe col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">{{ trans('message.Service Type') }}
                                        <label class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <!-- <label class="radio-inline free_service">
                                            <input type="radio" name="service_type" id="free" value="free" class="free_service" required>{{ trans('message.Free') }}</label> -->
                                        <label class="radio-inline">
                                            <input type="radio" name="service_type" id="paid" value="paid" required checked class="margin-left-10"> {{ trans('message.Paid') }}</label>
                                    </div>
                                    <div id="freeCouponList"></div>
                                </div>
                                <div id="dvCharge" class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 has-feedback {{ $errors->has('Charge') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 currency" for="last-name">{{ trans('message.Service Charge') }}
                                        (<?php echo getCurrencySymbols(); ?>) <label class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="text" id="charge_required" name="charge" class="form-control fixServiceCharge" placeholder="{{ trans('message.Enter Service Charge') }}" maxlength="8" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row row-mb-0">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="branch">{{ trans('message.Branch') }} <label class="color-danger">*</label></label>

                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <select class="form-control select_branch form-select" name="branch">
                                            @foreach ($branchDatas as $branchData)
                                            <option value="{{ $branchData->id }}">
                                                {{ $branchData->branch_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="details">{{ trans('message.Details') }}</label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <textarea class="form-control mb-2" name="details" id="details" maxlength="100">{{ old('details') }}</textarea>
                                    </div>
                                </div>


                            </div>

                            <!-- Wash Bay Feature -->
                            <div class="row row-mb-0">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 washbayLabel" for="washbay">{{ trans('message.Wash Bay') }} <label class="text-danger"></label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 washbayInputDiv pt-0">
                                        <input type="checkbox" name="washbay" id="washBay" class="washBayCheckbox form-check" style="height:20px; width:20px; margin-right:5px; position: relative; top: 1px; margin-bottom: 12px;">
                                    </div>
                                </div>

                                <div id="washBayCharge" class="has-feedback mt-0 {{ $errors->has('washBayCharge') ? ' has-error' : '' }} row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 row">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 currency" for="washBayCharge">{{ trans('message.Wash Bay Charge') }} (<?php echo getCurrencySymbols(); ?>)
                                        <label class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 washbay_charge_detail">
                                        <input type="text" id="washBayCharge_required" name="washBayCharge" class="form-control washbay_charge_textbox" placeholder="{{ trans('message.Enter Wash Bay Charge') }}" value="{{ old('washBayCharge') }}" maxlength="10">

                                        <span id="washbay_error_span" class="help-block error-help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <!-- Wash Bay Feature -->

                            <!-- MOt Test Checkbox Start-->
                            <div class="row row-mb-0">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 motTextLabel" for="">{{ trans('message.MOT Test') }}</label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="checkbox" name="motTestStatusCheckbox" id="motTestStatusCheckbox" class="motCheckbox form-check" style="height:20px; width:20px; margin-right:5px; position: relative; top: 1px; margin-bottom: 12px;">
                                    </div>
                                </div>
                                <div id="motTestCharge" class="has-feedback mt-0 {{ $errors->has('motTestCharge') ? ' has-error' : '' }} row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 row">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 currency" for="motTestCharge">{{ trans('message.MOT Test Charges') }} (<?php echo getCurrencySymbols(); ?>)
                                        <label class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 mot_charge_detail">
                                        <input type="text" id="motTestCharge_required" name="motTestCharge" class="form-control mot_charge_textbox" placeholder="{{ trans('message.Enter MOT Test Charges') }}" value="{{ old('motTestCharge') }}" maxlength="10">

                                        <span id="mot_error_span" class="help-block error-help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <!-- MOt Test Checkbox End-->


                            <div class="row row-mb-0">
                                @if ($errors->has('charge'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('reg_no') }}</strong>
                                </span>
                                @endif

                                <!-- Service images  -->
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.Select Multiple Images') }} <label class="color-danger"></label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="file" name="image[]" class="form-control imageclass" id="images" onchange="preview_images();" data-max-file-size="5M" multiple />
                                    </div>
                                </div>
                                <!-- Service images  -->
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.Title') }}</label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="text" name="title" placeholder="{{ trans('message.Enter Title') }}" value="{{ old('title') }}" maxlength="50" class="form-control">
                                    </div>
                                </div>

                            </div>

                            <!-- Note Functionality -->
                            <div class="row col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 form-group note-row">
                                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <h2 class="fw-bold">{{ trans('message.Add Notes') }} </h2></span>
                                </div>
                                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 text-end">
                                    <button type="button" class="btn btn-outline-secondary btn-sm addNotes mt-1 fl margin-left-0">{{ trans('+') }}</button><br>
                                </div>
                                <hr>
                                <div class="row notes-row" id="notes-1">
                                    <label class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2" for="">{{ trans('message.Notes') }} <label class="color-danger"></label></label>
                                    <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3">
                                        <textarea class="form-control" id="" name="notes[1][note_text]" maxlength="100"></textarea>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3 form-group my-form-group">
                                        <input type="file" name="notes[1][note_file][]" class="form-control imageclass mt-2" data-max-file-size="5M" accept="image/*,application/pdf,video/*" multiple />
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3 pt-0">
                                        <div class="d-flex">
                                            <input type="checkbox" name="notes[1][internal]" id="" class="form-check" style="height:20px; width:20px; margin-right:5px; position: relative; top: 1px; margin-bottom: 12px;">
                                            <label class="control-label pt-1" for="">{{ trans('message.Internal Notes') }} <label class="text-danger"></label></label>
                                        </div>
                                        <div class="d-flex">
                                            <input type="checkbox" name="notes[1][shared]" id="" class="form-check" style="height:20px; width:20px; margin-right:5px; position: relative; top: 1px; margin-bottom: 12px;">
                                            <label class="control-label pt-1" for="">{{ trans('message.Shared with customer') }} <label class="text-danger"></label></label>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-xl-1 col-xxl-1 col-sm-1 col-xs-1 text-center pt-3">
                                        <i class="fa fa-trash fa-2x deleteNotes"></i>
                                    </div>
                                </div>
                            </div>
                    </div>

                    <!-- Start Custom Field, (If register in Custom Field Module)  -->
                    @if (!empty($tbl_custom_fields))
                    <div class="col-md-12 col-xs-12 col-sm-12 space">
                        <h4><b>{{ trans('message.Custom Fields') }}</b></h4>
                        <p class="col-md-12 col-xs-12 col-sm-12 ln_solid"></p>
                    </div>
                    <?php
                    $subDivCount = 0;
                    ?>
                    @foreach ($tbl_custom_fields as $myCounts => $tbl_custom_field)
                    <?php
                    if ($tbl_custom_field->required == 'yes') {
                        $required = 'required';
                        $red = '*';
                    } else {
                        $required = '';
                        $red = '';
                    }

                    $subDivCount++;
                    ?>
                    @if ($myCounts % 2 == 0)
                    <div class="row col-md-12 col-sm-6 col-xs-12 row-mb-0">
                        @endif
                        <div class="form-group row  col-md-6 col-sm-6 col-xs-12 error_customfield_main_div_{{ $myCounts }}">

                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="account-no">{{ $tbl_custom_field->label }} <label class="color-danger">{{ $red }}</label></label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                @if ($tbl_custom_field->type == 'textarea')
                                <textarea name="custom[{{ $tbl_custom_field->id }}]" class="form-control textarea_{{ $tbl_custom_field->id }} textarea_simple_class common_simple_class common_value_is_{{ $myCounts }}" placeholder="{{ trans('message.Enter') }} {{ $tbl_custom_field->label }}" maxlength="100" isRequire="{{ $required }}" type="textarea" fieldNameIs="{{ $tbl_custom_field->label }}" rows_id="{{ $myCounts }}" {{ $required }}></textarea>

                                <span id="common_error_span_{{ $myCounts }}" class="help-block error-help-block color-danger"></span>
                                @elseif($tbl_custom_field->type == 'radio')
                                <?php
                                $radioLabelArrayList = getRadiolabelsList($tbl_custom_field->id);
                                ?>
                                @if (!empty($radioLabelArrayList))
                                <div class="mt-3">
                                    @foreach ($radioLabelArrayList as $k => $val)
                                    <input type="{{ $tbl_custom_field->type }}" name="custom[{{ $tbl_custom_field->id }}]" value="{{ $k }}" <?php if ($k == 0) {
                                                                                                                                                echo 'checked';
                                                                                                                                            } ?>>{{ $val }}
                                    &nbsp;
                                    @endforeach
                                </div>
                                @endif
                                @elseif($tbl_custom_field->type == 'checkbox')
                                <?php
                                $checkboxLabelArrayList = getCheckboxLabelsList($tbl_custom_field->id);
                                $cnt = 0;
                                ?>

                                @if (!empty($checkboxLabelArrayList))
                                <div class="required_checkbox_parent_div_{{ $tbl_custom_field->id }}">
                                    @foreach ($checkboxLabelArrayList as $k => $val)
                                    <input type="{{ $tbl_custom_field->type }}" name="custom[{{ $tbl_custom_field->id }}][]" value="{{ $val }}" isRequire="{{ $required }}" fieldNameIs="{{ $tbl_custom_field->label }}" custm_isd="{{ $tbl_custom_field->id }}" class="checkbox_{{ $tbl_custom_field->id }} required_checkbox_{{ $tbl_custom_field->id }} checkbox_simple_class common_value_is_{{ $myCounts }} common_simple_class" rows_id="{{ $myCounts }}"> {{ $val }}
                                    &nbsp;
                                    <?php $cnt++; ?>
                                    @endforeach
                                    <span id="common_error_span_{{ $myCounts }}" class="help-block error-help-block color-danger"></span>
                                </div>
                                <input type="hidden" name="checkboxCount" value="{{ $cnt }}">
                                @endif
                                @elseif($tbl_custom_field->type == 'textbox')
                                <input type="{{ $tbl_custom_field->type }}" name="custom[{{ $tbl_custom_field->id }}]" class="form-control textDate_{{ $tbl_custom_field->id }} textdate_simple_class common_value_is_{{ $myCounts }} common_simple_class" placeholder="{{ trans('message.Enter') }} {{ $tbl_custom_field->label }}" maxlength="30" isRequire="{{ $required }}" fieldNameIs="{{ $tbl_custom_field->label }}" rows_id="{{ $myCounts }}" {{ $required }}>

                                <span id="common_error_span_{{ $myCounts }}" class="help-block error-help-block color-danger"></span>
                                @elseif($tbl_custom_field->type == 'date')
                                <input type="{{ $tbl_custom_field->type }}" name="custom[{{ $tbl_custom_field->id }}]" class="form-control textDate_{{ $tbl_custom_field->id }} date_simple_class common_value_is_{{ $myCounts }} common_simple_class" placeholder="{{ trans('message.Enter') }} {{ $tbl_custom_field->label }}" maxlength="30" isRequire="{{ $required }}" fieldNameIs="{{ $tbl_custom_field->label }}" rows_id="{{ $myCounts }}" {{ $required }} onkeydown="return false">

                                <span id="common_error_span_{{ $myCounts }}" class="help-block error-help-block color-danger"></span>
                                @endif

                            </div>
                        </div>
                        @if ($myCounts % 2 != 0)
                    </div>
                    @endif
                    @endforeach
                    <?php
                    if ($subDivCount % 2 != 0) {
                        echo '</div>';
                    }
                    ?>
                    @endif
                    <!-- End Custom Field -->

                    <div class="row">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <!-- <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 my-1 mx-0">
                        <a class="btn btn-primary serviceCancleButton" href="{{ URL::previous() }}">{{ trans('message.CANCEL') }}</a>
                    </div> -->
                        <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 my-1 mx-0">
                            <button type="submit" id="submitButton" class="btn btn-success serviceSubmitButton">{{ trans('message.Save and continue') }}</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>