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
                        {{-- <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
                            <span class="titleup">{{ trans('message.Stock') }} </span>
                        </div> --}}
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
                            <span class="titleup">{{ trans('message.Stock') }}
                                @can('stock_add')
                                    <a href="{{ route('stock.add') }}" id="" class="addbotton">
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
                @if (!empty($product_stocks) && count($product_stocks) > 0)
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <table id="supplier" class="table jambo_table" style="width:100%">
                                <thead>
                                    <tr>  
                                        <th>Spare Part</th>
                                        <th>Category</th>
                                        <th>Stock</th>
                                        <th>Price</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($product_stocks as $product_stock)
                                        <tr data-user-id="{{ $product_stock->id }}">  
                                            <td>{{ $product_stock->label->title }}</td>
                                            <td>
                                               @switch($product_stock->category_id)
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
                                                @endswitch </td> 
                                            <td>{{ $product_stock->stock }}</td> 
                                            <td>{{ number_format($product_stock->price,2) ?? 0 }}</td> 
                                             
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
    </script>

@endsection
