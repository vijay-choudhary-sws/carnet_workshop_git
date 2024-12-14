<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style type="text/css">
        @if (getLangCode()==='hi')body,
        p,
        td,
        div,
        th {
            font-family: Poppins;
        }

        @elseif (getLangCode()==='gu') body,
        p,
        td,
        div,
        th {
            font-family: Poppins;
        }

        @elseif (getLangCode()==='ja') body,
        p,
        td,
        div,
        th {
            font-family: Poppins;
        }

        @elseif (getLangCode()==='zhcn') body,
        p,
        td,
        div,
        th {
            font-family: Poppins; // not working
        }

        @elseif (getLangCode()==='th') body,
        p,
        td,
        div,
        th,
        strong {
            font-family: Poppins;
        }

        @elseif (getLangCode()==='mr') body,
        p,
        td,
        div,
        th {
            font-family: Poppins;
        }

        @elseif (getLangCode()==='ta') body,
        p,
        td,
        div,
        th,
        strong {
            font-family: Poppins;
        }

        @else body,
        p,
        td,
        div,
        th,
        strong {
            font-family: Poppins;
        }

        @endif
        /* * {
      font-family: Poppins;
    }
    body, p, td, div, th { font-family: Poppins; } */

        @font-face {
            font-family: "Poppins" !important;
            font-weight: normal;
            font-style: italic;
        }

        body {
            font-family: "Poppins";
        }
    </style>
    <style>
        .margin-bottom {
            margin-bottom: 1rem;
        }

        .heading_gatepass {
            align-items: center;
            margin-left: 40%;
        }

        .fw-bold {
            font-weight: 700 !important;
            color: #333;
        }
        .padding-8{
            padding:8px;
        }
    </style>
</head>

<body>
    <table width="100%" border="0" style="margin:0px 8px 0px 8px; font-family:Poppins;">
        <tr>
            <td align="left">
                <h3 style="font-size:18px;"><?php echo $logo->system_name; ?></h3>
            </td>
        </tr>
    </table>
    <hr />
    <table width="100%" border="0">
        <tbody>
            <tr>
                <td width="50%">
                    <!-- <h4 class="text-center">{{ $logo->system_name }}</h4> -->
                    <img src="{{ base_path() }}/public/general_setting/<?php echo $logo->logo_image; ?>" width="230px" height="70px">
                <td width="20%">
                    <div class="col-xl-12 col-md-12 col-sm-12 mb-3">
                        <label class="fw-bold"><b>{{ trans('message.Product Code') }} : </b></label>
                        <label class=""> <?php echo $product->product_no; ?> </label>
                    </div>
                    <div class="col-xl-12 col-md-12 col-sm-12 mb-3">
                        <label class="fw-bold"><b>{{ trans('message.Manufacturer Name') }} : </b></label>
                        <label class=""> <?php echo getProductName($product->product_type_id); ?> </label>
                    </div>
                    <div class="col-xl-12 col-md-12 col-sm-12">
                        <label class="fw-bold"><b>{{ trans('message.Product Name') }} : </b></label>
                        <label class=""> <?php echo $product->name; ?> </label>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <br><br>
    <hr />
    <h3 class="heading_gatepass"><u>{{ trans('message.PURCHASE DETAILS') }}</u></h3>
    <br>
    <table class="margin-bottom table table-bordered itemtable" width="100%" border="1" style="border-collapse:collapse;">
        <thead>
            <tr>
                <th style="padding:8px;">{{ trans('message.Purchase Date') }}</th>
                <th style="padding:8px;">{{ trans('message.Supplier Name') }}</th>
                <th style="padding:8px;">{{ trans('message.Quantity') }}</th>
            </tr>
        </thead>
        <tbody>

            <?php $total = 0;
            // if(!empty($stockdata))
            if (count($stockdata) !== 0) {

                foreach ($stockdata as $stockdatas) { ?>
                    <tr>
                        <td style="padding:8px;" align="center"><?php echo date(getDateFormat(), strtotime($stockdatas->date)); ?></td>
                        <td style="padding:8px;" align="center"><?php echo getSupplierName($stockdatas->supplier_id); ?></td>
                        <td style="padding:8px;" align="center"><?php echo $stockdatas->qty; ?></td>
                        <?php $total += $stockdatas->qty; ?>
                    </tr>
                <?php }
            } else {
                ?>
                <tr>
                    <td style="padding:8px;" colspan="7">{{ trans('message.No data available in table.') }}</td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
    <table class="margin-bottom table table-bordered itemtable" width="100%" border="1" style="border-collapse:collapse;">
        <tbody>
            <tr>
                <td colspan="2" class="text-right padding-8" align="right">
                    <div class="col-xl-6 col-md-6 col-sm-12 me-50">
                        <label class="fw-bold"> <b>{{ trans('message.Sales Stock:') }}&nbsp;&nbsp;&nbsp; </b></label>
                        <label class=""> <?php echo $celltotal; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    </div>
                    <!-- {{ trans('message.Sales Stock:') }} &nbsp; &nbsp; <?php echo $celltotal; ?></td> -->
            </tr>
        </tbody>
    </table>
    <table class="margin-bottom table table-bordered itemtable" width="100%" border="1" style="border-collapse:collapse;">
        <tbody>
            <tr>
                <td colspan="2" class="text-right padding-8" align="right">
                    <div class="col-xl-6 col-md-6 col-sm-12 me-50">
                        <label class="fw-bold"> <b>{{ trans('message.Service Stock') }}:&nbsp;&nbsp;&nbsp; </b></label>
                        <label class=""> <?php echo $product_service_stocks_total; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    </div>
                    <!-- {{ trans('message.Service Stock') }}: &nbsp; &nbsp; <?php echo $product_service_stocks_total; ?></td> -->
            </tr>
        </tbody>
    </table>
    <table class="margin-bottom table table-bordered itemtable" width="100%" border="1" style="border-collapse:collapse;">
        <tbody>
            <tr> <?php $Currentstock = $total - $sale_service_stock; ?>
                <td colspan="2" class="text-right padding-8" align="right">
                    <div class="col-xl-6 col-md-6 col-sm-12 me-50">
                        <label class="fw-bold"> <b>{{ trans('message.Current Stock:') }}&nbsp;&nbsp;&nbsp;</b> </label>
                        <label class="">{{ getStockCurrent($p_id) }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </label>
                    </div>
                    <!-- {{ trans('message.Current Stock:') }} &nbsp; &nbsp; <?php echo $Currentstock; ?></td> -->
            </tr>
        </tbody>
    </table>
</body>

</html>