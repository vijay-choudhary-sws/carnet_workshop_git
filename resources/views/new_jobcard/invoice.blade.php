
<div class="modal-header">
    <h4 class="modal-title" id="myLargeModalLabel">{{$title}}</h4>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
 
<div class="modal-body"> 
    <div class="row mt-2 mb-0">

        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">

            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 printimg position-relative mx-0"> 
                <img src="<?= asset('public/general_setting/' . $logo->logo_image) ?>" class="system_logo_img">
   
            </div>

            <div class="row col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ms-0 mt-3 serviceinvoicemodal">
                <p class="mb-0">
                    <img src="<?= asset('public/img/icons/Vector (15).png') ?>">
                      {{ $logo->email }}<br><i class="fa fa-phone fa-lg"></i>&nbsp;&nbsp;{{ $logo->phone_number}}
                </p><div class="col-12 d-flex align-items-start m-1 mx-0">

                    <img src="<?= asset('public/img/icons/Vector (14).png') ?>">
                    <div class="col mx-2">
                        test , Ahmedabad, Gujarat, India                            </div>

                </div>
                <br> 
                <p></p>
            </div>
        </div>
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 mx-0 ps-4">
            <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 d-inline-flex">
                            <p class="fw-bold mb-0"><i class="fa fa-user fa-lg"></i></p>
                            <p class="cname mb-0 ps-2">{{ $customers->name .' '. $customers->lastname}}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 d-inline-flex">
                            <p class="fw-bold mb-0"><img src="<?= asset('public/img/icons/Vector (14).png') ?>"></p>
                            <p class="cname mb-0 ps-2">Jawahar Colony, Madhya Pradesh, India</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 d-inline-flex">
                            <p class="fw-bold mb-0"><i class="fa fa-phone fa-lg"></i></p>
                            <p class="cname mb-0 ps-2">{{ $customers->mobile_no }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 d-inline-flex">
                            <p class="fw-bold mb-0"><img src="<?= asset('public/img/icons/Vector (15).png') ?>" class="m-0"></p>
                            <p class="cname mb-0 ps-2">{{ $customers->email }}</p>
                        </div>
                    </div>
                </div><div class="col-md-12 col-sm-12 col-xs-12">  
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 d-inline-flex">
                            <p class="fw-bold mb-0">Status :</p>
                            <p class="cname mb-0 ps-2"><span style="color: rgb(255, 0, 0);"> @switch($newjobcard->status)
                                @case(1)
                                    <span class="badge text-bg-success">Success</span>
                                @break

                                @case(2)
                                    <span class="badge text-bg-info">Confirmed</span>
                                @break

                                @default
                                <span class="badge text-bg-danger">Open</span>
                            @endswitch
                        </span> 
                    </p>
                        </div>
                    </div>
                                                    <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12 d-inline-flex">
                                <p class="fw-bold mb-0">Date :</p>
                                <p class="cname mb-0 ps-2">{{ \Carbon\Carbon::parse($newjobcard->entry_date)->format('d-m-Y h:i A') ?? '-' }}</p>
                            </div>
                        </div> 
                                                                        </div><table class="table halfpaidview">
                
                
            </table>
        </div>
        <hr>
        <div class="table-responsive col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
            <table class="table halfpaymentview" border="1" style="border-collapse:collapse;" width="100%">
                <thead>
                    <tr>
                        <th class="cname text-start">Jobcard Number</th>
                        <th class="cname text-start">Coupon Number</th>
                        <th class="cname text-start">Vehicle Name</th>
                        <th class="cname text-start">Number Plate</th>
                        <th class="cname text-start">In Date</th>
                        <th class="cname text-start">Out Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="cname text-start fw-bold">{{ $newjobcard->jobcard_number }}</td>
                        <td class="cname text-start fw-bold">Paid Service</td>
                        <td class="cname text-start fw-bold">@checked($vehicles->first){{getvehicleBrand($vehicles->vehiclebrand_id).'/'.$vehicles->modelname.'/'.$vehicles->number_plate.'/'.$vehicles->id}}</td>
                        <td class="cname text-start fw-bold">{{ $vehicles->number_plate }}</td>
                        <td class="cname text-start fw-bold">{{ \Carbon\Carbon::parse($newjobcard->entry_date)->format('d-m-Y h:i A') ?? '-' }} </td>
                        <td class="cname text-start fw-bold">2024-12-15 10:08:24 </td>
                    </tr>
                </tbody>
            </table>
        </div> 

 
        <hr>
                        
                            <div class="table-responsive col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
            

                            <div class="table-responsive col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ms-0">

                <table class="table table-bordered" border="0" style="border-collapse:collapse;" width="100%">
                    <tbody><tr class="printimg">
                        <td class="cname" style="font-size: 14px;">
                            <b>Part Charges</b>
                        </td>
                    </tr>
                </tbody></table>


                {{-- <div class="table-responsive col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                    <table class="table table-bordered adddatatable" border="0" style="border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th class="text-start" style="width: 5%;">#</th>
                                <th class="text-start">Charge for</th>
                                <!-- <th class="text-start">Product Name</th> -->
                                <th class="text-start">Price ($)</th>
                                <th class="text-start" style="width: 25%;">
                                    Total Price ($)
                                </th>
                            </tr>
                        </thead>

                        <tbody>

                            <!-- <tr>
                        <td class="cname text-center" colspan="5">
                            No data available in table.
                        </td>
                    </tr> -->
                                                                    <tr>
                                    <td class="text-start cname">1</td>
                                    <td class="text-start cname">Wash Bay Service</td>
                                    <td class="text-start cname">150.00</td>
                                    <td class="text-end cname">
                                        150.00</td>
                                                                            </tr>
                            
                                                                
                                                                
                        </tbody>
                    </table>
                </div> --}}


                <div class="table-responsive col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                    <table class="table table-bordered adddatatable" border="0" style="border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th class="text-start" style="width: 5%;">#</th>
                                <th class="text-start">Product Name</th> 
                                <th class="text-start">Mechanic Name</th> 
                                <th class="text-start">Quantity</th> 
                                <th class="text-start">Price (₹)</th>
                                <th class="text-start" style="width: 25%;">
                                    Total Price (₹)
                                </th>
                            </tr>
                        </thead>
        
                        <tbody>
                            @if (count($jobCardSpareParts) > 0)
                            @php $i = 1 ; @endphp
                            @foreach ($jobCardSpareParts as $jobCardSparePart)
                                <tr> 
                                    <td class="text-start cname">{{ $i }}</td>
                                    <td class="text-start cname">{{ $jobCardSparePart->stock_label_name }}</td>
                                    <td class="text-start cname">{{ $jobCardSparePart->User->display_name }}</td>
                                    <td class="text-start cname">{{ $jobCardSparePart->quantity }}</td> 
                                    <td class="text-start cname">{{ $jobCardSparePart->price }}</td> 
                                    <td class="text-start cname">{{ $jobCardSparePart->final_amount }}</td> 
                                </tr>
                                @php $i++ @endphp
                            @endforeach
                        @endif                            
                        </tbody>
                    </table>
                </div>
                


                <table class="table table-bordered" border="0" style="border-collapse:collapse;" width="100%">
                    <tbody><tr class="printimg">
                        <td class="cname" style="font-size: 14px;">
                            <b>Extra Charges</b>
                        </td>
                    </tr>
                </tbody>
            </table>  
                <div class="table-responsive col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                    <table class="table table-bordered adddatatable" border="0" style="border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th class="text-start" style="width: 5%;">#</th>
                                <th class="text-start">Label</th> 
                                <th class="text-start">Charges</th>  
                            </tr>
                        </thead>
        
                        <tbody>
                            @if (count($jobCardExtraCharges) > 0)
                            @php $i = 1 ; @endphp
                            @foreach ($jobCardExtraCharges as $jobCardExtraCharge)
                                <tr> 
                                    <td class="text-start cname">{{ $i }}</td>
                                    <td class="text-start cname">{{ $jobCardExtraCharge->label }}</td>
                                    <td class="text-start cname">{{ $jobCardExtraCharge->charges }}</td> 
                                </tr>
                                @php $i++ @endphp
                            @endforeach
                        @endif                            
                        </tbody>
                    </table>
                </div> 
            <div class="table-responsive col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                <table class="table table-bordered halfpaymentcharge" style="border-collapse:collapse; width: 99%;">
                    <tbody>
                        {{-- <tr >
                        <td class="text-end cname" style="width: 75%;">
                            Fixed Service Charge ($):
                        </td>
                        <td class="text-end fw-bold cname gst f-17"><b>300.00</b></td>
                    </tr> --}}
                    <tr>
                        <td class="text-end cname">
                            Total Service Amount (₹) :
                        </td>
                        <td class="text-end fw-bold cname gst f-17"><b>{{ number_format($newjobcard->amount,2) }}</b></td>
                    </tr>
                                                <tr>
                        <td class="text-end cname">Discount
                            (₹) :</td>
                        <td class="text-end fw-bold cname gst f-17"><b>{{ $newjobcard->discount }}</b></td>
                    </tr>
                    
                    <tr>
                        <td class="text-end cname">Total
                            (₹) :</td>
                        <td class="text-end fw-bold cname gst f-17">
                            <b>{{ number_format($newjobcard->final_amount,2) }}</b>
                        </td>
                    </tr> 


                                                <tr>
                        <td class="text-end cname">
                            Adjustment Amount(Paid Amount)
                            (₹) :
                        </td>
                        <td class="text-end fw-bold cname gst f-17"><b>{{ number_format($newjobcard->advance,2) }}</b></td>
                    </tr>

                    <tr>
                        <td class="text-end cname">Due Amount
                            (₹) :
                        </td>
                        <td class="text-end fw-bold cname gst f-17"><b>{{ number_format($newjobcard->balance_amount,2) }}</b></td>
                    </tr>

                    <tr class="large-screen">
                        <td class="text-right cname" colspan="2">
                            <div class="row col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 grand_total_freeservice pt-2">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 text-end fullpaid_invoice_list pt-1 mps-0">
                                    Grand Total(₹):
                                </div>
                                <div class="row col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                                    <label class="total_amount pt-1">{{ number_format($newjobcard->balance_amount,2) }} </label>
                                </div>

                            </div>
                        </td>
                    </tr>

                    <tr class="small-screen">
                        <td class="text-end cname text-light" width="81.5%">Grand Total ($) :</td>
                        <td class="text-right fw-bold cname gst text-light text-end">405.00 </td>
                    </tr>
                </tbody>
            <tfoot>
                <tr>
                    <td>
                        <a href="{{ route('download.invoice', $newjobcard->id) }}" class="btn btn-primary">
                            Download PDF
                        </a>
                        
                    </td>
                </tr>
            </tfoot>
            </table>
            </div>
            </div>
    </div>
</div>
</div>