<script type="text/javascript">
    function PrintElem(el) {
        var restorepage = $('body').html();
        var printcontent = $('#' + el).clone();
        $('body').empty().html(printcontent);
        window.print();
        $('body').html(restorepage);
    }
</script>
<script src="{{ URL::asset('vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<div id="sales_print" style="margin-left:10px;">
    <style>
        b,
        strong {
            font-weight: 500;
        }

        th {
            font-weight: 500;
        }
    </style>
    <table width="100%" border="0">
        <tbody>
            <tr>
                <td align="left">
                    <?php $nowdate = date('Y-m-d'); ?>
                    <strong>{{ trans('message.Date :') }} </strong><?php echo date(getDateFormat(), strtotime($nowdate)); ?>
                </td>
            </tr>
        </tbody>
    </table> <br />
    <div class="row">
        <div class="col-md-7 col-sm-7 col-xs-12">
            <h3><?php echo $logo->system_name; ?></h3>
            <div class="col-md-6 col-sm-12 col-xs-12 printimg position-relative">

                <img src="../../public/vehicle/service.png" style="width: 243px; height: 90px;">

                <img src="../../public/general_setting/<?php echo $logo->logo_image; ?>" width="230px" height="70px"
                    style="position: absolute; top: 10px; left: 10px;">

            </div>
            <div class="col-md-6 col-sm-12 col-xs-12">
                <p>
                    <?php
                    echo $logo->address . ' ';
                    echo '<br>' . getCityName($logo->city_id);
                    echo ', ' . getStateName($logo->state_id);
                    echo ', ' . getCountryName($logo->country_id);
                    echo '<br>' . $logo->email;
                    echo '<br>' . $logo->phone_number;
                    ?>
                </p>

            </div>
        </div>
        <div class="col-md-5 col-sm-5 col-xs-12 table-responsive">
            <table class="table" width="100%" style="border-collapse:collapse;">

                <tr>
                    <th class="cname">{{ trans('message.Name:') }} </th>
                    <td class="cname"> <?php echo getCustomerName($tbl_services->customer_id); ?> </td>
                </tr>
                <tr>
                    <th class="cname">{{ trans('message.Address:') }} </th>
                    <td class="cname"> <?php echo $customer->address;
                    echo ' ,';
                    echo getCityName("$customer->city_id"); ?><?php echo ','; ?><?php echo getStateName("$customer->state_id,");
                    echo ' ,';
                    echo getCountryName($customer->country_id); ?></td>
                </tr>
                <tr>
                    <th class="cname">{{ trans('message.Contact:') }} </th>
                    <td class="cname"><?php echo "$customer->mobile_no"; ?></td>
                </tr>
                <tr>
                    <th class="cname">{{ trans('message.Email :') }} </th>
                    <td class="cname"><?php echo $customer->email; ?></td>
                </tr>
                <tr>
                    <th class="cname">{{ trans('message.Status :') }} </th>
                    <td class="cname"><?php $payment_status =  getInvoicePaymentStatus($tbl_services->job_no);
                    if ($payment_status == 0) {
                                    echo trans('message.UnPaid');
                                } elseif ($payment_status == 1) {
                                    echo trans('message.Partially paid');
                                } elseif ($payment_status == 2) {
                                    echo trans('message.Full Paid');
                                } else {
                                    echo trans('message.UnPaid');
                                }
                    ?>
                    </td>
                </tr>
            </table>
        </div>
        <hr />

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" border="1" style="border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th class="cname text-center">{{ trans('message.Jobcard Number') }}</th>
                                <th class="cname text-center">{{ trans('message.Coupon Number') }}</th>
                                <th class="cname text-center">{{ trans('message.Vehicle Name') }}</th>
                                <th class="cname text-center">{{ trans('message.Regi. No.') }}</th>
                                <th class="cname text-center">{{ trans('message.In Date') }}</th>
                                <th class="cname text-center">{{ trans('message.Out Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="cname text-center"><?php echo "$tbl_services->job_no"; ?></td>
                                <td class="cname text-center"><?php if (!empty($job->coupan_no)) {
                                    echo $job->coupan_no;
                                } else {
                                    echo 'Paid Service';
                                } ?></td>
                                <td class="cname text-center"><?php if (!empty($job->vehicle_id)) {
                                    echo getVehicleName($job->vehicle_id);
                                } ?></td>
                                <td class="cname text-center"><?php if (!empty($s_date->registration_no)) {
                                    echo $s_date->registration_no;
                                } else {
                                    if(!empty($vehical)) {

echo $vehical->registration_no;
}
                                } ?> </td>
                                <td class="cname text-center"><?php if (!empty($job)) {
                                    echo date(getDateFormat(), strtotime($job->in_date));
                                } ?> </td>
                                <td class="cname text-center"><?php if (!empty($job)) {
                                    echo date(getDateFormat(), strtotime($job->out_date));
                                } ?> </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered adddatatable" width="100%" border="1"
                        style="border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th class="cname text-center">{{ trans('message.Assigned To') }}</th>
                                <th class="cname text-center">{{ trans('message.Repair Category') }}</th>
                                <th class="cname text-center">{{ trans('message.Service Type') }}</th>
                                <th class="cname text-center">{{ trans('message.Details') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="cname text-center"><?php echo getAssignedName($tbl_services->assign_to); ?> </td>
                                <td class="cname text-center"><?php echo $tbl_services->service_category; ?> </td>
                                <td class="cname text-center"><?php echo $tbl_services->service_type; ?> </td>
                                <td class="cname text-center"><?php echo $tbl_services->detail; ?> </td>

                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" border="1" style="border-collapse:collapse;">
                        <tr class="printimg">
                            <td class="cname"><B>{{ trans('message.SERVICE CHARGES') }}</B></td>
                        </tr>
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered adddatatable" width="100%" border="1"
                        style="border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">{{ trans('message.Category') }}</th>
                                <th class="text-center">{{ trans('message.Observation Point') }}</th>
                                <th class="text-center">{{ trans('message.Product Name') }}</th>
                                <th class="text-center">{{ trans('message.Price') }} (<?php echo getCurrencySymbols(); ?>)</th>
                                <th class="text-center">{{ trans('message.Quantity') }} </th>
                                <th class="text-center">{{ trans('message.Total Price') }} (<?php echo getCurrencySymbols(); ?>) </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
						$total1=0;
						$i = 1 ;
            if(!empty($service_pro)) {
						foreach($service_pro as $service_pros)
						{ ?>
                            <tr>
                                <td class="text-center cname"><?php echo $i++; ?></td>
                                <td class="text-center cname"> <?php echo $service_pros->category; ?></td>
                                <td class="text-center cname"> <?php echo $service_pros->obs_point; ?></td>
                                <td class="text-center cname"> <?php echo getProduct($service_pros->product_id); ?></td>
                                <td class="text-center cname"> <?php echo number_format((float) $service_pros->price, 2); ?></td>
                                <td class="text-center cname"><?php echo $service_pros->quantity; ?></td>
                                <td class="text-center cname"><?php echo number_format((float) $service_pros->total_price, 2); ?></td>
                                <?php $total1 += $service_pros->total_price; ?>
                            </tr>

                            <?php } } else {
                              ?>
                              <tr>
                                <td class="cname text-center" colspan="5">{{ trans('message.No data available in table.') }}
                                </td>
                            </tr>
                              <?php 
                            }
						?>
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" border="1" style="border-collapse:collapse;">
                        <tr class="printimg">
                            <td class="cname"><b>{{ trans('message.OTHER SERVICE CHARGES') }}</b></td>
                        </tr>
                    </table>
                </div>
                <div class="table-responsive">

                    <table class="table table-bordered adddatatable" width="100%" border="1"
                        style="border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">{{ trans('message.Charge for') }}</th>
                                <th class="text-center">{{ trans('message.Product Name') }}</th>
                                <th class="text-center">{{ trans('message.Price') }} (<?php echo getCurrencySymbols(); ?>)</th>
                                <th class="text-center">{{ trans('message.Total Price') }} (<?php echo getCurrencySymbols(); ?>) </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
						$total2=0;
						$i = 1 ;
            if(!empty($service_pro2)) {
						foreach($service_pro2 as $service_pros)
						{ ?>
                            <tr>
                                <td class="text-center cname"><?php echo $i++; ?></td>
                                <td class="text-center cname">{{ trans('message.Other Charges') }}</td>
                                <td class="text-center cname"><?php echo $service_pros->comment; ?></td>
                                <td class="text-center cname"><?php echo number_format((float) $service_pros->total_price, 2); ?></td>
                                <td class="text-center cname"><?php echo number_format((float) $service_pros->total_price, 2); ?></td>
                                <?php $total2 += $service_pros->total_price; ?>
                            </tr>
                            <?php } } else {
                              ?>
                              <tr>
                                <td class="cname text-center" colspan="5">{{ trans('message.No data available in table.') }}
                                </td>
                            </tr>
                              <?php
                            }
						?>
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" border="1"
                        style="border-collapse:collapse;">
                        <tr>
                            <td class="text-center cname"><b>{{ trans('message.Fix Service Charge') }}<b></td>
                            <td class="text-center cname"><?php $fix = $tbl_services->charge;
                            if (!empty($fix)) {
                                echo number_format($fix, 2);
                            } else {
                                echo 'Free Service';
                            } ?></td>
                        </tr>
                        <tr>
                            <td class="text-right cname" width="81.5%"><b>{{ trans('message.Total Service Amount') }}
                                    (<?php echo getCurrencySymbols(); ?>) :</b></td>
                            <td class="text-center cname"><b><?php $total_amt = $total1 + $total2 + $fix;
                            echo number_format($total_amt, 2); ?></b></td>
                        </tr>
                        <?php
                        if($service_tax !== null) {
                            ?>
                        
                        <tr>
                            <td class="text-right cname" width="81.5%"><b>{{ trans('message.Discount') }}
                                    (<?php echo $dis = $service_tax->discount . '%'; ?>):</b></td>
                            <td class="text-center cname"><b><?php $dis = $service_tax->discount;
                            $discount = ($total_amt * $dis) / 100;
                            echo number_format($discount, 2); ?></b></td>
                        </tr>
                        <?php
                    }
                    ?>
                        <tr>
                            <td class="text-right cname" width="81.5%"><b>{{ trans('message.Total') }}
                                    (<?php echo getCurrencySymbols(); ?>) :</b></td>
                            <td class="text-center cname"><b><?php $after_dis_total = $total_amt - $discount;
                            echo number_format($after_dis_total, 2); ?></b></td>
                        </tr>

                        <?php 
					if(!empty($service_taxes))
					{
						$all_taxes = 0;
						$total_tax = 0;
                        
                        $service_tax = 0;
                        $tax_total = 0;
						foreach($service_taxes as $ser_tax)
						{ 
							$taxes_to_count = preg_replace("/[^0-9,.]/", "", $ser_tax);
						
							$all_taxes = ($after_dis_total*$taxes_to_count)/100;  
							
							$total_tax +=  $all_taxes;
                            
								$service_tax = $all_taxes - $after_dis_total;
                                $tax_total += $service_tax;
                            
					?>
                        <tr>
                            <td class="text-right cname" width="81.5%"><b><?php echo $ser_tax; ?> %:</b></td>
                            <td class="text-center cname" width="81.5%"><b><?php echo $after_dis_total;?></b></td>
                        </tr>
                        <?php 
						}
						$final_grand_total = $after_dis_total+$tax_total;
					}
					else
					{
						$final_grand_total = $after_dis_total;
					}
					?>

                        <tr>
                            <td class="text-right cname" width="81.5%"><b>{{ trans('message.Grand Total') }}
                                    (<?php echo getCurrencySymbols(); ?>) :</b></td>
                            <td class="text-center cname"><b><?php $final_grand_total;
                            echo number_format($final_grand_total, 2); ?></b></td>
                        </tr>
                    </table>
                    <div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="modal-footer">

    <button type="button" class="btn btn-outline-secondary printbtn" id=""
        onclick="PrintElem('sales_print')">{{ trans('message.Print') }} </button>

    <a href="{!! url('/customer/list/' . $tbl_services->customer_id) !!}" class="prints"><button type="button"
            class="btn btn-outline-secondary">{{ trans('message.Close') }}</button></a>
</div>
