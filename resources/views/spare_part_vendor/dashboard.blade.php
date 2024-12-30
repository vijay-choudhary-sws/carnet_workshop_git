@extends('layouts.app')
@section('content')
    <div class="right_col " role="main">
        <div class="">
            <div class="page-title">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><span class="titleup"> {{ trans('message.Dashboard') }} </span>
                        </div>
                        @include('dashboard.profile')
                    </nav>
                </div>
            </div>
            <div class="clearfix"></div>
            @include('success_message.message')
            <div class="row">
                <div class="col-12">
                    <div class="boxes">
                        <div class="row mb-1">
                            <div class="col-md-4">
                                <a href="{{ route('accessory.list') }}" target="blank">
                                    <div class="panel info-box panel-white">
                                        <div class="panel-body member shadow">
                                            <img src="{{ URL::asset('public/img/dashboard/employee.png') }}" width="40px"
                                                height="40px" class="dashboard_background" alt="">
                                            <div class="info-box-stats">
                                                <p class="counter">
                                                  {{$accessoryCount ?? 0 }}
                                                </p><br>
                                                <span class="info-box-title">ACCESSORIES</span>
                                            </div>

                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('sparepart.list') }}" target="blank">
                                    <div class="panel info-box panel-white">
                                        <div class="panel-body member shadow">
                                            <img src="{{ URL::asset('public/img/dashboard/customer.png') }}" width="40px"
                                                height="40px" class="dashboard_background" alt="">
                                            <div class="info-box-stats">
                                                <p class="counter">
                                                    {{$partCount ?? 0 }}
                                                </p></br>
                                                <span class="info-box-title">SPARE PART</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('tool.list') }}" target="blank">
                                    <div class="panel info-box panel-white">
                                        <div class="panel-body member shadow">
                                            <img src="{{ URL::asset('public/img/dashboard/supplier.png') }}" width="40px"
                                                height="40px" class="dashboard_background" alt="">
                                            <div class="info-box-stats">
                                                <p class="counter">
                                                    {{$toolCount ?? 0 }}
                                                </p></br>

                                                <span class="info-box-title">TOOLS</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <a href="{{ route('lubricant.list') }}" target="blank">
                                    <div class="panel info-box panel-white">
                                        <div class="panel-body member shadow">
                                            <img src="{{ URL::asset('public/img/dashboard/product.png') }}" width="40px"
                                                height="40px" class="dashboard_background" alt="">
                                            <div class="info-box-stats">
                                                <p class="counter">
                                                    {{$lubesCount ?? 0 }}
                                                </p></br>
                                                <span class="info-box-title">LUBRICANTS</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('order.list') }}">
                                    <div class="panel info-box panel-white">
                                        <div class="panel-body member shadow">
                                            <img src="{{ URL::asset('public/img/dashboard/sales.png') }}" width="40px"
                                                height="40px" class="dashboard_background" alt="">
                                            <div class="info-box-stats">
                                                <p class="counter">
                                                    {{$orderCount ?? 0 }}
                                                </p></br>

                                                <span class="info-box-title">ORDERS</span>
                                            </div>

                                        </div>
                                    </div>
                                </a>
                            </div>
                            {{-- <div class="col-md-4">
                                <a href="service/list" target="blank">
                                    <div class="panel info-box panel-white">
                                        <div class="panel-body member shadow">
                                            <img src="{{ URL::asset('public/img/dashboard/service.png') }}"
                                                width="40px" height="40px" class="dashboard_background"
                                                alt="">
                                            <div class="info-box-stats">
                                                <p class="counter">
                                                  0
                                                </p></br>

                                                <span class="info-box-title">{{ trans('message.SERVICES') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
