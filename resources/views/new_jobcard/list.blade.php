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
                            <span class="titleup">{{ trans('message.JobCard') }}
                                @can('jobcard_add')
                                    <a href="{{ route('newjobcard.add') }}" id="" class="addbotton">
                                        <img src="{{ URL::asset('public/img/icons/plus Button.png') }}">
                                    </a>
                                @endcan
                            </span>
                        </div>
                        @include('dashboard.profile')
                    </nav>
                </div>
            </div>
            @include('success_message.message')
            <div class="row">
                @if (!empty($jobcards) && count($jobcards) > 0)
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <table id="supplier" class="table jambo_table" style="width:100%">
                                <thead>
                                    <tr>
                                        @can('jobcard_delete')
                                            <th> </th>
                                        @endcan
                                        <th>Entry Date</th>
                                        <th>Jobcard Number</th>
                                        <th>Vehical Number</th>
                                        <th>Customer Name</th>
                                        <th>Vehical</th>
                                        <th>Bill Amount</th>
                                        <th>Balance</th>
                                        @canany(['jobcard_edit', 'jobcard_delete'])
                                            <th>{{ trans('message.Action') }}</th>
                                        @endcanany
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($jobcards as $jobcard)
                                        <tr data-user-id="{{ $jobcard->id }}">
                                            @can('jobcard_delete')
                                                <td>
                                                    <label class="container checkbox">
                                                        <input type="checkbox" name="chk">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </td>
                                            @endcan

                                            <td><a href="{{ route('jobcard.edit',$jobcard->id) }}">{{ $jobcard->entry_date  ?? '-'}}</a></td>
                                            <td><a href="{{ route('jobcard.edit',$jobcard->id) }}">{{ $jobcard->jobcard_number ?? '-' }}</a></td>
                                            <td><a href="{{ route('jobcard.edit',$jobcard->id) }}">{{ $jobcard->vehical_number  ?? '-' }}</a></td>
                                            <td><a href="{{ route('jobcard.edit',$jobcard->id) }}">{{ $jobcard->customer_name  ?? '-' }}</a></td>
                                            <td><a href="{{ route('jobcard.edit',$jobcard->id) }}">{{ $jobcard->vehical  ?? '-' }}</a></td>
                                            <td><a href="{{ route('jobcard.edit',$jobcard->id) }}">{{ $jobcard->amount  ?? '-' }}</a></td>
                                            <td><a href="{{ route('jobcard.edit',$jobcard->id) }}">{{ $jobcard->stock  ?? '-' }}</a></td>
                                            <td><a href="{{ route('jobcard.edit',$jobcard->id) }}">{{ $jobcard->balance_amount  ?? '-' }}</a></td>

                                            @canany(['jobcard_edit', 'jobcard_delete'])
                                                <td>
                                                    <div class="dropdown_toggle">
                                                        <img src="{{ URL::asset('public/img/list/dots.png') }}"
                                                            class="btn dropdown-toggle border-0" type="button"
                                                            id="dropdownMenuButtonaction" data-bs-toggle="dropdown"
                                                            aria-expanded="false">

                                                        <ul class="dropdown-menu heder-dropdown-menu action_dropdown shadow py-2"
                                                            aria-labelledby="dropdownMenuButtonaction">
                                                            @can('jobcard_edit')
                                                                <li><a class="dropdown-item" href="{{ route('jobcard.edit',$jobcard->id) }}">
                                                                <img src="{{ URL::asset('public/img/list/Edit.png') }}" class="me-3"> {{ trans('message.Edit') }}</a></li>
                                                            @endcan

                                                            @can('jobcard_delete')
                                                                <div class="dropdown-divider m-0"></div>
                                                                <li>
                                                                    <a class="dropdown-item sa-warning"
                                                                        url="{{ route('jobcard.destory', $jobcard->id) }}"
                                                                        style="color:#FD726A">
                                                                        <img src="{{ URL::asset('public/img/list/Delete.png') }}"
                                                                            class="me-3">{{ trans('message.Delete') }}</a>
                                                                </li>
                                                            @endcan
                                                        </ul>
                                                    </div>
                                                </td>
                                            @endcanany
                                        </tr>
                                        <?php $i++; ?>
                                    @endforeach
                                </tbody>
                            </table>
                            @can('jobcard_delete')
                                <button id="select-all-btn" class="btn select_all"><input type="checkbox" name="selectAll">
                                    {{ trans('message.Select All') }}</button>
                                <button id="delete-selected-btn" class="btn btn-danger text-white border-0"
                                    data-url="{{ route('jobcard.destroyMultiple') }}"><i class="fa fa-trash" aria-hidden="true"></i></button>
                            @endcan
                        </div>
                    </div>
                @else
                    <p class="d-flex justify-content-center mt-5 pt-5"><img
                            src="{{ URL::asset('public/img/dashboard/No-Data.png') }}" width="300px"></p>
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


            /*delete vehicalbrand*/
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