@extends('layouts.app')
@section('content')
    <style>
        @media screen and (max-width:540px) {
            div#order_info {
                margin-top: -169px;
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
                            <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><a
                                href="{{ route('order.list') }}" id=""><i class="">
                                    <img src="{{ URL::asset('public/supplier/Back Arrow.png') }}"
                                        class="back-arrow"></i><span class="titleup">
                                    {{ trans('message.Order Item') }}</span></a>
                        </div>
                        @include('dashboard.profile')
                    </nav>
                </div>
            </div>
            @include('success_message.message')
            <div class="row">
                @if (!empty($order_items) && count($order_items) > 0)
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <table id="supplier" class="table jambo_table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Spare Part</th>
                                        <th>Category</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total Amount</th>
                                        @canany(['order_edit', 'order_delete'])
                                            <th>{{ trans('message.Action') }}</th>
                                        @endcanany
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($order_items as $order_item)
                                        <tr data-user-id="{{ $order_item->id }}">
                                            <td>{{ @$order_item->SpareParts->name }}</td>
                                            <td>
                                                @switch($order_item->category)
                                                    @case(1)
                                                        Accessory
                                                    @break

                                                    @case(2)
                                                        Parts
                                                    @break

                                                    @case(3)
                                                        Tools
                                                    @break

                                                    @case(4)
                                                        Lubricants
                                                    @break

                                                    @default
                                                @endswitch
                                            </td>
                                            <td>{{ $order_item->quantity }}</td>
                                            <td>{{ $order_item->price }}</td>
                                            <td>{{ $order_item->total_amount }}</td>
                                            <td>
                                                @if ($order_item->status == 0)
                                                    <button type="button"
                                                        onclick="accept_order(this,'<?= $order_item->id ?>',1)"
                                                        class="btn btn-success btn-sm rounded mx-2">Accept</button>
                                                    <button type="button"
                                                        onclick="accept_order(this,'<?= $order_item->id ?>',2)"
                                                        class="btn btn-danger btn-sm border-0 text-white rounded mx-2">Decline</button>
                                                @elseif($order_item->status == 1)
                                                    <p class="badge bg-success">Accepted</p>
                                                @else
                                                    <p class="badge bg-danger">Declined</p>
                                                @endif
                                            </td>
                                        </tr>
                                        <?php $i++; ?>
                                    @endforeach
                                </tbody>
                            </table>

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


        function accept_order(e, id, status) {
            var url = "{{ route('order.accept') }}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    id: id,
                    status: status,
                },
                success: function(res) {
                    location.reload();
                },
            });
        }
    </script>

@endsection
