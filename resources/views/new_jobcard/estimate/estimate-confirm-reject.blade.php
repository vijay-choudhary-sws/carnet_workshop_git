@extends('layouts.app')
@section('content')
    <style>
        @media (max-width: 767px) {
            .small-screen {
                background-color: #EA6B00 !important;
            }
        }

        .grand_total_freeservice {
            background-color: #ffffff;
        }
    </style>
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><a href="{!! url('/quotation/list') !!}"
                                id=""><i class=""><img
                                        src="{{ URL::asset('public/supplier/Back Arrow.png') }}"
                                        class="back-arrow"></i><span class="titleup">
                                    {{ trans('Accept/Reject Quotation') }}</span></a>
                        </div>
                        @include('dashboard.profile')
                    </nav>
                </div>
            </div>
            @include('success_message.message')
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel table_up_div mb-0">
                        <div id="sales_print">

                            <div class="row mx-0">
                                <div class="row">
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <div
                                            class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6  printimg position-relative ms-2">
                                            <img src="{{ url('/public/general_setting/' . $logo->logo_image) }}"
                                                class="system_logo_img">
                                        </div>
                                        <div
                                            class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 system_address mt-1 ms-1">
                                            <p class="mb-0">
                                                <img src="{{ URL::asset('public/img/icons/Vector (15).png') }}"
                                                    class="m-2">

                                                <?php
                                                $taxNumber = $taxName = null;
                                                if (!empty($service_taxes)) {
                                                    foreach ($service_taxes as $tax) {
                                                        $taxName = getTaxNameFromTaxTable($tax);
                                                        $taxNumber = getTaxNumberFromTaxTable($tax);
                                                    }
                                                }
                                                echo ' ' . $logo->email;
                                                echo '<br>&nbsp;&nbsp;<i class="fa fa-phone fa-lg" aria-hidden="true" class="mb-0"></i>&nbsp;&nbsp;&nbsp;&nbsp;' . $logo->phone_number;
                                                ?>
                                            <div class="col-12 d-flex align-items-start m-1">
                                                <img src="{{ URL::asset('public/img/icons/Vector (14).png') }}"
                                                    class="m-1">
                                                <div class="col mx-2">
                                                    <?php
                                                    echo '&nbsp;' . $logo->address . ' ';
                                                    echo ' ' . getCityName($logo->city_id);
                                                    echo ',&nbsp;' . getStateName($logo->state_id);
                                                    echo ',&nbsp;' . getCountryName($logo->country_id);
                                                    ?>
                                                </div>
                                            </div>
                                            <?php
                                            if ($taxName !== null && $taxNumber !== null) {
                                                // echo '<br>' . $taxName . ': &nbsp;' . $taxNumber;
                                                echo '<b>&nbsp; ' . $taxName . ' :</b> ' . $taxNumber;
                                            }
                                            ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 quotation">
                                        <table class="table quotation">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12 col-xs-12 d-inline-flex">
                                                            <p class="fw-bold mb-0"><i class="fa fa-user fa-lg"></i></p>
                                                            <p class="cname mb-0 ps-2"><?php echo getCustomerName($customer->id); ?></p>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12 col-xs-12 d-inline-flex">
                                                            <p class="fw-bold mb-0"><img
                                                                    src="{{ URL::asset('public/img/icons/Vector (14).png') }}">
                                                            </p>
                                                            <p class="cname mb-0 ps-2"><?php echo getCustomerAddress($customer->id) . ', '; ?>
                                                                <?php echo getCityName($customer->city_id) != null ? getCityName($customer->city_id) . ', ' : ''; ?> <?php echo getStateName($customer->state_id) . ', ' . getCountryName($customer->country_id); ?></p>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12 col-xs-12 d-inline-flex">
                                                            <p class="fw-bold mb-0"><i class="fa fa-phone fa-lg"></i></p>
                                                            <p class="cname mb-0 ps-2"><?php echo $customer->mobile_no; ?></p>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12 col-xs-12 d-inline-flex">
                                                            <p class="fw-bold mb-0"><img
                                                                    src="{{ URL::asset('public/img/icons/Vector (15).png') }}"
                                                                    class="m-0"></p>
                                                            <p class="cname mb-0 ps-2"><?php echo $customer->email; ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    @if (getCustomerCompanyName($customer->id) != '')
                                                        <div class="row">
                                                            <div class="col-md-12 col-sm-12 col-xs-12 d-inline-flex">
                                                                <p class="fw-bold mb-0">{{ trans('message.Company') }}:</p>
                                                                <p class="cname mb-0 ps-2"><?php echo getCustomerCompanyName($customer->id); ?></p>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <?php
                                                if ($customer->tax_id !== null) {
                                                ?>

                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12 col-xs-12 d-inline-flex">
                                                            <p class="fw-bold mb-0">{{ trans('message.Tax Id') }} :</p>
                                                            <p class="cname mb-0 ps-2"><?php echo $customer->tax_id; ?></p>
                                                        </div>
                                                    </div>
                                                    <?php
                                                } ?>

                                                </div>
                                                </tbody>
                                            </div>
                                        </table>

                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div
                                        class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 table-responsive ms-0 ps-0">
                                        <table
                                            class="table table-bordered table-responsive adddatatable quotationDetail mb-0"
                                            width="100%">
                                            <span class="border-0">
                                                <thead>
                                                    <tr>
                                                        <th class="cname text-left">{{ trans('message.Quotation Number') }}
                                                        </th>
                                                        <th class="cname text-left">{{ trans('message.Vehicle Name') }}
                                                        </th>
                                                        <th class="cname text-left">
                                                            {{ trans('message.Number Plate' ?? '-') }}</th>
                                                        <th class="cname text-left">{{ trans('message.Date') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="cname text-left fw-bold">
                                                            {{ $newjobcard->jobcard_number }}</td>
                                                        <td class="cname text-left fw-bold">
                                                            {{ getVehicleName($newjobcard->vehicle_id) }}</td>
                                                        <td class="cname text-left fw-bold">
                                                            {{ getVehicleNumberPlate($newjobcard->vehicle_id) }}</td>
                                                        <td class="cname text-left fw-bold">
                                                            {{ \carbon\carbon::parse($newjobcard->entry_date)->format('d-m-Y h:i A') }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </span>
                                        </table>

                                    </div>
                                </div>
                                <div class="row">
                                    <div
                                        class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 table-responsive ms-0 ps-0">
                                        <table
                                            class="table table-bordered table-responsive adddatatable quotationDetail mb-0"
                                            width="100%">
                                            <span class="border-0">
                                                <thead>
                                                    <tr>
                                                        <th class="cname text-left">{{ trans('message.Repair Category') }}
                                                        </th>
                                                        <th class="cname text-left">{{ trans('message.Service Type') }}
                                                        </th>
                                                        <th class="cname text-left">{{ trans('message.Details') }}</th>
                                                        <th class="cname text-left"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="cname text-left fw-bold">-</td>
                                                        <td class="cname text-left fw-bold">Paid</td>
                                                        <td class="cname text-left fw-bold">-</td>
                                                        <td class="cname text-left fw-bold"></td>
                                                    </tr>
                                                </tbody>
                                            </span>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                @if (!empty($jobCardSpareParts))
                                    <div class="row mx-0 table-responsive">
                                        <table class="table table-bordered mb-0">
                                            <span class="border-0">
                                                <tbody>
                                                    <tr class="printimg">
                                                        <td class="cname fw-bold">
                                                            {{ trans('message.Observation Charges') }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </span>
                                        </table>
                                    </div>
                                    <div
                                        class="table-responsive col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                                        <table class="table table-bordered adddatatable">
                                            <thead>
                                                <tr>
                                                    <th class="text-start" style="width: 5%;">#</th>
                                                    <th class="text-left">{{ trans('message.Product Name') }}</th>
                                                    <th class="text-center">{{ trans('message.Price') }}
                                                        (<?php echo getCurrencySymbols(); ?>)
                                                    </th>
                                                    <th class="text-center">{{ trans('message.Quantity') }} </th>
                                                    <th class="text-center">
                                                        {{ trans('message.Discount') . ' (' . getCurrencySymbols() . ')' }}
                                                    </th>
                                                    <th class="text-center" style="width: 25%;">
                                                        {{ trans('message.Total Price') }}
                                                        (<?php echo getCurrencySymbols(); ?>)
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($jobCardSpareParts as $jobCardSparePart)
                                                    <tr>
                                                        <td class="text-center cname">{{ $loop->iteration }}</td>
                                                        <td class="text-left cname">
                                                            {{ $jobCardSparePart->stock_label_name ?? '-' }}</td>
                                                        <td class="text-end cname"><?php echo number_format((float) $jobCardSparePart->price, 2); ?></td>
                                                        <td class="text-center cname">
                                                            {{ $jobCardSparePart->quantity ?? 0 }}</td>
                                                        <td class="text-center cname">
                                                            {{ number_format($jobCardSparePart->discount, 2) ?? 0 }}</td>
                                                        <td class="text-end cname"><?php echo number_format((float) $jobCardSparePart->final_amount, 2); ?></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                                @if (!empty($jobCardExtraCharges))
                                    <div
                                        class="table-responsive col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ms-0">
                                        <table class="table table-bordered">
                                            <tr class="printimg">
                                                <td class="cname fw-bold" colspan="7">
                                                    {{ trans('message.Other Service Charges') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div
                                        class="table table-responsive col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 mb-0">
                                        <table class="table table-bordered adddatatable mx-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="width: 5%;">#</th>
                                                    <th class="text-center">{{ trans('message.Charge for') }}</th>
                                                    <th class="text-center">{{ trans('message.Price') }}
                                                        (<?php echo getCurrencySymbols(); ?>)</th>
                                                    <th class="text-center" style="width: 25%;">
                                                        {{ trans('message.Total Price') }}
                                                        (<?php echo getCurrencySymbols(); ?>)
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($jobCardExtraCharges as $jobCardExtraCharge)
                                                    <tr>
                                                        <td class="text-center cname">{{ $loop->iteration }}</td>
                                                        <td class="text-center cname">
                                                            {{ $jobCardExtraCharge->label ?? '-' }}
                                                        </td>
                                                        <td class="text-center cname"><?php echo number_format((float) $jobCardExtraCharge->charges, 2); ?></td>
                                                        <td class="text-end cname"><?php echo number_format((float) $jobCardExtraCharge->charges, 2); ?></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                                <div class="row table-responsive ms-0">
                                    <table class="table table-bordered quotation_total">
                                        <tr>
                                            <td class="text-end cname">
                                                {{ trans('message.Total Service Amount') . ' (' . getCurrencySymbols() . ')' }}:
                                            </td>
                                            <td class="text-end cname fw-bold gst f-17" style="width: 25%;">
                                                <b>{{ number_format($newjobcard->amount, 2) }}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-end cname"> Discount ({{ getCurrencySymbols() }}) :</td>
                                            <td class="text-end cname fw-bold gst f-17"style="width: 25%;">
                                                <b>{{ number_format($newjobcard->discount, 2) }}</b>
                                            </td>
                                        </tr>
                                        @if (!empty($newjobcard->advance))
                                            <tr>
                                                <td class="text-end cname"> Advance ({{ getCurrencySymbols() }}) :</td>
                                                <td class="text-end cname fw-bold gst f-17"style="width: 25%;">
                                                    <b>{{ number_format($newjobcard->advance, 2) }}</b>
                                                </td>
                                            </tr>
                                        @endif

                                        <tr class="grand_total_freeservice">
                                            <td class="text-end cname">
                                                {{ trans('message.Grand Total') . ' (' . getCurrencySymbols() . ')' }} :
                                            </td>
                                            <td class="text-end fw-bold cname gst f-17 " style="width: 25%;">
                                                {{ number_format($newjobcard->balance_amount, 2) }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="ps-2 ps-lg-0">
                                <form action="{{ route('jobcard.status') }}" method="POST"
                                    enctype="multipart/form-data" onsubmit="event.preventDefault();submitJobcard(this);">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $newjobcard->id }}">

                                    <div class="row mx-0">
                                        <div class="row col-6 ps-2">
                                            <label class="control-label col-4"
                                                for="first-name">{{ trans('message.Status') }}
                                                <span class="color-danger">*</span>
                                            </label>
                                            <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                                <label class="radio-inline">
                                                    <input type="radio" name="status" id="accept" value="1"
                                                        required class="margin-left-10" @checked($status == '1')>
                                                    {{ trans('message.Accepted') }}
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="status" id="reject" value="0"
                                                        required class="margin-left-10" @checked($status == '0')>
                                                    {{ trans('message.Rejected') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ps-2 m-3">
                                        <div class="row col-6">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <button type="submit"
                                                class="btn btn-success">{{ trans('message.SUBMIT') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /page content -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function submitJobcard(e) {

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, change it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = $(e).attr('action');
                    let formData = $(e).serialize();

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: formData,
                        success: function(response) {
                            if (response.status === 1) {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "success",
                                    title: "Confirmation Accepted Successfully!",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                setTimeout(() => {
                                    window.location.replace("{{ route('thankyou') }}");
                                }, 1500);
                            } else {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "error",
                                    title: response.msg,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;

                                let errorMessage = '<ul class="list-unstyled">';
                                for (const field in errors) {
                                    if (errors.hasOwnProperty(field)) {
                                        errorMessage +=
                                            `<li>${errors[field][0]}</li>`;
                                    }
                                }
                                errorMessage += '</ul>';

                                Swal.fire({
                                    position: "top-end",
                                    icon: "error",
                                    title: errorMessage,
                                    showConfirmButton: false,
                                    timer: 5000
                                });
                            } else {
                                toastr.error('An unexpected error occurred. Please try again.');
                            }
                        }
                    });
                }
            });
        }
    </script>
@endsection
