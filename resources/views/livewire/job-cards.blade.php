@extends('layouts.app')
@section('content')
   
  
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
                            <span class="titleup">{{ trans('message.Job Card') }} </span>
                        </div>
                        @include('dashboard.profile')
                    </nav>
                </div>
            </div>
            @include('success_message.message')
     <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel table_up_div">
         
          <table id="supplier" class="table jambo_table" style="width: 100px;">
            <thead>
              <tr>
                <!-- <th> </th> -->
                <th>{{ trans('message.Job Card No') }}.</th>
                <th>{{ trans('message.Service Type') }}</th>
                <th>{{ trans('message.Customer Name') }}</th>
                <th>{{ trans('message.Service Date') }}</th>
                <th>{{ trans('message.Upcoming Service Date') }}</th>
                <th>{{ trans('message.Status') }}</th>
                @canany(['invoice_add', 'invoice_view', 'jobcard_edit', 'gatepass_add', 'gatepass_view'])
                <th>{{ trans('message.Action') }}</th>
                @endcanany
              </tr>
            </thead>
            <tbody>
              @if (!empty($services))
              <?php $i = 1; ?>
              @foreach ($services as $servicess)
              <tr>
                <!-- <td>
                  <label class="container">
                    <input type="checkbox" name="chk">
                    <span class="checkmark"></span>
                  </label>
                </td> -->
                <td>{{ $servicess->job_no }} 
                  <!-- <a data-toggle="tooltip" data-placement="bottom" title="Job Card No." class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a> -->
                </td>
                <td>
                  {{ trans('message.' . ucwords($servicess->service_type)) }} 
                  <!-- <a data-toggle="tooltip" data-placement="bottom" title="Service Type" class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a> -->
                </td>
                <td>{{ getCustomerName($servicess->customer_id) }} 
                  <!-- <a data-toggle="tooltip" data-placement="bottom" title="Customer Name" class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a> -->
                </td>
                <?php $dateservice = date('Y-m-d', strtotime($servicess->service_date)); ?>
                @if (strpos($available, $dateservice) !== false)
                <td>
                  <span class="label  label-danger" style="font-size:13px;">{{ date(getDateFormat(), strtotime($dateservice)) }} 
                    <!-- <a data-toggle="tooltip" data-placement="bottom" title="Service Date" class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a> -->
                  </span>
                </td>
                @else
                <td>{{ date(getDateFormat(), strtotime($dateservice)) }} 
                  <!-- <a data-toggle="tooltip" data-placement="bottom" title="Service Date" class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a> -->
                </td>
                @endif
                <td>{{ $servicess->next_date }}</td>
                <td>
                  <?php
                    // Get the current date
                    $currentDate = date('Y-m-d');

                    // Check if service is open
                    if ($servicess->done_status == 0) {
                    // Check if the service date is in the future
                    if ($servicess->service_date > $currentDate) {
                      echo '<span style="color: rgb(0, 0, 255);">' . trans('message.Upcoming') . '</span>';
                    } else {
                      echo '<span style="color: rgb(255, 0, 0);">' . trans('message.Open') . '</span>';
                    }
                    } elseif ($servicess->done_status == 1) {
                      echo '<span style="color: rgb(0, 128, 0);">' . trans('message.Completed') . '</span>';
                    } elseif ($servicess->done_status == 2) {
                      echo '<span style="color: rgb(255, 165, 0);">' . trans('message.Progress') . '</span>';
                    }
                  ?>
                </td>
                
                @canany(['invoice_add', 'invoice_view', 'jobcard_edit', 'gatepass_add', 'gatepass_view'])
                <td>
                  <div class="dropdown_toggle">
                    <img src="{{ URL::asset('public/img/list/dots.png') }}" class="btn dropdown-toggle border-0" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <ul class="dropdown-menu heder-dropdown-menu action_dropdown shadow py-2" aria-labelledby="dropdownMenuButton1">
                      @if (getUserRoleFromUserTable(Auth::User()->id) == 'admin' || getUserRoleFromUserTable(Auth::User()->id) == 'supportstaff' || getUserRoleFromUserTable(Auth::User()->id) == 'accountant' || getUserRoleFromUserTable(Auth::User()->id) == 'employee' || getUserRoleFromUserTable(Auth::User()->id) == 'branch_admin')
                      @if (Gate::allows('jobcard_view') && Gate::allows('jobcard_edit') && Gate::allows('jobcard_add'))
                      @can('jobcard_view')
                      <?php
                      $view_data = getInvoiceStatus($servicess->job_no);

                      if ($view_data == "No") {
                        if ($servicess->done_status == '1') {
                      ?>
                          <!-- <li><a href="{{ url('invoice/add/' . $servicess->id) }}" class="dropdown-item"><img src="{{ URL::asset('public/img/list/create.png') }}" class="me-3"> {{ trans('message.Create Invoice') }}</a></li> -->
                          @can('invoice_add')
                            <button type="button" data-bs-toggle="modal" data-bs-target="#myModal1" class="dropdown-item invoice" serviceid="{{ $servicess->id }}" job_no="{{ $servicess->job_no }}" url="{!! url('/jobcard/invoice') !!}"><img src="{{ URL::asset('public/img/list/create.png') }}" class="me-3">{{ trans('message.Create Invoice') }} </button>
                          @endcan
                        <?php
                        } elseif ($servicess->done_status != '1') {
                        ?>
                          <!-- <li><a class="dropdown-item"><img src="{{ URL::asset('public/img/list/create.png') }}" class="me-3"> {{ trans('message.Create Invoice') }} </a></li> -->
                        <?php
                        }
                      } else {
                        ?>
                        @can('invoice_view')
                        <li><button type="button" data-bs-toggle="modal" data-bs-target="#myModal-job" serviceid="{{ $servicess->id }}" job_no="{{ $servicess->job_no }}" url="{!! url('/jobcard/modalview') !!}" class="dropdown-item save"><img src="{{ URL::asset('public/img/list/Vector.png') }}" class="me-3"> {{ trans('message.View Invoice') }} </button></li>
                        @endcan
                      <?php
                      }
                      ?>
                      @endcan
 
                      <?php $jobcard = getJobcardStatus($servicess->job_no);
                      $view_data = getInvoiceStatus($servicess->job_no);
                      $edit_service = getEditService();
                      ?>

                      @can('jobcard_edit')
                      @if ($jobcard == 1)
                      @if($edit_service == 1)
                      <li><a href="{{ url('service/list/edit/' . $servicess->id) }}" class="dropdown-item"><img src="{{ URL::asset('public/img/list/Edit.png') }}" class="me-3"> {{ trans('message.Edit') }}</a></li>
                      @endif
                      <li><a href="{{ url('jobcard/complete/' . $servicess->id) }}" class="dropdown-item"><img src="{{ URL::asset('public/img/list/jobprocess.png') }}" class="me-3">{{ trans('message.Job Completed') }}</a></li>
                      @elseif($view_data == 'Yes') 
                      @if($edit_service == 1)
                      <li><a href="{{ url('service/list/edit/' . $servicess->id) }}" class="dropdown-item"><img src="{{ URL::asset('public/img/list/Edit.png') }}" class="me-3"> {{ trans('message.Edit') }}</a></li>
                      @endif
                      <li><a href="{{ url('jobcard/complete/' . $servicess->id) }}" class="dropdown-item"><img src="{{ URL::asset('public/img/list/jobprocess.png') }}" class="me-3">{{ trans('message.Job Completed') }}</a></li>
                      @else
                      <li><a href="{{ url('service/list/edit/' . $servicess->id) }}" class="dropdown-item"><img src="{{ URL::asset('public/img/list/Edit.png') }}" class="me-3"> {{ trans('message.Edit') }}</a></li>
                      <li><a href="{{ url('jobcard/list/' . $servicess->id) }}" class="dropdown-item"><img src="{{ URL::asset('public/img/list/jobprocess.png') }}" class="me-3">{{ trans('message.Process Job') }}</a></li>
                      @endif

                      @if (getAlreadypasss($servicess->job_no) == 0 && $view_data == 'Yes')
                      @can('gatepass_add')
                      <li><a href="{!! url('/jobcard/gatepass/' . $servicess->id) !!}" class="dropdown-item"><img src="{{ URL::asset('public/img/list/gatepass.png') }}" class="me-3">{{ trans('message.Gate Pass') }}</a></li>
                      @endcan
                      @elseif($view_data == 'No')
                      <!-- <a class="dropdown-item"><img src="{{ URL::asset('public/img/list/gatepass.png') }}" class="me-3"> {{ trans('message.Gate Pass') }}</a> -->
                      @elseif(getAlreadypasss($servicess->job_no) == 1)
                      @can('gatepass_view')
                      <li><button type="button" data-bs-toggle="modal" data-bs-target="#myModal-gate" serviceid="" class="dropdown-item getgetpass" getid="{{ $servicess->job_no }}"><img src="{{ URL::asset('public/img/list/receipt.png') }}" class="me-3">{{ trans('message.Gate Receipt') }}</li>
                      @endcan
                      @endif
                      @endcan
                      @elseif(getUserRoleFromUserTable(Auth::User()->id) == 'supportstaff' || getUserRoleFromUserTable(Auth::User()->id) == 'accountant' || getUserRoleFromUserTable(Auth::User()->id) == 'employee')
                      @can('jobcard_view')
                      <?php
                      $view_data = getInvoiceStatus($servicess->job_no);

                      if ($view_data == "Yes") {
                      ?>
                      @can('invoice_view')
                        <li><button type="button" data-bs-toggle="modal" data-bs-target="#myModal-job" serviceid="{{ $servicess->id }}" job_no="{{ $servicess->job_no }}" url="{!! url('/jobcard/modalview') !!}" class="dropdown-item save"><img src="{{ URL::asset('public/img/list/Vector.png') }}" class="me-3"> {{ trans('message.View Invoice') }} </button></li>
                      @endcan
                      <?php
                      } else {
                      ?>
                      @can('invoice_view')
                        <li><button type="button" data-bs-toggle="modal" data-bs-target="#myModal-job" serviceid="{{ $servicess->id }}" job_no="{{ $servicess->job_no }}" url="{!! url('/jobcard/modalview') !!}" class="dropdown-item save" disabled><img src="{{ URL::asset('public/img/list/Vector.png') }}" class="me-3"> {{ trans('message.View Invoice') }} </button></li>
                      @endcan
                      <?php
                      }
                      ?>
                      @endcan

                      <?php
                      $jobcard = getJobcardStatus($servicess->job_no);
                      $view_data = getInvoiceStatus($servicess->job_no);
                      ?>

                      @can('jobcard_edit')
                      @if ($jobcard == 1)
                      <li><a href="{{ url('jobcard/list/' . $servicess->id) }}" class="dropdown-item"><img src="{{ URL::asset('public/img/list/jobprocess.png') }}" class="me-3">{{ trans('message.Process Job') }}</a></li>
                      @elseif($view_data == 'Yes')
                      <li><a href="{{ url('jobcard/list/' . $servicess->id) }}" class="dropdown-item"><img src="{{ URL::asset('public/img/list/jobprocess.png') }}" class="me-3">{{ trans('message.Process Job') }}</a></li>
                      @else
                      <li><a href="{{ url('jobcard/list/' . $servicess->id) }}" class="dropdown-item"><img src="{{ URL::asset('public/img/list/jobprocess.png') }}" class="me-3">{{ trans('message.Process Job') }}</a></li>
                      @endif

                      @if (getAlreadypasss($servicess->job_no) == 0 && $view_data == 'Yes')
                      @can('gatepass_add')
                      <li><a href="{!! url('/jobcard/gatepass/' . $servicess->id) !!}" class="dropdown-item"><img src="{{ URL::asset('public/img/list/gatepass.png') }}" class="me-3">{{ trans('message.Gate Pass') }}</a></li>
                      @endcan
                      @elseif($view_data == 'No')
                      @can('gatepass_add')
                      <li><a href="{!! url('/jobcard/gatepass/' . $servicess->id) !!}" class="dropdown-item"><img src="{{ URL::asset('public/img/list/gatepass.png') }}" class="me-3">{{ trans('message.Gate Pass') }}</a></li>
                      @endcan
                      @elseif(getAlreadypasss($servicess->job_no) == 1)
                      @can('gatepass_view')
                      <li><button type="button" data-bs-toggle="modal" data-bs-target="#myModal-gate" serviceid="" class="dropdown-item getgetpass" getid="{{ $servicess->job_no }}"><img src="{{ URL::asset('public/img/list/receipt.png') }}" class="me-3">{{ trans('message.Gate Receipt') }}</button></li>
                      @endcan
                      @endif
                      @endcan
                      @endif
                      @elseif(getUserRoleFromUserTable(Auth::User()->id) == 'Customer')
                      @if (Gate::allows('jobcard_view') && Gate::allows('jobcard_add') && Gate::allows('jobcard_edit'))
                      @can('jobcard_view')
                      <?php
                      $view_data = getInvoiceStatus($servicess->job_no);

                      if ($view_data == "No") {
                        if ($servicess->done_status == '1') {
                      ?>
                        @can('invoice_add')
                          <button type="button" data-bs-toggle="modal" data-bs-target="#myModal1" class="dropdown-item invoice" serviceid="{{ $servicess->id }}" job_no="{{ $servicess->job_no }}" url="{!! url('/jobcard/invoice') !!}"><img src="{{ URL::asset('public/img/list/create.png') }}" class="me-3">{{ trans('message.Create Invoice') }} </button>
                        @endcan
                        <?php
                        } elseif ($servicess->done_status != '1') {
                        ?>
                        @can('invoice_add')
                          <button type="button" data-bs-toggle="modal" data-bs-target="#myModal1" class="dropdown-item invoice" serviceid="{{ $servicess->id }}" job_no="{{ $servicess->job_no }}" url="{!! url('/jobcard/invoice') !!}"><img src="{{ URL::asset('public/img/list/create.png') }}" class="me-3">{{ trans('message.Create Invoice') }} </button>
                        @endcan
                        <?php
                        }
                      } else {
                        ?>
                        @can('invoice_view')
                        <li><button type="button" data-bs-toggle="modal" data-bs-target="#myModal-job" serviceid="{{ $servicess->id }}" job_no="{{ $servicess->job_no }}" url="{!! url('/jobcard/modalview') !!}" class="dropdown-item save"><img src="{{ URL::asset('public/img/list/Vector.png') }}" class="me-3"> {{ trans('message.View Invoice') }} </button></li>
                        @endcan
                      <?php
                      }
                      ?>
                      @endcan

                      <?php $jobcard = getJobcardStatus($servicess->job_no);
                      $view_data = getInvoiceStatus($servicess->job_no);
                      ?>

                      @can('jobcard_edit')
                      @if ($jobcard == 1)
                      <li><a href="{{ url('jobcard/list/' . $servicess->id) }}" class="dropdown-item"><img src="{{ URL::asset('public/img/list/jobprocess.png') }}" class="me-3">{{ trans('message.Process Job') }}</a></li>
                      @elseif($view_data == 'Yes')
                      <li><a href="{{ url('jobcard/list/' . $servicess->id) }}" class="dropdown-item"><img src="{{ URL::asset('public/img/list/jobprocess.png') }}" class="me-3">{{ trans('message.Process Job') }}</a></li>
                      @else
                      <li><a href="{{ url('jobcard/list/' . $servicess->id) }}" class="dropdown-item"><img src="{{ URL::asset('public/img/list/jobprocess.png') }}" class="me-3">{{ trans('message.Process Job') }}</a></li>
                      @endif

                      @if (getAlreadypasss($servicess->job_no) == 0 && $view_data == 'Yes')
                      @can('gatepass_add')
                      <li><a href="{!! url('/jobcard/gatepass/' . $servicess->id) !!}" class="dropdown-item"><img src="{{ URL::asset('public/img/list/gatepass.png') }}" class="me-3">{{ trans('message.Gate Pass') }}</a></li>
                      @endcan
                      @elseif($view_data == 'No')
                      @can('gatepass_add')
                      <li><a href="{!! url('/jobcard/gatepass/' . $servicess->id) !!}" class="dropdown-item"><img src="{{ URL::asset('public/img/list/gatepass.png') }}" class="me-3">{{ trans('message.Gate Pass') }}</a></li>
                      @endcan
                      @elseif(getAlreadypasss($servicess->job_no) == 1)
                      @can('gatepass_view')
                      <li><button type="button" data-bs-toggle="modal" data-bs-target="#myModal-gate" serviceid="" class="dropdown-item getgetpass" getid="{{ $servicess->job_no }}"><img src="{{ URL::asset('public/img/list/receipt.png') }}" class="me-3">{{ trans('message.Gate Receipt') }}</button></li>
                      @endcan
                      @endif
                      @endcan
                      @else
                      @can('jobcard_view')
                      <?php

                      $view_data = getInvoiceStatus($servicess->job_no);

                      if ($view_data == "Yes") {
                      ?>
                      @can('invoice_view')
                        <li><button type="button" data-bs-toggle="modal" data-bs-target="#myModal-job" serviceid="{{ $servicess->id }}" job_no="{{ $servicess->job_no }}" url="{!! url('/jobcard/modalview') !!}" class="dropdown-item save"><img src="{{ URL::asset('public/img/list/Vector.png') }}" class="me-3"> {{ trans('message.View Invoice') }} </button></li>
                      @endcan
                      <?php
                      } else {
                      ?>
                      @can('invoice_view')
                        <li><button type="button" data-bs-toggle="modal" data-bs-target="#myModal-job" serviceid="{{ $servicess->id }}" job_no="{{ $servicess->job_no }}" url="{!! url('/jobcard/modalview') !!}" class="dropdown-item save" disabled><img src="{{ URL::asset('public/img/list/Vector.png') }}" class="me-3"> {{ trans('message.View Invoice') }} </button></li>
                      @endcan
                      <?php
                      }
                      ?>
                      @endcan

                      <?php $jobcard = getJobcardStatus($servicess->job_no);
                      $view_data = getInvoiceStatus($servicess->job_no);
                      ?>

                      @can('jobcard_edit')
                      @if ($jobcard == 1)
                      <li><a href="{{ url('jobcard/list/' . $servicess->id) }}" class="dropdown-item"><img src="{{ URL::asset('public/img/list/jobprocess.png') }}" class="me-3">{{ trans('message.Process Job') }}</a></li>
                      @elseif($view_data == 'Yes')
                      <li><a href="{{ url('jobcard/list/' . $servicess->id) }}" class="dropdown-item"><img src="{{ URL::asset('public/img/list/jobprocess.png') }}" class="me-3">{{ trans('message.Process Job') }}</a></li>
                      @else
                      <li><a href="{{ url('jobcard/list/' . $servicess->id) }}" class="dropdown-item"><img src="{{ URL::asset('public/img/list/jobprocess.png') }}" class="me-3">{{ trans('message.Process Job') }}</a></li>
                      @endif

                      @if (getAlreadypasss($servicess->job_no) == 0 && $view_data == 'Yes')
                      @can('gatepass_add')
                      <li><a href="{!! url('/jobcard/gatepass/' . $servicess->id) !!}" class="dropdown-item"><img src="{{ URL::asset('public/img/list/gatepass.png') }}" class="me-3">{{ trans('message.Gate Pass') }}</a></li>
                      @endcan
                      @elseif($view_data == 'No')
                      @can('gatepass_add')
                      <li><a href="{!! url('/jobcard/gatepass/' . $servicess->id) !!}" class="dropdown-item"><img src="{{ URL::asset('public/img/list/gatepass.png') }}" class="me-3">{{ trans('message.Gate Pass') }}</a></li>
                      @endcan
                      @elseif(getAlreadypasss($servicess->job_no) == 1)
                      @can('gatepass_view')
                      <li><button type="button" data-bs-toggle="modal" data-bs-target="#myModal-gate" serviceid="" class="dropdown-item getgetpass" getid="{{ $servicess->job_no }}"><img src="{{ URL::asset('public/img/list/receipt.png') }}" class="me-3">{{ trans('message.Gate Receipt') }}</button></li>
                      @endcan
                      @endif
                      @endcan
                      @endif
                      @endif
                    </ul>
                  </div>
                </td>
                @endcanany
              </tr>
              <?php $i++; ?>
              @endforeach
              @endif
            </tbody>
          </table>
          <!-- <button id="select-all-btn" class="btn select_all"><input type="checkbox" name="chk"> {{ trans('message.Select All') }} </button>
          <button id="delete-selected-btn" class="btn btn-danger text-white border-0" data-url="{!! url('/supplier/list/delete') !!}"><i class="fa fa-trash" aria-hidden="true"></i></button> -->
        </div>
      </div>
    <!-- /page content -->

@endsection
