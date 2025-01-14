@extends('layouts.app')
@section('content')
<style>
  @media screen and (max-width:540px) {
    div#vehicle_info {
      margin-top: -169px;
    }

    span.titleup {
      /* margin-left: -10px; */
    }
  }
</style>
<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="nav_menu">
        <nav>
          <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><span class="titleup">{{ trans('message.Vehicles') }}</a>
              @can('vehicle_add')
              <a href="{!! url('/vehicle/add') !!}" id="" class="addbotton">
                <img src="{{ URL::asset('public/img/icons/plus Button.png') }}">
              </a>
            </span>
            @endcan
          </div>

          @include('dashboard.profile')
        </nav>
      </div>
    </div>
    @include('success_message.message')
    <div class="row">
    @if(!empty($vehicals) && count($vehicals) > 0)
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel mb-0">
          <table id="supplier" class="table jambo_table" style="width:100%">
            <thead>
              <tr>
                @can('vehicle_delete')
                <th> </th>
                @endcan
                <th>{{ trans('message.Image') }}</th>
                <th>{{ trans('message.Type') }}</th>
                <th>{{ trans('message.Model Name') }}</th>
                <th>{{ trans('message.Number Plate') }}</th>
                <th>{{ trans('message.Customer Name') }}</th>
                <!-- <th>{{ trans('message.Price') }} (<?php echo getCurrencySymbols(); ?>)</th> -->
                <th>{{ trans('message.Date Of Manufacturing') }}</th>
                <th>{{ trans('message.Last Service Date') }}</th>
                <th>{{ trans('message.Upcoming Service Date') }}</th>
                <th>{{ trans('message.Engine No') }}</th>
                <th>{{ trans('message.Action') }}</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1; ?>
              @if (!empty($vehicals))
              @foreach ($vehicals as $vehicals)
              <tr data-user-id="{{ $vehicals->id }}">
              @can('vehicle_delete')
                <td>
                  <label class="container checkbox">
                    <input type="checkbox" name="chk">
                    <span class="checkmark"></span>
                  </label>
                </td>
              @endcan
                <?php $vehicleimage = getVehicleImage($vehicals->id); ?>
                <td><a href="{!! url('/vehicle/list/view/' . $vehicals->id) !!}"><img src="{{ URL::asset('public/vehicle/' . $vehicleimage) }}" width="52px" height="52px" class="datatable_img"></a></td>
                <td><a href="{!! url('/vehicle/list/view/' . $vehicals->id) !!}">{{ getVehicleType($vehicals->vehicletype_id) }}</a></td>
                <td>{{ $vehicals->modelname }}</td>
                <td>{{ $vehicals->number_plate ?? trans('message.Not Added') }} 
                </td>
                @can('customer_view')
                <td>
                  <a href="{!! url('/customer/list/' . $vehicals->customer_id) !!}"> {{ getCustomerName($vehicals->customer_id) ?? trans('message.Not Added')}} 
                  </a>
                </td>
                @else
                <td>
                  {{ getCustomerName($vehicals->customer_id) ?? trans('message.Not Added')}} 
                </td>
                @endcan 
                <td>
                  @if (!empty($vehicals->dom))
                  {{ date(getDateFormat(), strtotime($vehicals->dom)) }}
                  @else
                  {{ trans('message.Not Added') }}
                  @endif
                </td>
                <td>{{ $vehicals->lastServiceDate ? $vehicals->lastServiceDate->format('Y-m-d') : 'No service records' }} </td>
                <td>{{ $vehicals->upcomingServiceDate ? $vehicals->upcomingServiceDate->format('Y-m-d') : 'No service records' }} </td>
                <td>{{ $vehicals->engineno ?? trans('message.Not Added') }} </td>
                
                
                <td>
                  <div class="dropdown_toggle">
                    <img src="{{ URL::asset('public/img/list/dots.png') }}" class="btn dropdown-toggle border-0" type="button" id="dropdownMenuButtonaction" data-bs-toggle="dropdown" aria-expanded="false">

                    <ul class="dropdown-menu heder-dropdown-menu action_dropdown shadow py-2" aria-labelledby="dropdownMenuButtonaction">
                      @can('vehicle_view')
                      <li><a class="dropdown-item" href="{!! url('/vehicle/list/view/' . $vehicals->id) !!}"><img src="{{ URL::asset('public/img/list/Vector.png') }}" class="me-3">{{ trans('message.View') }}</a></li>
                      @endcan

                      @can('vehicle_edit')
                      <li><a class="dropdown-item" href="{!! url('/vehicle/list/edit/' . $vehicals->id) !!}"><img src="{{ URL::asset('public/img/list/Edit.png') }}" class="me-3"> {{ trans('message.Edit') }}</a></li>
                      @endcan

                      @can('vehicle_delete')
                      <div class="dropdown-divider m-0"></div>
                      <li><a class="dropdown-item sa-warning" url="{!! url('/vehicle/list/delete/' . $vehicals->id) !!}" style="color:#FD726A"><img src="{{ URL::asset('public/img/list/Delete.png') }}" class="me-3">{{ trans('message.Delete') }}</a></li>
                      @endcan
                    </ul>
                  </div>
                </td>
              </tr>
              <?php $i++; ?>
              @endforeach
              @endif
            <tbody>
            </tbody>
          </table>
          @can('vehicle_delete')
          <button id="select-all-btn" class="btn select_all"><input type="checkbox" name="selectAll"> {{ trans('message.Select All') }}</button>
          <button id="delete-selected-btn" class="btn btn-danger text-white border-0" data-url="{!! url('/vehicle/list/delete/') !!}"><i class="fa fa-trash" aria-hidden="true"></i></button>
          @endcan
        </div>
      </div>
    @else
      <p class="d-flex justify-content-center mt-5 pt-5"><img src="{{ URL::asset('public/img/dashboard/No-Data.png') }}" width="300px"></p>
    @endif
    </div>
  </div>
</div>
<!-- /page content -->


<!-- Scripts starting -->
<script src="{{ URL::asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<!-- language change in user selected -->
<script>
  $(document).ready(function() {

    var search = "{{ trans('message.Search...') }}";
    var info = "{{ trans('message.Showing page _PAGE_ - _PAGES_') }}";
    var zeroRecords = "{{ trans('message.No Data Found') }}";
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


    /*delete vehical*/
    $('body').on('click', '.sa-warning', function() {

      var url = $(this).attr('url');

      var msg1 = "{{ trans('message.Are You Sure?') }}";
      var msg2 = "{{ trans('message.You will not be able to recover this data afterwards!') }}";
      var msg3 = "{{ trans('message.Cancel') }}";
      var msg4 = "{{ trans('message.Yes, delete!') }}";

      swal({
        title: msg1,
        text: msg2,
        icon: 'warning',
        cancelButtonColor: '#C1C1C1',
        buttons: [msg3, msg4],
        dangerMode: true,
      }).then((willDelete) => {
        if (willDelete) {
          window.location.href = url;
        }
      });
    });
  });
</script>

@endsection