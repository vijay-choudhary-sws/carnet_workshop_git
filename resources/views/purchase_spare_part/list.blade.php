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
                            @if (getActiveCustomer(Auth::user()->id) == 'yes' ||
                                    getActiveEmployee(Auth::user()->id) == 'yes' ||
                                    getBranchadminsactive(Auth::user()->id) == 'yes')
                                <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><span
                                    class="titleup">{{ trans('message.Purchase Spare Parts') }}
                                    @can('salespart_add')
                                        <a href="{{ route('purchase_spare_part.add') }}" id="" class="addbotton">
                                            <img src="{{ URL::asset('public/img/icons/plus Button.png') }}" class="mb-2">
                                        </a>
                                    @endcan
                                </span>
                            @else
                                <button type="button" id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></button><span
                                    class="titleup">{{ trans('message.Purchase') }}
                                </span>
                            @endif
                        </div>
                        @include('dashboard.profile')
                    </nav>
                </div>
            </div>
            @include('success_message.message')
            <div class="row">
                @if (!empty($orders) && count($orders) > 0)
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <table id="supplier" class="table jambo_table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Order No.</th>
                                        <th>Order Date</th>
                                        <th>Total Amount</th>
                                        <th>Total Quantity</th>
                                        <th>Total Item</th>
                                        <th>Status</th>
                                        @canany(['order_edit', 'order_delete'])
                                            <th>{{ trans('message.Action') }}</th>
                                        @endcanany
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($orders as $order)
                                        <tr data-user-id="{{ $order->id }}">
                                            <td><a href="{{ route('order.view', $order->id) }}">{{ $order->id }}</a>
                                            <td><a href="{{ route('order.view', $order->id) }}">{{ $order->order_date }}</a>
                                            </td>
                                            <td><a
                                                    href="{{ route('order.view', $order->id) }}">{{ $order->total_amount }}</a>
                                            </td>
                                            <td><a
                                                    href="{{ route('order.view', $order->id) }}">{{ $order->total_quantity }}</a>
                                            </td>
                                            <td><a
                                                    href="{{ route('order.view', $order->id) }}">{{ $order->total_item }}</a>
                                            </td>
                                            <td>
                                                @switch($order->status)
                                                    @case(1)
                                                      <p class="badge bg-success">Completed</p> 
                                                    @break

                                                    @case(0)
                                                      <p class="badge bg-danger">Pending</p>  
                                                    @break

                                                    @case(2)
                                                      <p class="badge bg-danger">Decline</p>   
                                                        
                                                    @break

                                                    @case(3)
                                                      <p class="badge bg-primary">Partial Pending</p>   
                                                    @break

                                                    @case(4).
                                                      <p class="badge bg-primary">Partial Complete</p>   
                                                    @break

                                                    @default
                                                @endswitch 
                                                 
                                            </td>
                                            <td>
                                                <a href="{{ route('purchase_spare_part.view', $order->id) }}"
                                                    class="btn btn-success">View Item</a>
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

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

        $(document).ready(function() {

            $('.select2').select2();

            $('#purchase-spare-part-Form').on('submit', function(e) {
                e.preventDefault();

                let form = $(this);
                let url = form.attr('action');
                let formData = form.serialize();

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    success: function(response) {

                        toastr.clear();

                        if (response.status === 'success') {
                            toastr.success(response.message, 'Success');
                            window.location.replace('{{ route('purchase_spare_part.list') }}');
                        }
                    },
                    error: function(xhr) {

                        toastr.clear();

                        let errorMessages = '';
                        let errors = xhr.responseJSON.errors;
                        for (let field in errors) {
                            if (errors.hasOwnProperty(field)) {
                                let messages = errors[field];
                                messages.forEach(function(message) {
                                    errorMessages += message +
                                        '<br>';
                                });
                            }
                        }
                        toastr.error(errorMessages, 'Validation Errors');
                    }
                });
            });
        });

        function getItem(e) {
            let cateId = $(e).val();
            if (!cateId) {
                toastr.info("Please select a valid category.", 'INFO')
                return;
            }
            let row = $(e).parents('tr').attr('data-row');
            if (!row) {
                console.error("Row attribute is missing in the parent <tr>.");
                return;
            }

            let itemId = [];
            $('select[name="item[]"]').each(function() {
                let value = parseFloat($(this).val());
                if (!isNaN(value)) {
                    itemId.push(value);
                }
            });

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('purchase_spare_part.getItem') }}",
                method: "post",
                data: {
                    cat_id: cateId,
                    item_id: itemId
                },
                success: function(res) {
                    if (res.status == 1) {
                        $('select[name="item[]"]').eq(row - 1).html(res.html);
                    } else {
                        toastr.info(res.msg, "INFO");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error: " + error);
                    console.error("Status: " + status);
                    console.error("Response: " + xhr.responseText);
                }
            });
        }

        function getStock(e) {
            let stockId = $(e).val();
            if (!stockId) {
                toastr.info("Please select a valid item.", "INFO");
                return;
            }
            let row = $(e).parents('tr').attr('data-row');
            if (!row) {
                console.error("Row attribute is missing in the parent <tr>.");
                return;
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('purchase_spare_part.getAmount') }}",
                method: "post",
                data: {
                    stock_id: stockId,
                    row_id: row,
                },
                success: function(res) {
                    if (res.status == 1) {
                        $('input[name="quantity[]"]').eq(row - 1).attr('max', res.stock).val('1');
                        $('input[name="price[]"]').eq(row - 1).val(res.price);
                        $('input[name="total_price[]"]').eq(row - 1).val(res.price);
                        totalAmount();
                    } else {
                        toastr.info(res.msg, "INFO");
                    }
                },
                error: function(xhr, status, error) {
                    {{-- console.error("Error: " + error);
                    console.error("Status: " + status);
                    console.error("Response: " + xhr.responseText); --}}
                }
            });
        }

        function checkMax(e) { 
            let value = parseInt($(e).val(), 10);
            let maxValue = parseInt($(e).attr('max'), 10);
            if (value < 0) {
                $(e).val(0);
            } else if (value > maxValue) {
                $(e).val(maxValue);
            }
            getPrice(e);
        }

        function getPrice(e) {

            let qty = parseInt($(e).val(), 10);

            if (isNaN(qty) || qty < 0) {
                toastr.info("Please enter a valid quantity.", "INFO");
                return;
            }

            let row = $(e).parents('tr').attr('data-row');
            if (!row) {
                console.error("Row attribute is missing in the parent <tr>.");
                return;
            }

            let price = parseFloat($('input[name="price[]"]').eq(row - 1).val());

            if (isNaN(price) || price < 0) {
                toastr.info("Price is invalid.", "INFO");
                return;
            }

            let totalPrice = price * qty;
            name = "total_price[]"
            $('input[name="total_price[]"]').eq(row - 1).val(totalPrice);

            totalAmount();
        }

        function totalAmount() {
            let totalAmount = 0;
            $('input[name="total_price[]"]').each(function() {
                let value = parseFloat($(this).val());
                if (!isNaN(value)) {
                    totalAmount += value;
                }
            });

            $('#totalAmount').val(totalAmount);
        }

        function addRowmodel() {

            let isValid = true;

            $('select[name="category[]"]').each(function() {
                let value = $(this).val();
                if (!value) {
                    toastr.info('Category cannot be null.', "INFO");
                    isValid = false;
                    return false;
                }
            });

            $('select[name="item[]"]').each(function() {
                let value = $(this).val();
                if (!value) {
                    toastr.info('Item cannot be null.', "INFO");
                    isValid = false;
                    return false;
                }
            });

            $('select[name="quantity[]"]').each(function() {
                let value = $(this).val();
                if (!value) {
                    toastr.info('Qty cannot be null.', "INFO");
                    isValid = false;
                    return false;
                }
            });

            if (!isValid) {
                return;
            }

            let row = $('tbody tr').length + 1;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('purchase_spare_part.addRowmodel') }}",
                method: "post",
                data: {
                    row: row
                },
                success: function(res) {
                    if (res.status == 1) {
                        $('table tbody').append(res.html);
                        $('.select2').select2();
                    } else {
                        toastr.info(res.msg, "INFO");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error: " + error);
                    console.error("Status: " + status);
                    console.error("Response: " + xhr.responseText);
                }
            });
        }

        function deleteRow(e) {
            $(e).parents('tr').remove();
            let $i = 1;
            $('tbody tr').each(function() {
                $(this).attr('data-row', $i++);
            });
            totalAmount();
        }

        


    </script>

@endsection
