<div class="modal-header">
    <h4 class="modal-title" id="myLargeModalLabel">Create</h4>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="formSubmit" onsubmit="formSubmit(this);return false;">
    @csrf
    <div class="modal-body">
        <div class="container-fluid">
          <form id="vehicleModelAdd-Form" action="{{ route('sparepart.store') }}" method="post"
                                enctype="multipart/form-data" data-parsley-validate
                                class="form-horizontal form-label-left vehicleModelAddForm">

                                <div class="row row-mb-0">
                                    <label
                                        class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 text-center"
                                        for="name">{{ trans('message.Name') }} <label class="color-danger">*</label>
                                    </label>
                                    <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                                         <select class="form-control col-md-7 col-xs-12 select2-name" name="name" id="name">
                                            <option value="" selected></option>
                                            @foreach($sparePartLabels as $sparePartLabel)
                                                <option value="{{ $sparePartLabel->title }}">{{ $sparePartLabel->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 has-feedback">
                                        <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                            for="image">{{ trans('message.Image') }}</label>
                                        <div class="col-8 ps-3">
                                            <input type="file" id="image" name="image"
                                                class="form-control chooseImage">

                                            <img src="{{ url('public/img/branch/avtar.png') }}" id="imagePreview"
                                                alt="Branch Image" class="mt-2" style="width: 52px;">
                                        </div>
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
                                            class="form-control col-md-7 col-xs-12" maxlength="30">
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
                                                    <option value="{{ $unit->id }}">{{ $unit->title }}</option>
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
                                            class="form-control col-md-7 col-xs-12" maxlength="30">
                                    </div>
                                    <label
                                        class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 text-center"
                                        for="suitable-for">{{ trans('message.Suitable For') }} 
                                    </label>
                                    <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                                        <input type="text" name="suitable_for" id="suitable for"
                                            placeholder="{{ trans('message.Enter Suitable For') }}"
                                            class="form-control col-md-7 col-xs-12" maxlength="30">

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
                                            class="form-control col-md-7 col-xs-12" maxlength="30">
                                    </div>
                                    <label
                                        class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 text-center"
                                        for="discount">{{ trans('message.Discount') }} 
                                    </label>
                                    <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                                        <input type="number" name="discount" id="discount"
                                            placeholder="{{ trans('message.Enter Discount') }}"
                                            class="form-control col-md-7 col-xs-12" maxlength="30">

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
                                            class="form-control col-md-7 col-xs-12" maxlength="30">
                                    </div>
                                    <label
                                        class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 text-center"
                                        for="sp_type">{{ trans('message.SP Type') }} 
                                    </label>
                                    <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                                        <select name="sp_type" id="sp_type"
                                            class="form-control col-md-7 col-xs-12 vehicle_types form-select">
                                            <option value="0">{{ trans('message.New') }}</option>
                                            <option value="1">{{ trans('message.Used') }}</option>
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
                                            class="form-control col-md-7 col-xs-12" maxlength="30"></textarea>
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
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-primary" type="submit">Save<i
            class="st_loader fa-btn-loader fa fa-refresh fa-spin fa-1x fa-fw"
            style="display:none;"></i></button>
    </div>
</form>