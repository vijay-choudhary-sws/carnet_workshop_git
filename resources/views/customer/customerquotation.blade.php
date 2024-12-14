@extends('layouts.app')
@section('content')
<?php if (getLangCode() == 'pl') {
?>
    <style>
        @media (max-width: 320px) {
            ul.bar_tabs>li.active {
                margin-top: 0px !important;
                margin-left: 8px !important;
            }

            .x_panel {
                margin-top: 40px !important;
            }
        }

        @media (max-width: 30px) {
            ul.bar_tabs>li.active {
                margin-top: 0px !important;
                margin-left: 8px !important;
            }

            .x_panel {
                margin-top: 40px !important;
            }
        }

        /* new added */
        ul.recent_box {
            height: 220px;
            width: 100%;
        }
    </style>
<?php
} ?>
<?php if (
    getLangCode() == 'gr' ||
    getLangCode() == 'it' ||
    getLangCode() == 'id' ||
    getLangCode() == 'tr' ||
    getLangCode() == 'ro' ||
    getLangCode() == 'hr' ||
    getLangCode() == 'hu'
) {
?>
    <style>
        @media (max-width: 320px) {
            ul.bar_tabs>li.active {
                margin-top: 0px !important;
                margin-left: 0px !important;
            }

            .x_panel {
                margin-top: 40px !important;
            }
        }

        @media (max-width: 30px) {
            ul.bar_tabs>li.active {
                margin-top: 0px !important;
                margin-left: 0px !important;
            }

            .x_panel {
                margin-top: 40px !important;
            }
        }
    </style>
<?php
} ?>
<?php if (getlangCode() == 'ta') {
?>
    <style>
        @media (max-width: 320px) {
            ul.bar_tabs>li.active {
                margin-top: 0px !important;
                margin-left: 0px !important;
            }

            .x_panel {
                margin-top: 60px !important;
            }
        }

        @media (max-width: 30px) {
            ul.bar_tabs>li.active {
                margin-top: 0px !important;
                margin-left: 0px !important;
            }

            .x_panel {
                margin-top: 60px !important;
            }
        }

        @supports (-webkit-touch-callout: none) {
            ul.bar_tabs>li.active {
                margin-top: 1px !important;
                margin-left: 0px !important;
            }

            .x_panel {
                margin-top: 40px !important;
            }
        }
    </style>
<?php
} ?>
<?php if (
    getLangCode() == 'cs' ||
    getLangCode() == 'ru' ||
    getLangCode() == 'vi'
) {
?>
    <style>
        @media (max-width: 320px) {
            ul.bar_tabs>li.active {
                margin-top: 0px !important;
                margin-left: 0px !important;
            }

            .x_panel {
                margin-top: 40px !important;
            }
        }

        @media (max-width: 30px) {
            ul.bar_tabs>li.active {
                margin-top: 0px !important;
                margin-left: 0px !important;
            }

            .x_panel {
                margin-top: 40px !important;
            }
        }

        @supports (-webkit-touch-callout: none) {
            ul.bar_tabs>li.active {
                margin-top: 1px !important;
                margin-left: 0px !important;
            }

            .x_panel {
                margin-top: 40px !important;
            }
        }
    </style>
<?php
} ?>
<style>
    .right_side .table_row,
    .member_right .table_row {
        border-bottom: 1px solid #dedede;
        float: left;
        width: 100%;
        padding: 1px 0px 4px 2px;
    }

    .table_row .table_td {
        padding: 8px 8px !important;
    }

    .report_title {
        float: left;
        font-size: 20px;
        width: 100%;
    }

    @media (min-width: 320px) {
        .ul.bar_tabs>li.active {
            margin-left: 4px !important;
        }
    }
</style>

<!-- page content -->
<div class="right_col" role="main">
    <!-- MOT Model-->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
    <!-- free service  model-->
    <div id="myModal-free-open" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg modal-xs">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="myLargeModalLabel" class="modal-title"> {{ trans('message.Free Service Details') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
    <!-- Paid Service view -->
    <div id="myModal-paid-service" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg modal-xs">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="myLargeModalLabel" class="modal-title">{{ trans('message.Paid Service Details') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
    <!--  Repeat job service view -->
    <div id="myModal-repeatjob" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg modal-xs">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="myLargeModalLabel" class="modal-title">{{ trans('message.Repeat Job Service Details') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
    <!--  Free service customer view -->
    <div id="myModal-customer-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg modal-xs">
            <!-- Modal content-->
            <div class="modal-content">

                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
    <div class="">
        <div class="page-title">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><span class="titleup"><a href="{!! url('/customer/list') !!}"><img src="{{ URL::asset('public/supplier/Back Arrow.png') }}" class="me-2 back-arrow"> {{ $customer->name . ' ' . $customer->lastname }}</span></a>
                    </div>
                    @include('dashboard.profile')
                </nav>
            </div>
        </div>
    </div>
    <div class="row view_page_header_bg ms-0">
        <div class="row">
            <div class="col-xl-10 col-md-9 col-sm-10">
                <div class="user_profile_header_left">
                    <img class="user_view_profile_image" src="{{ URL::asset('public/customer/' . $customer->image) }}">
                    <div class="row">
                        <div class="view_top1">
                            <div class="col-xl-12 col-md-12 col-sm-12">
                                <label class="nav_text h5 user-name fh5">
                                    {{ $customer->name . ' ' . $customer->lastname }}&nbsp;
                                </label>
                                @can('customer_edit')
                                <div class="view_user_edit_btn d-inline">
                                    <a href="{!! url('/customer/list/edit/' . $customer->id) !!}">
                                        <img src="{{ URL::asset('public/img/dashboard/Edit.png') }}">
                                    </a>
                                </div>
                                @endcan
                            </div>
                            <div class="col-xl-12 col-md-12 col-sm-12 nav_text mt-2">
                                <div class="d-lg-inline">
                                    <i class=" fa fa-phone"></i> {{ $customer->mobile_no }}
                                </div>
                                <div class="d-lg-inline">
                                    <i class=" fa fa-envelope"></i> {{ $customer->email }}
                                </div>
                            </div>
                            <div class="col-xl-12 col-md-12 col-sm-12 heading_view mt-3" style="width: 90%;">
                                <i class="fa-solid fa-location-dot"></i>
                                <lable class="">
                                    {{ $customer->address }}
                                    <!-- , <?php echo getCityName($customer->city_id) != null ? getCityName($customer->city_id) . ',' : ''; ?>{{ getStateName($customer->state_id) }}, {{ getCountryName($customer->country_id) }}. -->
                                </lable>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-3 col-sm-2">
                <div class="group_thumbs">
                    <img src="{{ URL::asset('public/img/dashboard/Design.png') }}" height="93px" width="134px">
                </div>
            </div>
        </div>
    </div>
    @if (session('message'))
    <div class="row massage">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="checkbox checkbox-success checkbox-circle mb-2 alert alert-success alert-dismissible fade show">
                <input id="checkbox-10" type="checkbox" checked="">
                <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ session('message') }} </label>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 1rem 0.75rem;"></button>
            </div>
        </div>
    </div>
    @endif
    <section id="" class="">

        <div class="panel-body padding_0">
        <div class="row mt-4">
            <div class="col-xl-11 col-md-11 col-sm-11 pt-3 table-responsive">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                <a href="{!! url('/customer/list/' . $customer->id) !!}" class="nav-link nav-link-not-active fw-bold">
                    {{ trans('message.GENERAL') }}</a>
                </li>
                @can('vehicle_view')
                <li class="nav-item">
                <a href="{!! url('/customer/list/vehicle/' . $customer->id) !!}" class="nav-link nav-link-not-active fw-bold">
                    {{ trans('message.VEHICLE DETAILS') }}</a>
                </li>
                @endcan
                @can('jobcard_view')
                <li class="nav-item">
                <a href="{!! url('/customer/list/jobcard/' . $customer->id) !!}" class="nav-link nav-link-not-active fw-bold">
                    {{ trans('message.JOB CARDS') }}</a>
                </li>
                @endcan
                @can('quotation_view')
                <li class="nav-item">
                <a href="{!! url('/customer/list/quotation/' . $customer->id) !!}" class="nav-link active fw-bold">
                    {{ trans('message.QUOTATIONS') }}</a>
                </li>
                @endcan
                @can('invoice_view')
                <li class="nav-item">
                <a href="{!! url('/customer/list/invoice/' . $customer->id) !!}" class="nav-link nav-link-not-active fw-bold">
                    {{ trans('message.INVOICES') }}</a>
                </li>
                @endcan
                <li class="nav-item">
                <a href="{!! url('/customer/list/payment/' . $customer->id) !!}" class="nav-link nav-link-not-active fw-bold">
                    {{ trans('message.PAYMENTS') }}</a>
                </li>
                <li class="nav-item text-uppercase">
                <a href="{!! url('/customer/list/notes/' . $customer->id) !!}" class="nav-link nav-link-not-active fw-bold">
                    {{ trans('message.Notes') }}</a>
                </li>
            </ul>

            </div>
            @canany(['vehicle_add', 'service_add', 'quotation_add', 'income_add'])
            <div class="ms-lg-auto col-xl-1 col-md-1 col-sm-1 text-end">
            <div class="dropdown_toggle">
                <img src="{{ URL::asset('public/img/icons/plus Button.png') }}" class="btn dropdown-toggle border-0" type="button" id="dropdownMenuButtonAction" data-bs-toggle="dropdown" aria-expanded="false">
                <ul class="dropdown-menu heder-dropdown-menu action_dropdown shadow py-2" aria-labelledby="dropdownMenuButtonAction">
                @can('vehicle_add')
                <li><a class="dropdown-item" href="{!! url('/vehicle/add') !!}?c_id={{ $customer->id }}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.VEHICLE DETAILS') }}</a></li>
                @endcan
                @can('service_add')
                <li><a class="dropdown-item" href="{!! url('/service/add') !!}?c_id={{ $customer->id }}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.JOB CARDS') }}</a></li>
                @endcan
                @can('quotation_add')
                <li><a class="dropdown-item" href="{!! url('/quotation/add') !!}?c_id={{ $customer->id }}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.QUOTATIONS') }}</a></li>
                @endcan
                <!-- @can('income_add')
                    <li><a class="dropdown-item" href="?c_id={{ $customer->id }}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.INVOICES') }}</a></li>
                    @endcan -->
                </ul>
            </div>
            </div>
            @endcanany
        </div>

        </div>
        <div class="x_panel bgr">
            <div class="row">
                <?php
                if (count($service) == 0) {
                ?>
                    <p style="text-align: center;"><img src="{{ URL::asset('public/img/dashboard/No-Data.png') }}" width="300px"></p>
                <?php
                } else {
                    foreach ($service as $services)
                ?>

                <?php
            }
                ?>
            </div>
            @if (!empty($services))
            <table id="supplier" class="table responsive jumbo_table" style="width:100%">
                <thead>
                    <tr>
                        <th>{{ trans('message.Quotation No') }}</th>
                        <th>{{ trans('message.Customer Name') }}</th>
                        <th>{{ trans('message.Date') }}</th>
                        <th>{{ trans('message.Service Category') }}</th>
                        <th>{{ trans('message.Number Plate') }}</th>
                        <th>{{ trans('message.Price') }} ({{ getCurrencySymbols() }})</th>
                        <th>{{ trans('message.MOT Test Details') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($service) !== 0)
                    @foreach ($service as $services)
                    <tr>
                        <td>{{ getQuotationNumber($services->job_no) }}</td>
                        <td>{{ getCustomerName($services->customer_id) }}</td>
                        <?php $date_db = date('Y-m-d', strtotime($services->service_date)); ?>
                        @if (!empty($current_month) && strpos($available, $date_db) !== false)
                        <td><span class="label label-danger">{{ date(getDateFormat(), strtotime($date_db)) }}</span>
                        </td>
                        @else
                        <td> {{ date(getDateFormat(), strtotime($date_db)) }}</td>
                        @endif
                        <td>{{ $services->service_category }}</td>
                        <!-- <td>{{ getAssignTo($services->assign_to) }}</td> -->
                        <td>{{ getVehicleNumberPlate($services->vehicle_id) ?? trans('message.Not Added') }}
                        </td>
                        <td>{{ getTotalPriceOfQuotation($services->id) }}</td>
                        <?php $coupon = getAllCoupon($services->customer_id, $services->vehicle_id);
                        ?></td>
                        <td>@if ($services->mot_status == 1 )<button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" serviceid="{{ $services->id }}" class="btn save border-0"><img src="{{ URL::asset('public/img/list/Vector.png') }}" class=""> {{ trans('message.View') }}</button>@endif</td>
                    </tr>
                    @endforeach
                    @else

                    @endif
                </tbody>
            </table>
            @endif
        </div>
        <!-- END PANEL BODY DIV-->

    </section>
</div>
<!-- Page content end -->



<!-- Scripts starting -->
<script src="{{ URL::asset('vendors/jquery/dist/jquery.min.js') }}"></script>

<script type="text/javascript">
    /****** Free Service only *******/
    $(document).ready(function() {
        $(".freeserviceopen").click(function() {
            $('.modal-body').html("");

            var f_serviceid = $(this).attr("f_serviceid");
            var url = $(this).attr('url');

            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    f_serviceid: f_serviceid
                },
                success: function(data) {
                    $('.modal-body').html(data.html);
                },
                beforeSend: function() {
                    $(".modal-body").html(
                        "<center><h2 class=text-muted><b>Loading...</b></h2></center>");
                },
                error: function(e) {
                    alert("An error occurred: " + e.responseText);
                    console.log(e);
                }
            });
        });


        /****** Paid Service only *******/
        $(".paidservice").click(function() {
            $('.modal-body').html("");

            var p_serviceid = $(this).attr("p_serviceid");
            var url = $(this).attr('url');

            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    p_serviceid: p_serviceid
                },
                success: function(data) {
                    $('.modal-body').html(data.html);
                },
                beforeSend: function() {
                    $(".modal-body").html(
                        "<center><h2 class=text-muted><b>Loading...</b></h2></center>");
                },
                error: function(e) {
                    alert("An error occurred: " + e.responseText);
                    console.log(e);
                }
            });
        });

        /******* Repeat job  Service only *******/
        $(".repeatjobservice").click(function() {
            $('.modal-body').html("");

            var r_service = $(this).attr("r_service");
            var url = $(this).attr('url');

            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    r_service: r_service
                },
                success: function(data) {
                    $('.modal-body').html(data.html);
                },
                beforeSend: function() {
                    $(".modal-body").html(
                        "<center><h2 class=text-muted><b>Loading...</b></h2></center>");
                },
                error: function(e) {
                    alert("An error occurred: " + e.responseText);
                    console.log(e);
                }
            });
        });

        /******* Free cusomer model service *******/
        $(".customeropenmodel").click(function() {
            $('.modal-body').html("");
            var open_customer_id = $(this).attr("open_customer_id");
            var url = $(this).attr('url');

            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    servicesid: open_customer_id
                },
                success: function(data) {
                    $('.modal-body').html(data.html);
                },
                beforeSend: function() {
                    $(".modal-body").html(
                        "<center><h2 class=text-muted><b>Loading...</b></h2></center>");
                },
                error: function(e) {
                    alert("An error occurred: " + e.responseText);
                    console.log(e);
                }
            });
        });
    });
    $(document).ready(function() {
        var search = "{{ trans('message.Search...') }}";
        var info = "{{ trans('message.Showing page _PAGE_ - _PAGES_') }}";
        var zeroRecords = "{{ trans('message.Nothing found - sorry') }}";
        var infoEmpty = "{{ trans('message.No records available') }}";

        $('#supplier').DataTable({
            columnDefs: [{
                width: 2,
                targets: 0
            }],
            fixedColumns: true,
            paging: true,
            scrollCollapse: true,
            scrollX: true,
            // scrollY: 300,

            responsive: true,
            "language": {
                lengthMenu: "_MENU_ ",
                info: info,
                zeroRecords: zeroRecords,
                infoEmpty: infoEmpty,
                infoFiltered: '(filtered from _MAX_ total records)',
                searchPlaceholder: search,
                search: '',
                paginate: {
                    previous: "<",
                    next: ">",
                }
            },
            aoColumnDefs: [{
                bSortable: false,
                aTargets: [-1]
            }],

        });
        // For get getParameterByName
        function getParameterByName(name, url = window.location.href) {
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
        }

        $('body').on('click', '.save', function() {
            var servicesid = $(this).attr("serviceid");
            var url = "<?php echo url('/mot'); ?>";
            var currentPageAction = getParameterByName('page_action');
            // Construct the URL for AJAX request with page_action parameter
            if (currentPageAction) {
                url += '?page_action=' + currentPageAction;
            }
            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    servicesid: servicesid
                },
                dataType: 'json',
                success: function(response) {
                    $('.modal-body').html(response.html);
                },
            });

        });
    });
</script>
@endsection