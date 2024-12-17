@extends('layouts.app')
@section('content')
   
    <!-- page content -->
    <div class="right_col" role="main">

    <div class="page-title">
        <div class="nav_menu">
            <nav>
                <div class="nav toggle">
                    <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
                    <a href="{!! url('/service/list') !!}" id=""><i class=""><img src="{{ URL::asset('public/supplier/Back Arrow.png') }}" class="back-arrow"></i><span class="titleup">
                            {{ trans('message.Add Services') }}</span></a>
                </div>
                @include('dashboard.profile')
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
      
              @livewire('create')
        </div>
    </div>




</div>
    <!-- /page content -->

@endsection
