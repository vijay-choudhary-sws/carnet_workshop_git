  <!--customer add model -->
  <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title" id="exampleModalLabel1">{{ trans('message.Customer Details') }}</h4>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="x_content">
                      <form id="formcustomer" method="POST" name="formcustomer" enctype="multipart/form-data"
                          data-parsley-validate class="form-horizontal form-label-left input_mask">

                          <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 space">
                              <h4><b>{{ trans('message.PERSONAL INFORMATION') }}</b></h4>
                              <p class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ln_solid"></p>
                          </div>
                          <div class="row mt-3">
                              <div
                                  class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback">
                                  <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                      for="first-name">{{ trans('message.First Name') }} <label
                                          class="color-danger">*</label> </label>
                                  <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                      <input type="text" id="firstname" name="firstname" class="form-control"
                                          value="{{ old('firstname') }}"
                                          placeholder="{{ trans('message.Enter First Name') }}" maxlength="25"
                                          required />
                                      <span class="color-danger" id="errorlfirstname"></span>
                                  </div>
                              </div>

                              <div
                                  class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback {{ $errors->has('lastname') ? ' has-error' : '' }}">
                                  <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                      for="last-name">{{ trans('message.Last Name') }} <label
                                          class="color-danger">*</label></label>
                                  <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                      <input type="text" id="lastname" name="lastname"
                                          placeholder="{{ trans('message.Enter Last Name') }}"
                                          value="{{ old('lastname') }}" maxlength="25" class="form-control" required>
                                      <span class="color-danger" id="errorllastname"></span>
                                  </div>
                              </div>
                          </div>

                          <div class="row mt-3">
                              <div
                                  class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback">
                                  <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                                      {{ trans('message.Gender') }}
                                      <label class="color-danger"></label></label>
                                  <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 gender">
                                      <input type="radio" class="gender" id="male" name="gender" value="0"
                                          checked> <label for="male">{{ trans('message.Male') }}</label>
                                      <input type="radio" class="gender" id="female" name="gender" value="1">
                                      <label for="female">{{ trans('message.Female') }}</label>

                                  </div>
                              </div>
                              <div
                                  class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback {{ $errors->has('mobile') ? ' has-error' : '' }}">
                                  <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                      for="mobile">{{ trans('message.Mobile No') }}. <label
                                          class="color-danger">*</label></label>
                                  <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                      <input type="text" id="mobile" name="mobile"
                                          placeholder="{{ trans('message.Enter Mobile No') }}"
                                          value="{{ old('mobile') }}" class="form-control" maxlength="16"
                                          minlength="6" required>
                                      <span class="color-danger" id="errorlmobile"></span>
                                  </div>
                              </div>

                          </div>
                          <div class="row mt-3">
                              <div
                                  class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                                  <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                      for="Email">{{ trans('message.Email') }} <label
                                          class="color-danger">*</label></label>
                                  <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                      <input type="text" id="email" name="email"
                                          placeholder="{{ trans('message.Enter Email') }}" value="{{ old('email') }}"
                                          class="form-control" maxlength="50" required>
                                      <span class="color-danger" id="errorlemail"></span>
                                  </div>
                              </div>

                              <div
                                  class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
                                  <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                      for="Password">{{ trans('message.Password') }} <label
                                          class="color-danger">*</label></label>
                                  <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                      <input type="password" id="password" name="password"
                                          placeholder="{{ trans('message.Enter Password') }}"
                                          class="form-control col-md-7 col-xs-12" maxlength="20" required>
                                      <span class="color-danger" id="errorlpassword"></span>
                                  </div>
                              </div>
                          </div>
                          <div class="row mt-3">
                              <div
                                  class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                  <label
                                      class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 currency p-0 ps-2 px-5"
                                      for="Password">{{ trans('message.Confirm Password') }}
                                      <label class="color-danger">*</label></label>
                                  <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                      <input type="password" id="password_confirmation" name="password_confirmation"
                                          placeholder="{{ trans('message.Enter Confirm Password') }}"
                                          class="form-control col-md-7 col-xs-12" maxlength="20" required>
                                      <span class="color-danger" id="errorlpassword_confirmation"></span>
                                  </div>
                              </div>

                              <div
                                  class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group {{ $errors->has('dob') ? ' has-error' : '' }}">
                                  <label
                                      class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">{{ trans('message.Date of Birth') }}</label>
                                  <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 date ">
                                      <input type="text" id="datepicker" autocomplete="off"
                                          class="form-control datepickercustmore" placeholder="<?php echo getDatepicker(); ?>"
                                          name="dob" value="{{ old('dob') }}" onkeypress="return false;" />
                                  </div>
                                  <span class="color-danger" id="errorldatepicker"></span>
                              </div>
                          </div>

                          <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 space">
                              <h4><b>{{ trans('message.ADDRESS') }}</b></h4>
                              <p class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ln_solid"></p>
                          </div>
                          <div class="row mt-3">
                              <div
                                  class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback">
                                  <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                      for="Country">{{ trans('message.Country') }} <label
                                          class="color-danger">*</label></label>
                                  <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                      <select class="form-control select_country form-select" id="country_id"
                                          name="country_id" countryurl="{!! url('/getstatefromcountry') !!}" required>
                                          <option value="">{{ trans('message.Select Country') }}</option>
                                          @foreach ($country as $countrys)
                                              <option value="{{ $countrys->id }}">{{ $countrys->name }}</option>
                                          @endforeach
                                      </select>
                                      <span class="color-danger" id="errorlcountry_id"></span>
                                  </div>
                              </div>

                              <div
                                  class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback">
                                  <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                      for="State ">{{ trans('message.State') }} <label
                                          class="color-danger">*</label></label>
                                  <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                      <select class="form-control state_of_country form-select" id="state_id"
                                          name="state_id" stateurl="{!! url('/getcityfromstate') !!}" required>
                                          <option value="">{{ trans('message.Select State') }}</option>
                                      </select>
                                  </div>
                              </div>
                          </div>
                          <div class="row mt-3">
                              <div
                                  class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback">
                                  <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                      for="Town/City">{{ trans('message.Town/City') }}<label
                                          class="color-danger">*</label></label>
                                  <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                      <select class="form-control city_of_state form-select" id="city"
                                          name="city" required>
                                          <option value="">{{ trans('message.Select City') }}</option>
                                      </select>
                                  </div>
                              </div>

                              <div
                                  class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback">
                                  <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                      for="Address">{{ trans('message.Address') }} <label
                                          class="color-danger">*</label></label>
                                  <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                      <textarea class="form-control" id="address" name="address" maxlength="100" required>{{ old('address') }}</textarea>
                                      <span class="color-danger" id="errorladdress"></span>
                                  </div>
                              </div>
                          </div>
                          <input type="hidden" name="_token" value="{{ csrf_token() }}">
                          <div class="form-group col-md-12 col-sm-12 col-xs-12">
                              <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                  <!-- <a class="btn btn-primary cancelcustomer" data-bs-dismiss="modal">{{ trans('message.CANCEL') }}</a> -->
                                  <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 my-1 mx-0">
                                      <button type="submit"
                                          class="btn btn-success addcustomer">{{ trans('message.SUBMIT') }}</button>
                                  </div>
                              </div>
                          </div>
                      </form>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-outline-secondary btn-sm mx-1"
                      data-bs-dismiss="modal">{{ trans('message.Close') }}</button>
              </div>
          </div>
      </div>
  </div>

  <div class="modal" id="vehiclemymodel" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title" id="exampleModalLabel">{{ trans('message.Vehicle Details') }}</h4>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              @include('success_message.message')
              <div class="modal-body">
                  <form action="" method="post" enctype="multipart/form-data"
                      class="form-horizontal upperform" id="add_vehi">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <input type="hidden" name="customer_id" value="" class="hidden_customer_id">
                      <div class="row">
                          <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                              <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                  for="first-name">{{ trans('message.Vehicle Type') }} <label
                                      class="color-danger">*</label></label>
                              <div class="col-md-12 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                  <select class="form-control select_vehicaltype form-select" id="vehical_id1"
                                      name="vehical_id" vehicalurl="{!! url('/vehicle/vehicaltypefrombrand') !!}" required>
                                      <option value="">{{ trans('message.Select Vehicle Type') }}</option>
                                      @if (!empty($vehical_type))
                                          @foreach ($vehical_type as $vehical_types)
                                              <option value="{{ $vehical_types->id }}">
                                                  {{ $vehical_types->vehicle_type }}
                                              </option>
                                          @endforeach
                                      @endif
                                  </select>
                                  <span class="color-danger" id="errorlvehical_id1"></span>
                              </div>
                              <div class="col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 addremove">
                                  <button type="button" class="btn btn-outline-secondary btn-sm showmodal ms-1"
                                      data-show-modal="responsive-modal">
                                      +
                                  </button>
                              </div>
                          </div>
                          <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                              <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                  for="first-name">{{ trans('message.Number Plate') }} <label
                                      class="text-danger">*</label></label>
                              <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                  <input type="text" id="number_plate" name="number_plate"
                                      value="{{ old('number_plate') }}"
                                      placeholder="{{ trans('message.Enter Number Plate') }}" maxlength="30"
                                      class="form-control" required>
                                  <span class="color-danger" id="npe"></span>
                                  @if ($errors->has('price'))
                                      <span class="help-block">
                                          <strong>{{ $errors->first('price') }}</strong>
                                      </span>
                                  @endif
                              </div>
                          </div>
                      </div>
                      <div class="row mt-2">
                          <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                              <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                  for="first-name">{{ trans('message.Vehicle Brand') }}<label
                                      class="color-danger">*</label></label>
                              <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                  <select class="form-control select_vehicalbrand form-select" id="vehicabrand1"
                                      name="vehicabrand" url="{!! url('/vehicle/vehicalmodelfrombrand') !!}">
                                      <option value="">{{ trans('message.Select Brand') }}</option>
                                  </select>
                                  <span class="color-danger">
                                      <strong id="errorlvehicabrand1"></strong>
                                  </span>
                              </div>
                              <div class="col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 addremove">
                                  <button type="button" class="btn btn-outline-secondary btn-sm showmodal ms-1"
                                      data-show-modal="responsive-modal-brand">
                                      +
                                  </button>
                              </div>
                          </div>
                          <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                              <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                  for="first-name">{{ trans('message.Chasic No') }}</label>
                              <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                  <input type="text" name="chasicno" id="chasicno1"
                                      value="{{ old('chasicno') }}"
                                      placeholder="{{ trans('message.Enter ChasicNo') }}" maxlength="30"
                                      class="form-control">
                                  <span class="color-danger" id="errorlchasicno1"></span>
                              </div>
                          </div>
                      </div>
                      <div class="row mt-2">
                          <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                              <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                  for="last-name">{{ trans('message.Model Name') }} <label
                                      class="color-danger">*</label></label>
                              <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                  <select class="form-control model_addname form-select" id="modelname1"
                                      name="modelname" required>
                                      <option value="">{{ trans('message.Select Model') }}</option>
                                  </select>
                                  <span class="color-danger" id="errorlmodelname1"></span>
                              </div>
                              <div class="col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 addremove">
                                  <button type="button" class="btn btn-outline-secondary btn-sm showmodal ms-1"
                                      data-show-modal="responsive-modal-vehi-model">
                                      +
                                  </button>
                              </div>
                          </div>
                          <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                              <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                  for="first-name">{{ trans('message.Model Years') }}</label>
                              <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 input-groups date">
                                  <input type="text" name="modelyear" id="modelyear1"
                                      class="form-control myDatepicker2" />
                              </div>
                          </div>
                      </div>
                      <div class="row mt-2">
                          <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                              <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                  for="first-name">{{ trans('message.Fuel Type') }}<label
                                      class="color-danger">*</label></label>
                              <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                  <select class="form-control select_fueltype form-select" id="fueltype1"
                                      name="fueltype">
                                      <option value="">{{ trans('message.Select fuel type') }} </option>
                                      @if (!empty($fuel_type))
                                          @foreach ($fuel_type as $fuel_types)
                                              <option value="{{ $fuel_types->id }}">{{ $fuel_types->fuel_type }}
                                              </option>
                                          @endforeach
                                      @endif
                                  </select>
                                  <span class="color-danger" id="fuel1"></span>
                              </div>
                              <div class="col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 addremove">
                                  <button type="button" class="btn btn-outline-secondary btn-sm showmodal ms-1"
                                      data-show-modal="responsive-modal-fuel">
                                      +
                                  </button>
                              </div>
                          </div>
                          <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                              <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                  for="first-name">{{ trans('message.No of Grear') }}</label>
                              <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                  <input type="text" name="gearno" id="gearno1" value="{{ old('gearno') }}"
                                      placeholder="{{ trans('message.Enter No of Gear') }}" maxlength="5"
                                      class="form-control">
                              </div>
                          </div>
                      </div>
                      <div class="row mt-2">
                          <div
                              class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 {{ $errors->has('odometerreading') ? ' has-error' : '' }}">
                              <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                  for="first-name">{{ trans('message.Odometer Reading') }} </label>
                              <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                  <input type="text" name="odometerreading" id="odometerreading1"
                                      value="{{ old('odometerreading') }}"
                                      placeholder="{{ trans('message.Enter Odometer Reading') }}" maxlength="20"
                                      class="form-control">
                              </div>
                          </div>
                          <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                              <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                  for="last-name">{{ trans('message.Date Of Manufacturing') }} </label>
                              <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 date">
                                  <input type="text" name="dom" id="dom1"
                                      class="form-control datepicker1" placeholder="<?php echo getDatepicker(); ?>"
                                      onkeypress="return false;" />
                              </div>
                          </div>
                      </div>
                      <div class="row mt-2">
                          <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                              <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                  for="first-name">{{ trans('message.Gear Box') }}</label>
                              <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                  <input type="text" name="gearbox" id="gearbox1" value="{{ old('gearbox') }}"
                                      placeholder="{{ trans('message.Enter Grear Box') }}" maxlength="30"
                                      class="form-control">
                              </div>
                          </div>
                          <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                              <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                  for="last-name">{{ trans('message.Gear Box No') }}</label>
                              <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                  <input type="text" name="gearboxno" id="gearboxno1"
                                      value="{{ old('gearboxno') }}"
                                      placeholder="{{ trans('message.Enter Gearbox No') }}" maxlength="30"
                                      class="form-control">
                              </div>
                          </div>
                      </div>
                      <div class="row mt-2">
                          <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                              <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                  for="first-name">{{ trans('message.Engine No') }}</label>
                              <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                  <input type="text" name="engineno" id="engineno1"
                                      value="{{ old('engineno') }}"
                                      placeholder="{{ trans('message.Enter Engine No') }}" maxlength="30"
                                      class="form-control">
                                  <span class="color-danger" id="errorlengineno1"></span>
                              </div>
                          </div>
                          <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                              <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                  for="last-name">{{ trans('message.Engine Size') }}</label>
                              <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                  <input type="text" name="enginesize" id="enginesize1"
                                      value="{{ old('enginesize') }}"
                                      placeholder="{{ trans('message.Enter Engine Size') }}" maxlength="30"
                                      class="form-control">
                              </div>
                          </div>
                      </div>
                      <div class="row mt-2">
                          <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                              <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                  for="first-name">{{ trans('message.Key No') }} </label>
                              <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                  <input type="text" name="keyno" id="keyno1" value="{{ old('keyno') }}"
                                      placeholder="{{ trans('message.Enter Key No') }}" maxlength="30"
                                      class="form-control">
                              </div>
                          </div>
                          <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                              <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                  for="first-name">{{ trans('message.Engine') }} </label>
                              <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                  <input type="text" name="engine" id="engine1" value="{{ old('engine') }}"
                                      placeholder="{{ trans('message.Enter Engine') }}" maxlength="30"
                                      class="form-control">
                              </div>
                          </div>
                      </div>
                      <div class="row mt-2">
                          <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                              <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                  for="first-name">{{ trans('message.Chasic No') }}</label>
                              <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                  <input type="text" name="chasicno" id="chasicno1"
                                      value="{{ old('chasicno') }}"
                                      placeholder="{{ trans('message.Enter ChasicNo') }}" maxlength="30"
                                      class="form-control">
                                  <span class="color-danger" id="errorlchasicno1"></span>
                              </div>
                          </div>
                      </div>
                      <div class="row mt-2">
                          <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 text-center">
                              <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 my-1 mx-0">
                                  <button type="button"
                                      class="btn btn-success addvehicleservice">{{ trans('message.SUBMIT') }}</button>
                              </div>
                          </div>
                      </div>
                  </form>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-outline-secondary vhc_close btn-sm mx-1"
                      data-bs-dismiss="modal">{{ trans('message.Close') }}</button>
              </div>
          </div>
      </div>
  </div>
  <!-- Model Name -->
  <div class="col-md-6">
      <div id="responsive-modal-vehi-model" class="modal fade" tabindex="-1" role="dialog"
          aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title">{{ trans('message.Add Model Name') }}</h4>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                  </div>
                  <div class="modal-body">
                      <form class="form-horizontal" action="" method="post">
                          <div class="row">
                              <div class="col-md-5 form-group data_popup">
                                  <select class="form-control model_input form-select vehi_brand_id" name="vehical_id"
                                      id="vehicleBrandSelect" vehicalurl="{!! url('/vehicle/vehicalformtype') !!}" required>
                                      <option value="">{{ trans('message.Select Brand') }}</option>
                                      @if (!empty($vehical_brand))
                                          @foreach ($vehical_brand as $vehical_brands)
                                              <option value="{{ $vehical_brands->id }}">
                                                  {{ $vehical_brands->vehicle_brand }}</option>
                                          @endforeach
                                      @endif
                                  </select>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-md-8 form-group data_popup">
                                  <input type="text" class="form-control model_input vehi_modal_name"
                                      name="model_name" id="model_name"
                                      placeholder="{{ trans('message.Enter Model Name') }}" maxlength="20"
                                      required />
                              </div>
                              <div class="col-md-4 form-group data_popup">
                                  <button type="button" class="btn btn-success model_submit vehi_model_add"
                                      modelurl="{!! url('/vehicle/vehicle_model_add') !!}">{{ trans('message.Submit') }}</button>
                              </div>
                          </div>
                          <table class="table vehi_model_class">

                              <tbody>

                                  @if (!empty($model_name))
                                      @foreach ($model_name as $model_names)
                                          <tr class="mod-{{ $model_names->id }} data_color_name row mx-1">
                                              <td class="text-start col-6">{{ $model_names->model_name }}
                                              </td>
                                              <td class="text-end col-6">
                                                  <button type="button" modelid="{{ $model_names->id }}"
                                                      deletemodel="{!! url('/vehicle/vehicle_model_delete') !!}"
                                                      class="btn btn-danger text-white border-0 modeldeletes"><i
                                                          class="fa fa-trash" aria-hidden="true"></i></button>
                                              </td>
                                          </tr>
                                      @endforeach
                                  @endif
                              </tbody>
                          </table>

                      </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <!-- End Model Name -->
  <!-- Vehicle Type  -->
  <div class="col-md-6">
      <div id="responsive-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
          aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title"> {{ trans('message.Add Vehicle Type') }}</h4>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                  </div>
                  <div class="modal-body">
                      <form class="form-horizontal formaction" action="" method="">
                          <div class="row">
                              <div
                                  class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 form-group data_popup">
                                  <input type="text" class="form-control model_input vehical_type"
                                      name="vehical_type" id="vehical_type"
                                      placeholder="{{ trans('message.Enter Vehicle Type') }}" maxlength="20"
                                      required />
                              </div>
                              <div
                                  class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 form-group data_popup">

                                  <button type="button" class="btn btn-success model_submit vehicaltypeadd"
                                      url="{!! url('/vehicle/vehicle_type_add') !!}">{{ trans('message.Submit') }}</button>
                              </div>
                          </div>
                          <table class="table vehical_type_class" align="center">
                              <tbody>
                                  @if (!empty($vehical_type))
                                      @foreach ($vehical_type as $vehical_types)
                                          <tr class="del-{{ $vehical_types->id }} data_vehicle_type_name row mx-1">
                                              <td class="text-start col-6 w-50">{{ $vehical_types->vehicle_type }}
                                              </td>
                                              <td class="text-end col-6 w-50">
                                                  <button type="button" vehicletypeid="{{ $vehical_types->id }}"
                                                      deletevehical="{!! url('/vehicle/vehicaltypedelete') !!}"
                                                      class="btn btn-danger text-white border-0 deletevehicletype"><i
                                                          class="fa fa-trash" aria-hidden="true"></i></button>
                                              </td>
                                          </tr>
                                      @endforeach
                                  @endif
                              </tbody>
                          </table>

                      </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <!-- End  Vehicle Type  -->

  <!-- Vehicle Brand -->
  <div class="col-md-6">
      <div id="responsive-modal-brand" class="modal fade" tabindex="-1" role="dialog"
          aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title">{{ trans('message.Add Vehicle Brand') }}</h4>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                  </div>
                  <div class="modal-body">
                      <form class="form-horizontal" action="" method="">
                          <div class="row">
                              <div class="col-md-8 form-group data_popup">
                                  <select class="form-control model_input vehical_id form-select vehicle_type_model"
                                      name="vehical_id" id="vehicleTypeSelect" vehicalurl="{!! url('/vehicle/vehicalformtype') !!}"
                                      required>
                                      <option>{{ trans('message.Select Vehicle Type') }}</option>
                                      @if (!empty($vehical_type))
                                          @foreach ($vehical_type as $vehical_types)
                                              <option value="{{ $vehical_types->id }}">
                                                  {{ $vehical_types->vehicle_type }}
                                              </option>
                                          @endforeach
                                      @endif
                                  </select>
                              </div>
                              <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"></div>
                          </div>
                          <div class="row">
                              <div
                                  class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 form-group data_popup">
                                  <input type="text" class="form-control model_input vehical_brand"
                                      name="vehical_brand" id="vehical_brand"
                                      placeholder="{{ trans('message.Enter Vehicle brand') }}" maxlength="25"
                                      required />
                              </div>
                              <div
                                  class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 form-group data_popup">

                                  <button type="button" class="btn btn-success model_submit vehicalbrandadd"
                                      vehiclebrandurl="{!! url('/vehicle/vehicle_brand_add') !!}">{{ trans('message.Submit') }}</button>
                              </div>
                          </div>
                          <table class="table vehical_brand_class" align="center">
                              <tbody>
                                  @if (!empty($vehical_brand))
                                      @foreach ($vehical_brand as $vehical_brands)
                                          <tr class="del-{{ $vehical_brands->id }} data_vehicle_brand_name row mx-1">
                                              <td class="text-start col-6 w-50">{{ $vehical_brands->vehicle_brand }}
                                              </td>
                                              <td class="text-end col-6 w-50">

                                                  <button type="button" brandid="{{ $vehical_brands->id }}"
                                                      deletevehicalbrand="{!! url('/vehicle/vehicalbranddelete') !!}"
                                                      class="btn btn-danger text-white border-0 deletevehiclebrands"><i
                                                          class="fa fa-trash" aria-hidden="true"></i></button>
                                              </td>
                                          </tr>
                                      @endforeach
                                  @endif
                              </tbody>
                          </table>


                      </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <!-- End Vehicle Brand -->
  <!-- Fuel Type -->
  <div class="col-md-6">
      <div id="responsive-modal-fuel" class="modal fade" tabindex="-1" role="dialog"
          aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title">{{ trans('message.Add Fuel Type') }}</h4>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                  </div>
                  <div class="modal-body">
                      <form class="form-horizontal" action="" method="post">
                          <div class="row">
                              <div
                                  class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 form-group data_popup">
                                  <input type="text" class="form-control model_input fuel_type" name="fuel_type"
                                      id="fuel_type" placeholder="{{ trans('message.Enter Fuel Type') }}"
                                      maxlength="20" required />
                              </div>
                              <div
                                  class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 form-group data_popup">

                                  <button type="button" class="btn btn-success model_submit fueltypeadd"
                                      fuelurl="{!! url('/vehicle/vehicle_fuel_add') !!}">{{ trans('message.Submit') }}</button>
                              </div>

                          </div>
                          <table class="table fuel_type_class" align="center">

                              <tbody>
                                  @if (!empty($fuel_type))
                                      @foreach ($fuel_type as $fuel_types)
                                          <tr class="del-{{ $fuel_types->id }} data_fuel_type_name row mx-1">
                                              <td class="text-start col-6 w-50">{{ $fuel_types->fuel_type }}</td>
                                              <td class="text-end col-6 w-50">
                                                  <button type="button" fuelid="{{ $fuel_types->id }}"
                                                      deletefuel="{!! url('/vehicle/fueltypedelete') !!}"
                                                      class="btn btn-danger text-white border-0 fueldeletes"><i
                                                          class="fa fa-trash" aria-hidden="true"></i></button>
                                              </td>
                                          </tr>
                                      @endforeach
                                  @endif
                              </tbody>
                          </table>

                      </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <!-- end Fuel Type -->


  {{-- <!-- Repair Add or Remove Model Start-->
  <div class="col-md-6">
      <div id="responsive-modal-color" class="modal fade" tabindex="-1" role="dialog"
          aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered ">
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title">{{ trans('message.Add Repair Category') }}</h4>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                  </div>
                  <div class="modal-body">
                      <form class="form-horizontal" action="" method="">
                          <div class="row">
                              <div
                                  class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 form-group data_popup">
                                  <input type="text" class="form-control model_input repair_category_name"
                                      name="repair_category_name"
                                      placeholder="{{ trans('message.Enter repair category name') }}"
                                      maxlength="20" />
                              </div>
                              <div
                                  class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 form-group data_popup">
                                  <button type="button" class="btn btn-success model_submit addcolor"
                                      colorurl="{!! url('/addRepairCategory') !!}">{{ trans('message.Submit') }}</button>
                              </div>
                          </div>
                          <table class="table colornametype" align="center">

                              <tbody>
                                  @foreach ($repairCategoryList as $repairCategory)
                                      <tr class="del-{{ $repairCategory->slug }} data_color_name row mx-1">
                                          <td class="text-start col-6">{{ $repairCategory->repair_category_name }}
                                          </td>
                                          <td class="text-end col-6">
                                              @if ($repairCategory->added_by_system !== '1' && $repairCategory->added_by_system !== 1)
                                                  <button type="button" id="{{ $repairCategory->slug }}"
                                                      deletecolor="{!! url('deleteRepairCategory') !!}"
                                                      class="btn btn-danger text-white border-0 deletecolors"><i
                                                          class="fa fa-trash" aria-hidden="true"></i></button>
                                              @else
                                                  {{ trans('message.Added by system') }}
                                              @endif

                                          </td>
                                      </tr>
                                  @endforeach
                              </tbody>
                          </table>

                      </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <!-- Repair Add or Remove Model End--> --}}

  <script>
      var msg1 = "{{ trans('message.Are You Sure?') }}";
      var msg2 = "{{ trans('message.You will not be able to recover this data afterwards!') }}";
      var msg3 = "{{ trans('message.Cancel') }}";
      var msg4 = "{{ trans('message.Yes, delete!') }}";
      var msg5 = "{{ trans('message.Done!') }}";
      // var msg6 = "{{ trans('message.It was succesfully deleted!') }}";
      var msg7 = "{{ trans('message.Cancelled') }}";
      var msg8 = "{{ trans('message.Your data is safe') }}";
      var msg35 = "{{ trans('message.OK') }}";
      var submitmsg = "{{ trans('message.Successfully Submitted') }}";
      var vtypedelete = "{{ trans('message.Vehicle Type Deleted Successfully') }}";
      var vbranddelete = "{{ trans('message.Vehicle Brand Deleted Successfully') }}";
      var fueldelete = "{{ trans('message.Fuel Type Deleted Successfully') }}";
      var modeldelete = "{{ trans('message.Model Deleted Successfully') }}";

      $(document).ready(function() {

          /*vehicle type*/
          $('.vehicaltypeadd').click(function() {

              var vehical_type = $('.vehical_type').val();
              var url = $(this).attr('url');

              var msg13 = "{{ trans('message.Please enter vehicle type') }}";
              var msg15 = "{{ trans('message.Only blank space not allowed') }}";
              var msg16 = "{{ trans('message.This Record is Duplicate') }}";

              function define_variable() {
                  return {
                      vehicle_type_value: $('.vehical_type').val(),
                      vehicle_type_pattern: /^[a-zA-Z0-9\u0621-\u064A\u00C0-\u017F\u0600-\u06FF\u0750-\u077F\uFB50-\uFDFF\uFE70-\uFEFF\u2E80-\u2FD5\u3190-\u319f\u3400-\u4DBF\u4E00-\u9FCC\uF900-\uFAAD\u0900-\u097F\s]+$/,
                      vehicle_type_pattern2: /^[a-zA-Z0-9\u0621-\u064A\u00C0-\u017F\u0600-\u06FF\u0750-\u077F\uFB50-\uFDFF\uFE70-\uFEFF\u2E80-\u2FD5\u3190-\u319f\u3400-\u4DBF\u4E00-\u9FCC\uF900-\uFAAD\u0900-\u097F\s]*$/
                  }

                  ;
              }

              var call_var_vehicletypeadd = define_variable();

              if (vehical_type == "") {
                  swal({

                      title: msg13,
                      cancelButtonColor: '#C1C1C1',
                      buttons: {
                          cancel: msg35,
                      }

                      ,
                      dangerMode: true,
                  });
              } else if (!call_var_vehicletypeadd.vehicle_type_pattern.test(call_var_vehicletypeadd
                      .vehicle_type_value)) {
                  $('.vehical_type').val("");

                  swal({

                      title: msg14,
                      cancelButtonColor: '#C1C1C1',
                      buttons: {
                          cancel: msg35,
                      }

                      ,
                      dangerMode: true,
                  });
              } else if (!vehical_type.replace(/\s/g, '').length) {
                  $('.vehical_type').val("");

                  swal({

                      title: msg15,
                      cancelButtonColor: '#C1C1C1',
                      buttons: {
                          cancel: msg35,
                      }

                      ,
                      dangerMode: true,
                  });
              } else if (!call_var_vehicletypeadd.vehicle_type_pattern2.test(call_var_vehicletypeadd
                      .vehicle_type_value)) {
                  $('.vehical_type').val("");

                  swal({

                      title: msg34,
                      cancelButtonColor: '#C1C1C1',
                      buttons: {
                          cancel: msg35,
                      }

                      ,
                      dangerMode: true,
                  });
              } else {
                  $.ajax({

                      type: 'GET',
                      url: url,
                      data: {
                          vehical_type: vehical_type
                      }

                      ,
                      success: function(data) {
                              var newd = $.trim(data);
                              var classname = 'del-' + newd;

                              if (newd == '01') {
                                  swal({

                                      title: msg16,
                                      cancelButtonColor: '#C1C1C1',
                                      buttons: {
                                          cancel: msg35,
                                      }

                                      ,
                                      dangerMode: true,
                                  });
                              } else {
                                  $('.vehical_type_class').append('<tr class=" row mx-1 ' +
                                      classname +
                                      ' data_vehicle_type_name "><td class="text-start w-50">' +
                                      vehical_type +
                                      '</td><td class="text-end w-50"><button type="button" vehicletypeid=' +
                                      data +
                                      ' deletevehical="{!! url('vehicle/vehicaltypedelete') !!}" class="btn btn-danger text-white border-0 deletevehicletype"><i class="fa fa-trash" aria-hidden="true"></i></button></a></td><tr>'
                                  );

                                  $('.select_vehicaltype').append('<option selected value=' +
                                      data +
                                      '>' + vehical_type + '</option>');

                                  $('.vehical_type').val('');

                                  $('.vehical_id').append('<option value=' + data + '>' +
                                      vehical_type + '</option>');

                                  $('.vehical_type').val('');

                                  $('.select_vehicaltype').trigger('change');

                                  swal({

                                      title: msg5,
                                      text: submitmsg,
                                      icon: 'success',
                                      cancelButtonColor: '#C1C1C1',
                                      buttons: {
                                          cancel: msg35,
                                      }

                                      ,
                                      dangerMode: true,
                                  }).then(() => {
                                      $('#responsive-modal').modal(
                                          'hide'); // Close the modal
                                  });
                              }
                          }

                          ,
                  });
              }
          });



          /*vehical Type delete*/
          $('body').on('click', '.deletevehicletype', function() {

              var vtypeid = $(this).attr('vehicletypeid');
              var url = $(this).attr('deletevehical');

              var msg1 = "{{ trans('message.Are You Sure?') }}";
              var msg2 = "{{ trans('message.You will not be able to recover this data afterwards!') }}";
              var msg3 = "{{ trans('message.Cancel') }}";
              var msg4 = "{{ trans('message.Yes, delete!') }}";
              var msg5 = "{{ trans('message.Done!') }}";
              // var msg6 = "{{ trans('message.It was succesfully deleted!') }}";
              var msg7 = "{{ trans('message.Cancelled') }}";
              var msg8 = "{{ trans('message.Your data is safe') }}";

              swal({

                  title: msg1,
                  text: msg2,
                  icon: "warning",
                  buttons: [msg3, msg4],
                  dangerMode: true,
                  cancelButtonColor: "#C1C1C1",
              }).then((isConfirm) => {
                  if (isConfirm) {
                      $.ajax({

                          type: 'GET',
                          url: url,
                          data: {
                              vtypeid: vtypeid
                          }

                          ,
                          success: function(data) {
                              $('.del-' + vtypeid).remove();
                              $(".select_vehicaltype option[value=" + vtypeid + "]")
                                  .remove();

                              swal({

                                  title: msg5,
                                  text: vtypedelete,
                                  icon: 'success',
                                  cancelButtonColor: '#C1C1C1',
                                  buttons: {
                                      cancel: msg35,
                                  }

                                  ,
                                  dangerMode: true,
                              }).then(() => {
                                  $('#responsive-modal').modal(
                                      'hide'); // Close the modal
                              });
                          }
                      });
                  } else {
                      swal({

                          title: msg7,
                          text: msg8,
                          icon: 'error',
                          cancelButtonColor: '#C1C1C1',
                          buttons: {
                              cancel: msg35,
                          }

                          ,
                          dangerMode: true,
                      });
                  }
              })
          });


          /*vehical brand*/
          $('.vehicalbrandadd').click(function() {
              var vehical_id = $('.vehicle_type_model').val();
              console.log(vehical_id);
              var vehical_brand = $('.vehical_brand').val();
              var url = $(this).attr('vehiclebrandurl');

              var msg17 = "{{ trans('message.Please first select vehicle type') }}";
              var msg18 = "{{ trans('message.Please enter vehicle brand') }}";
              var msg19 = "{{ trans('message.Please enter only alphanumeric data') }}";
              var msg20 = "{{ trans('message.Only blank space not allowed') }}";
              var msg21 = "{{ trans('message.This Record is Duplicate') }}";

              function define_variable() {
                  return {
                      vehicle_brand_value: $('.vehical_brand').val(),
                      vehicle_brand_pattern: /^[a-zA-Z0-9\u0621-\u064A\u00C0-\u017F\u0600-\u06FF\u0750-\u077F\uFB50-\uFDFF\uFE70-\uFEFF\u2E80-\u2FD5\u3190-\u319f\u3400-\u4DBF\u4E00-\u9FCC\uF900-\uFAAD\u0900-\u097F\s]+$/,
                      vehicle_brand_pattern2: /^[a-zA-Z0-9\u0621-\u064A\u00C0-\u017F\u0600-\u06FF\u0750-\u077F\uFB50-\uFDFF\uFE70-\uFEFF\u2E80-\u2FD5\u3190-\u319f\u3400-\u4DBF\u4E00-\u9FCC\uF900-\uFAAD\u0900-\u097F\s]*$/
                  }

                  ;
              }

              var call_var_vehiclebrandadd = define_variable();

              if ($(".vehicle_type_model")[0].selectedIndex <= 0) {
                  swal({

                      title: msg17,
                      cancelButtonColor: '#C1C1C1',
                      buttons: {
                          cancel: msg35,
                      }

                      ,
                      dangerMode: true,
                  });
              } else {
                  if (vehical_brand == "") {
                      swal({

                          title: msg18,
                          cancelButtonColor: '#C1C1C1',
                          buttons: {
                              cancel: msg35,
                          }

                          ,
                          dangerMode: true,
                      });
                  } else if (!call_var_vehiclebrandadd.vehicle_brand_pattern.test(call_var_vehiclebrandadd
                          .vehicle_brand_value)) {
                      $('.vehical_brand').val("");

                      swal({

                          title: msg19,
                          cancelButtonColor: '#C1C1C1',
                          buttons: {
                              cancel: msg35,
                          }

                          ,
                          dangerMode: true,
                      });

                  } else if (!vehical_brand.replace(/\s/g, '').length) {
                      // var str = "    ";
                      $('.vehical_brand').val("");

                      swal({

                          title: msg20,
                          cancelButtonColor: '#C1C1C1',
                          buttons: {
                              cancel: msg35,
                          }

                          ,
                          dangerMode: true,
                      });
                  } else if (!call_var_vehiclebrandadd.vehicle_brand_pattern2.test(
                          call_var_vehiclebrandadd
                          .vehicle_brand_value)) {
                      $('.vehical_brand').val("");

                      swal({

                          title: msg34,
                          cancelButtonColor: '#C1C1C1',
                          buttons: {
                              cancel: msg35,
                          }

                          ,
                          dangerMode: true,
                      });

                  } else {
                      $.ajax({

                          type: 'GET',
                          url: url,
                          data: {
                              vehical_id: vehical_id,
                              vehical_brand: vehical_brand
                          }

                          ,
                          success: function(data) {
                                  var newd = $.trim(data);
                                  var classname = 'del-' + newd;

                                  if (newd == "01") {
                                      swal({

                                          title: msg21,
                                          cancelButtonColor: '#C1C1C1',
                                          buttons: {
                                              cancel: msg35,
                                          }

                                          ,
                                          dangerMode: true,
                                      });
                                  } else {
                                      $('.vehical_brand_class').append('<tr class=" row mx-1 ' +
                                          classname +
                                          ' data_vehicle_brand_name"><td class="text-start w-50">' +
                                          vehical_brand +
                                          '</td><td class="text-end w-50"><button type="button" brandid="' +
                                          data +
                                          '" deletevehicalbrand="{!! url('vehicle/vehicalbranddelete') !!}" class="btn btn-danger text-white border-0 deletevehiclebrands"><i class="fa fa-trash" aria-hidden="true"></i></button></a></td><tr>'
                                      );

                                      $('.select_vehicalbrand').append('<option selected value=' +
                                          data +
                                          '>' + vehical_brand + '</option>');

                                      $('.vehi_brand_id').append('<option value=' + data +
                                          '>' + vehical_brand + '</option>');

                                      $('.vehical_brand').val('');

                                      $('.select_vehicalbrand').trigger('change');

                                      swal({

                                          title: msg5,
                                          text: submitmsg,
                                          icon: 'success',
                                          cancelButtonColor: '#C1C1C1',
                                          buttons: {
                                              cancel: msg35,
                                          }

                                          ,
                                          dangerMode: true,
                                      }).then(() => {
                                          sessionStorage.removeItem('selectedType');
                                          $('#responsive-modal-brand').modal(
                                              'hide'); // Close the modal
                                      });
                                  }
                              }

                              ,
                      });
                  }
              }
          });

          /*vehical brand delete*/
          $('body').on('click', '.deletevehiclebrands', function() {
              var vbrandid = $.trim($(this).attr('brandid'));
              var url = $(this).attr('deletevehicalbrand');


              swal({

                  title: msg1,
                  text: msg2,
                  icon: "warning",
                  buttons: [msg3, msg4],
                  dangerMode: true,
                  cancelButtonColor: "#C1C1C1",
              }).then((isConfirm) => {
                  if (isConfirm) {
                      $.ajax({

                          type: 'GET',
                          url: url,
                          data: {
                              vbrandid: vbrandid
                          }

                          ,
                          success: function(data) {
                              $('.del-' + vbrandid).remove();
                              $(".select_vehicalbrand option[value=" + vbrandid + "]")
                                  .remove();

                              swal({

                                  title: msg5,
                                  text: vbranddelete,
                                  icon: 'success',
                                  cancelButtonColor: '#C1C1C1',
                                  buttons: {
                                      cancel: msg35,
                                  }

                                  ,
                                  dangerMode: true,
                              }).then(() => {
                                  $('#responsive-modal-brand').modal(
                                      'hide'); // Close the modal
                              });
                          }
                      });
                  } else {
                      swal({

                          title: msg7,
                          text: msg8,
                          icon: 'error',
                          cancelButtonColor: '#C1C1C1',
                          buttons: {
                              cancel: msg35,
                          }

                          ,
                          dangerMode: true,
                      });
                  }
              })
          });

          /*Fuel  Type delete*/
          $('body').on('click', '.fueldeletes', function() {
              var fueltypeid = $(this).attr('fuelid');
              var url = $(this).attr('deletefuel');

              swal({

                  title: msg1,
                  text: msg2,
                  icon: "warning",
                  buttons: [msg3, msg4],
                  dangerMode: true,
                  cancelButtonColor: "#C1C1C1",
              }).then((isConfirm) => {
                  if (isConfirm) {
                      $.ajax({

                          type: 'GET',
                          url: url,
                          data: {
                              fueltypeid: fueltypeid
                          }

                          ,
                          success: function(data) {
                              $('.del-' + fueltypeid).remove();
                              $(".select_fueltype option[value=" + fueltypeid + "]")
                                  .remove();

                              swal({

                                  title: msg5,
                                  text: fueldelete,
                                  icon: 'success',
                                  cancelButtonColor: '#C1C1C1',
                                  buttons: {
                                      cancel: msg35,
                                  }

                                  ,
                                  dangerMode: true,
                              }).then(() => {
                                  $('#responsive-modal-fuel').modal(
                                      'hide'); // Close the modal
                              });
                          }
                      });
                  } else {
                      swal({

                          title: msg7,
                          text: msg8,
                          icon: 'error',
                          cancelButtonColor: '#C1C1C1',
                          buttons: {
                              cancel: msg35,
                          }

                          ,
                          dangerMode: true,
                      });
                  }
              })
          });

          var msg10 = "{{ trans('message.Please enter only alphanumeric data') }}";
          var msg11 = "{{ trans('message.Only blank space not allowed') }}";
          var msg12 = "{{ trans('message.This Record is Duplicate') }}";
          /*Add Vehicle Model*/
          $('.vehi_model_add').click(function() {
              var model_name = $('.vehi_modal_name').val();
              var model_url = $(this).attr('modelurl');
              var brand_id = $('.vehi_brand_id').val();

              var msg9 = "{{ trans('message.Please enter model name') }}";

              function define_variable() {
                  return {
                      vehicle_model_value: $('.vehi_modal_name').val(),
                      vehicle_model_pattern: /^[a-zA-Z0-9\u0621-\u064A\u00C0-\u017F\u0600-\u06FF\u0750-\u077F\uFB50-\uFDFF\uFE70-\uFEFF\u2E80-\u2FD5\u3190-\u319f\u3400-\u4DBF\u4E00-\u9FCC\uF900-\uFAAD\u0900-\u097F\s]+$/,
                      vehicle_model_pattern2: /^[a-zA-Z0-9\u0621-\u064A\u00C0-\u017F\u0600-\u06FF\u0750-\u077F\uFB50-\uFDFF\uFE70-\uFEFF\u2E80-\u2FD5\u3190-\u319f\u3400-\u4DBF\u4E00-\u9FCC\uF900-\uFAAD\u0900-\u097F\s]*$/
                  };
              }

              var call_var_vehiclemodeladd = define_variable();

              if (model_name == "") {
                  swal({
                      title: msg9,
                      cancelButtonColor: '#C1C1C1',
                      buttons: {
                          cancel: msg35,
                      },
                      dangerMode: true,
                  });
              } else if (!call_var_vehiclemodeladd.vehicle_model_pattern.test(call_var_vehiclemodeladd
                      .vehicle_model_value)) {
                  $('.vehi_modal_name').val("");
                  swal({
                      title: msg14,
                      cancelButtonColor: '#C1C1C1',
                      buttons: {
                          cancel: msg35,
                      },
                      dangerMode: true,
                  });
              } else if (!model_name.replace(/\s/g, '').length) {
                  $('.vehi_modal_name').val("");
                  swal({
                      title: msg15,
                      cancelButtonColor: '#C1C1C1',
                      buttons: {
                          cancel: msg35,
                      },
                      dangerMode: true,
                  });
              } else if (!call_var_vehiclemodeladd.vehicle_model_pattern2.test(call_var_vehiclemodeladd
                      .vehicle_model_value)) {
                  $('.vehi_modal_name').val("");
                  swal({
                      title: msg34,
                      cancelButtonColor: '#C1C1C1',
                      buttons: {
                          cancel: msg35,
                      },
                      dangerMode: true,
                  });
              } else {
                  $.ajax({
                      type: 'GET',
                      url: model_url,
                      data: {
                          model_name: model_name,
                          brand_id: brand_id
                      },

                      beforeSend: function() {
                          $(".vehi_model_add").prop('disabled', true);
                      },

                      success: function(data) {
                          var newd = $.trim(data);
                          var classname = 'mod-' + newd;

                          if (newd == '01') {
                              swal({
                                  title: msg16,
                                  cancelButtonColor: '#C1C1C1',
                                  buttons: {
                                      cancel: msg35,
                                  },
                                  dangerMode: true,
                              });
                          } else {
                              $('.vehi_model_class').append(
                                  '<tr class=" data_color_name row mx-1 ' + classname +
                                  '"><td class="text-start col-6">' +
                                  model_name +
                                  '</td><td class="text-end col-6"><button type="button" modelid=' +
                                  data +
                                  ' deletemodel="{!! url('vehicle/vehicle_model_delete') !!}" class="btn btn-danger text-white border-0 modeldeletes"><i class="fa fa-trash" aria-hidden="true"></i></button></a></td><tr>'
                              );
                              $('.model_addname').append("<option selected value='" +
                                  model_name +
                                  "'>" + model_name +
                                  "</option>");
                              $('.vehi_modal_name').val('');

                              swal({
                                  title: msg5,
                                  text: submitmsg,
                                  icon: 'success',
                                  cancelButtonColor: '#C1C1C1',
                                  buttons: {
                                      cancel: msg35,
                                  },
                                  dangerMode: true,
                              }).then(() => {
                                  sessionStorage.removeItem('selectedBrand');
                                  $('#responsive-modal-vehi-model').modal(
                                      'hide'); // Close the modal
                              });
                          }

                          $(".vehi_model_add").prop('disabled', false);
                          return false;
                      },
                  });
              }
          });



          $('body').on('click', '.modeldeletes', function() {
              var mod_del_id = $(this).attr('modelid');
              var del_url = $(this).attr('deletemodel');
              var msg1 = "{{ trans('message.Are You Sure?') }}";
              var msg2 = "{{ trans('message.You will not be able to recover this data afterwards!') }}";
              var msg3 = "{{ trans('message.Cancel') }}";
              var msg4 = "{{ trans('message.Yes, delete!') }}";
              var msg5 = "{{ trans('message.Done!') }}";
              // var msg6 = "{{ trans('message.It was succesfully deleted!') }}";
              var msg7 = "{{ trans('message.Cancelled') }}";
              var msg8 = "{{ trans('message.Your data is safe') }}";
              swal({
                  title: msg1,
                  text: msg2,
                  icon: "warning",
                  buttons: [msg3, msg4],
                  dangerMode: true,
                  cancelButtonColor: "#C1C1C1",
              }).then((isConfirm) => {
                  if (isConfirm) {
                      $.ajax({
                          type: 'GET',
                          url: del_url,
                          data: {
                              mod_del_id: mod_del_id
                          },
                          success: function(data) {
                              $('.del-' + mod_del_id).remove();
                              $(".model_addname option[value=" + mod_del_id + "]")
                                  .remove();
                              swal({
                                  title: msg5,
                                  text: modeldelete,
                                  icon: 'success',
                                  cancelButtonColor: '#C1C1C1',
                                  buttons: {
                                      cancel: msg35,
                                  },
                                  dangerMode: true,
                              }).then(() => {
                                  $('#responsive-modal-vehi-model').modal(
                                      'hide'); // Close the modal
                              });
                          }
                      });
                  } else {
                      swal({
                          title: msg7,
                          text: msg8,
                          icon: 'error',
                          cancelButtonColor: '#C1C1C1',
                          buttons: {
                              cancel: msg35,
                          },
                          dangerMode: true,
                      });
                  }
              })
          });

          /*Fuel type*/
          $('.fueltypeadd').click(function() {

              var fuel_type = $('.fuel_type').val();
              var url = $(this).attr('fuelurl');
              var msg21 = "{{ trans('message.Please enter fuel type') }}";
              var msg22 = "{{ trans('message.Please enter only alphanumeric data') }}";
              var msg23 = "{{ trans('message.Only blank space not allowed') }}";
              var msg24 = "{{ trans('message.This Record is Duplicate') }}";
              var msg25 = "{{ trans('message.An error occurred :') }}";

              function define_variable() {
                  return {
                      vehicle_fuel_value: $('.fuel_type').val(),
                      vehicle_fuel_pattern: /^[a-zA-Z0-9\u0621-\u064A\u00C0-\u017F\u0600-\u06FF\u0750-\u077F\uFB50-\uFDFF\uFE70-\uFEFF\u2E80-\u2FD5\u3190-\u319f\u3400-\u4DBF\u4E00-\u9FCC\uF900-\uFAAD\u0900-\u097F\s]+$/,
                      vehicle_fuel_pattern2: /^[a-zA-Z0-9\u0621-\u064A\u00C0-\u017F\u0600-\u06FF\u0750-\u077F\uFB50-\uFDFF\uFE70-\uFEFF\u2E80-\u2FD5\u3190-\u319f\u3400-\u4DBF\u4E00-\u9FCC\uF900-\uFAAD\u0900-\u097F\s]*$/
                  };
              }

              var call_var_vehiclefueladd = define_variable();

              if (fuel_type == "") {
                  swal({
                      title: msg21,
                      cancelButtonColor: '#C1C1C1',
                      buttons: {
                          cancel: msg35,
                      },
                      dangerMode: true,
                  });
              } else if (!call_var_vehiclefueladd.vehicle_fuel_pattern.test(call_var_vehiclefueladd
                      .vehicle_fuel_value)) {
                  $('.fuel_type').val("");
                  swal({
                      title: msg22,
                      cancelButtonColor: '#C1C1C1',
                      buttons: {
                          cancel: msg35,
                      },
                      dangerMode: true,
                  });
              } else if (!fuel_type.replace(/\s/g, '').length) {
                  // var str = "    ";
                  $('.fuel_type').val("");
                  swal({
                      title: msg23,
                      cancelButtonColor: '#C1C1C1',
                      buttons: {
                          cancel: msg35,
                      },
                      dangerMode: true,
                  });
              } else if (!call_var_vehiclefueladd.vehicle_fuel_pattern2.test(call_var_vehiclefueladd
                      .vehicle_fuel_value)) {
                  $('.fuel_type').val("");
                  swal({
                      title: msg34,
                      cancelButtonColor: '#C1C1C1',
                      buttons: {
                          cancel: msg35,
                      },
                      dangerMode: true,
                  });

              } else {
                  $.ajax({
                      type: 'GET',
                      url: url,
                      data: {
                          fuel_type: fuel_type
                      },
                      success: function(data) {
                          var newd = $.trim(data);
                          var classname = 'del-' + newd;

                          if (newd == '01') {
                              swal({
                                  title: msg24,
                                  cancelButtonColor: '#C1C1C1',
                                  buttons: {
                                      cancel: msg35,
                                  },
                                  dangerMode: true,
                              });
                          } else {
                              $('.fuel_type_class').append('<tr class=" row mx-1 ' +
                                  classname +
                                  ' data_fuel_type_name"><td class="text-start w-50">' +
                                  fuel_type +
                                  '</td><td class="text-end w-50"><button type="button" fuelid=' +
                                  data +
                                  ' deletefuel="{!! url('vehicle/fueltypedelete') !!}" class="btn btn-danger text-white border-0 fueldeletes"><i class="fa fa-trash" aria-hidden="true"></i></button></a></td><tr>'
                              );

                              $('.select_fueltype').append('<option selected value=' + data +
                                  '>' +
                                  fuel_type + '</option>');

                              $('.fuel_type').val('');

                              swal({
                                  title: msg5,
                                  text: submitmsg,
                                  icon: 'success',
                                  cancelButtonColor: '#C1C1C1',
                                  buttons: {
                                      cancel: msg35,
                                  },
                                  dangerMode: true,
                              }).then(() => {
                                  $('#responsive-modal-fuel').modal(
                                      'hide'); // Close the modal
                              });
                          }
                      },
                  });
              }
          });

          $('body').on('click', '.addvehicleservice', function(event) {

              function define_variable() {
                  return {
                      vehical_id1: $("#vehical_id1").val(),
                      chasicno1: $("#chasicno1").val(),
                      vehicabrand1: $("#vehicabrand1").val(),
                      modelname1: $("#modelname1").val(),
                      engineno1: $("#engineno1").val(),
                      pp: $('#fueltype1').val(),
                      pricePattern: /^[0-9]*$/,
                      np: $('#number_plate').val(),
                  };
              }

              event.preventDefault();
              var call_var_vehicleadd = define_variable();
              var errro_msg = [];

              //Vehicle type
              if (call_var_vehicleadd.vehical_id1 == "") {
                  var msg = "Vehical type is required";
                  $('#errorlvehical_id1').html(msg);
                  errro_msg.push(msg);
                  return false;
              } else {
                  $('#errorlvehical_id1').html("");
                  errro_msg = [];
              }

              //Vehical brand
              if (call_var_vehicleadd.vehicabrand1 == "") {
                  var msg = "Vehical brand is required";
                  $('#errorlvehicabrand1').html(msg);
                  errro_msg.push(msg);
                  return false;
              } else {
                  $('#errorlvehicabrand1').html("");
                  errro_msg = [];
              }

              //Model name
              if (call_var_vehicleadd.modelname1 == "") {
                  var msg = "Model name is required";
                  $('#errorlmodelname1').html(msg);
                  errro_msg.push(msg);
                  return false;
              } else {
                  $('#errorlmodelname1').html("");
                  errro_msg = [];
              }

              //Fuel Type
              if (call_var_vehicleadd.pp == "") {
                  var msg = "Fuel Type is required";
                  $('#fuel1').html(msg);
                  errro_msg.push(msg);
                  return false;
              } else {
                  $('#fuel1').html("");
                  errro_msg = [];
              }

              // Number Plate
              if (call_var_vehicleadd.np == "") {
                  var msg = "Number Plate is required";
                  $('#npe').html(msg);
                  errro_msg.push(msg);
                  return false;
              }

              // AJAX request to check number plate uniqueness
              $.ajax({
                  type: 'GET',
                  url: '{!! url('vehicle/check-number-plate-unique') !!}',
                  data: {
                      numberPlate: call_var_vehicleadd.np
                  },
                  success: function(response) {
                      if (response.unique == false) {
                          var msg = "Number Plate you entered is already registered.";
                          $('#npe').html(msg);
                          errro_msg.push(msg);
                          submitForm(errro_msg);
                      } else if (response.unique == true) {
                          errro_msg = [];
                          submitForm(errro_msg);
                      }
                  }
              });

              if (errro_msg == "") {
                  // Proceed with form submission
                  submitForm();
              }

              // Function to submit form with error messages
              function submitForm(errro_msg) {
                  if (errro_msg == "") {
                      // Proceed with form submission
                      var vehical_id1 = $('#vehical_id1').val();
                      var chasicno1 = $('#chasicno1').val();
                      var vehicabrand1 = $('#vehicabrand1').val();
                      var modelyear1 = $('#modelyear1').val();
                      var fueltype1 = $('#fueltype1').val();
                      var gearno1 = $('#gearno1').val();
                      var modelname1 = $('#modelname1').val();
                      var price1 = $('#price1').val();
                      var odometerreading1 = $('#odometerreading1').val();
                      var dom1 = $('#dom1').val();
                      var gearbox1 = $('#gearbox1').val();
                      var gearboxno1 = $('#gearboxno1').val();
                      var engineno1 = $('#engineno1').val();
                      var enginesize1 = $('#enginesize1').val();
                      var keyno1 = $('#keyno1').val();
                      var engine1 = $('#engine1').val();
                      var numberPlate = $('#number_plate').val();
                      var customer_id = $('.hidden_customer_id').val();
                      var branch_id_vehicle = $('.select_branch_vehicle').val();

                      $.ajax({
                          type: 'get',
                          url: '{!! url('service/vehicleadd') !!}',
                          data: {
                              vehical_id1: vehical_id1,
                              chasicno1: chasicno1,
                              vehicabrand1: vehicabrand1,
                              modelyear1: modelyear1,
                              fueltype1: fueltype1,
                              gearno1: gearno1,
                              modelname1: modelname1,
                              price1: price1,
                              odometerreading1: odometerreading1,
                              dom1: dom1,
                              gearbox1: gearbox1,
                              gearboxno1: gearboxno1,
                              engineno1: engineno1,
                              enginesize1: enginesize1,
                              keyno1: keyno1,
                              engine1: engine1,
                              numberPlate: numberPlate,
                              customer_id: customer_id,
                              branch_id_vehicle: branch_id_vehicle
                          },
                          success: function(data) {
                              var modelname1 = $('#modelname1').val();
                              var vehicabrand1 = $('#vehicabrand1').find(":selected").text();
                              var numberPlate = $('#number_plate').val();
                              var vehical_id1 = data;

                              var optionText = vehicabrand1 + '/' + modelname1 + '/' +
                                  numberPlate + '/' +
                                  vehical_id1;

                              $('.modelnameappend').append('<option selected value="' +
                                  vehical_id1 +
                                  '">' + optionText + '</option>');
                              var vehical_id1 = $('#vehical_id1').val('');
                              var chasicno1 = $('#chasicno1').val('');
                              var vehicabrand1 = $('#vehicabrand1').val('');
                              var modelyear1 = $('#modelyear1').val('');
                              var fueltype1 = $('#fueltype1').val('');
                              var gearno1 = $('#gearno1').val('');
                              var modelname1 = $('#modelname1').val('');
                              var price1 = $('#price1').val('');
                              var odometerreading1 = $('#odometerreading1').val('');
                              var dom1 = $('#dom1').val('');
                              var gearbox1 = $('#gearbox1').val('');
                              var gearboxno1 = $('#gearboxno1').val('');
                              var engineno1 = $('#engineno1').val('');
                              var enginesize1 = $('#enginesize1').val('');
                              var keyno1 = $('#keyno1').val('');
                              var engine1 = $('#engine1').val('');
                              var number_plate = $('#number_plate').val('');
                              $(".addvehiclemsg").removeClass("hide");

                              $('#add_vehi').trigger("reset");

                              $('#vehiclemymodel').modal('toggle');
                              toastr.success('Vehicle Added Successfully.', "success");
                          },
                          error: function(e) {
                              alert(msg42 + " " + e.responseText);
                              console.log(e);
                          }
                      });
                  }
              }

              /*If vehicle add when customer is selected otherwise not add vehicle*/

          });


          $('body').on('click', '.vehiclemodel', function() {

              var cus_id = $('#customer-dropdown').val();
              
              var msg1 = "{{ trans('message.Alert') }}";
              var msg2 = "{{ trans('message.Please select customer!') }}";

              if (cus_id == "" || cus_id == null) {
                  swal({
                      title: msg1,
                      text: msg2,
                      cancelButtonColor: '#C1C1C1',
                      buttons: {
                          cancel: msg35,
                      },
                      dangerMode: true,
                  });
                  $('#vehiclemymodel').modal('toggle');
                  return false;
              } else {
                  $('#vehiclemymodel').show();
              }
          });
      });
  </script>
