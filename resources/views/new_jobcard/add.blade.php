@extends('layouts.app')
@section('content')
    <style>
        .list-count {
            width: 10px !important;
            height: 10px !important;
            top: 0;
            right: 10px;
            padding: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 10px;
        }

        span.select2-selection.select2-selection--single {
            width: auto !important;
        }

        #spare-table th,
        #spare-table .add-spare-btn,
        .green-variant {
            background-color: #2E7D32 !important;
            color: white !important;
        }

        #lubes-table th,
        #lubes-table .add-spare-btn,
        .blue-variant,
        .blue-variant i.fa {
            background-color: #1976D2 !important;
            color: white !important;
        }

        #tools-table th,
        #tools-table .add-spare-btn,
        .orange-variant,
        .orange-variant i.fa {
            background-color: #D84315 !important;
            color: white !important;
        }

        .grey-variant,
        .grey-variant i.fa {
            background-color: #424242 !important;
            color: white !important;
        }

        #accessory-table th,
        #accessory-table .add-spare-btn,
        .purple-variant,
        .purple-variant i.fa {
            background-color: #6A1B9A !important;
            color: white !important;
        }

        .green-text {
            color: #2E7D32 !important;
        }

        .blue-text {
            color: #1976D2 !important;
        }

        .orange-text {
            color: #D84315 !important;
        }

        .grey-text {
            color: #424242 !important;
        }

        .purple-text {
            color: #6A1B9A !important;
        }
        
    </style>
    <div class="right_col " role="main">
        <div class="">
            <div class="page-title">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
                            <a href="{{ route('accessory.list') }}" id=""><span class="titleup"><img
                                        src="{{ URL::asset('public/supplier/Back Arrow.png') }}" class="back-arrow">
                                    {{ trans('message.JobCard') }}</span></a>
                        </div>
                        @include('dashboard.profile')
                        <div class="ulprofile">
                            <div class="input-group mt-2">
                                <span class="input-group-text">JobCard No.</span>
                                <input type="text" form="jobcard-form" id="jobcard_number" name="jobcard_number"
                                    class="form-control bg-light" value="{{ generateHashJobCardNumber(time()) }}" readonly
                                    placeholder="Job Card Number" aria-label="Job Card Number">
                                <span class="input-group-text">Date</span>
                                <input type="date" class="form-control bg-light" value="{{ now()->format('Y-m-d') }}"
                                    readonly placeholder="Date" aria-label="Date">
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
            <div class="clearfix"></div>
            @include('success_message.message')
            <div class="row">
                <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                    <div class="x_content">
                        <div class="x_panel">
                            <br />
                            <form id="jobcard-form" action="{{ route('newjobcard.store') }}" method="POST"
                                onsubmit="event.preventDefault();submitJobcard(this);">
                                @csrf
                                <div class="row">
                                    <div class="col-6">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h6 class="text-center m-0">
                                                    <b>Search or Create New User</b>
                                                </h6>
                                                <hr>
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="search"><i
                                                            class="fa fa-search"></i></span>
                                                    <select name="customer_name" id="customer_name"
                                                        class="form-control select2" aria-describedby="search"
                                                        style="width: 75%;">
                                                        <option value="">-- Select Customer --</option>
                                                        @foreach ($customers as $customer)
                                                            <option value="{{ $customer->id }}">
                                                                {{ getCustomerName($customer->id) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    {{-- <button type="button" class="input-group-text">Add New</button> --}}
                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#myModal"
                                                        class="btn btn-outline-secondary input-group-text fl margin-left-0">{{ trans('+') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <h6 class="m-0"><b>Job Card Details</b></h6>
                                                    <button type="button" class="btn btn-sm btn-info">Last History</button>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-4">
                                                        <div class="form-floating mb-3">
                                                            <input type="number" name="km_reading" class="form-control"
                                                                id="km_reading" value="0" min="0">
                                                            <label for="km_reading">KM Reading</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <label for="fual level" class="form-label">Fual Level</label>
                                                        <input type="range" class="form-range" id="fual level"
                                                            name="fual_level" min="0" max="100">
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="form-floating">
                                                            <select class="form-select" id="supervisor" name="supervisor"
                                                                aria-label="Floating label select example">
                                                                <option selected>Open this select menu</option>
                                                                <option value="1">One</option>
                                                                <option value="2">Two</option>
                                                                <option value="3">Three</option>
                                                            </select>
                                                            <label for="supervisor">Supervisor</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <a href="javascript:void(0)" onclick=customerVoice(this);return;false;
                                                        class="text-center p-2 position-relative d-flex flex-column justify-content-between">
                                                        <img src="{{ asset('public/assets/jobcard_img/customer_voice2.jpg') }}"
                                                            alt="image" width="80">
                                                        <p class="my-2">Costomer voice</p>
                                                        <p class="position-absolute list-count rounded-circle bg-primary text-white"
                                                            style="width: 10px !important;height:10px !important;">@if(!empty($jobCardscustomervoice)) {{$jobCardscustomervoice->count()}} @else 0 @endif</p>
                                                    </a>
                                                    <a href="javascript:void(0)" onclick=addDentMark(this);return;false;
                                                        class="text-center p-2 position-relative d-flex flex-column justify-content-between">
                                                        <img src="{{ asset('public/assets/jobcard_img/dent.jpg') }}"
                                                            alt="image" width="80">
                                                        <p class="my-2">Dent Marks</p>
                                                        <p class="position-absolute list-count rounded-circle bg-primary text-white"
                                                            style="width: 10px !important;height:10px !important;">@if(!empty($jobCardsDentMark)) {{$jobCardsDentMark->count()}} @else 0 @endif </p>
                                                    </a>
                                                    <a href="javascript:void(0)" onclick=addPhoto(this);return;false;
                                                        class="text-center p-2 position-relative d-flex flex-column justify-content-between">
                                                        <img src="{{ asset('public/assets/jobcard_img/photo.jpg') }}"
                                                            alt="image" width="80">
                                                        <p class="my-2">Photos</p>
                                                        <p class="position-absolute list-count rounded-circle bg-primary text-white"
                                                            style="width: 10px !important;height:10px !important;">@if(!empty($jobCardsImage)) {{$jobCardsImage->count()}} @else 0 @endif</p>
                                                    </a>
                                                    <a href="javascript:void(0)" onclick=accessories(this);return;false;
                                                        class="text-center p-2 position-relative d-flex flex-column justify-content-between">
                                                        <img src="{{ asset('public/assets/jobcard_img/accessory.jpg') }}"
                                                            alt="image" width="80">
                                                        <p class="my-2">Accessories</p>
                                                        <p class="position-absolute list-count rounded-circle bg-primary text-white"
                                                            style="width: 10px !important;height:10px !important;">@if(!empty($jobCardsaccessary)) {{$jobCardsaccessary->count()}} @else 0 @endif</p>
                                                    </a>
                                                    <a href="javascript:void(0)" onclick=workNotes(this);return;false;
                                                        class="text-center p-2 position-relative d-flex flex-column justify-content-between">
                                                        <img src="{{ asset('public/assets/jobcard_img/work_note.jpg') }}"
                                                            alt="image" width="80">
                                                        <p class="my-2">Work Note</p>
                                                        <p class="position-absolute list-count rounded-circle bg-primary text-white worknotecount"
                                                            style="width: 10px !important;height:10px !important;">@if(!empty($jobCardsworknote)) {{$jobCardsworknote->count()}} @else 0 @endif</p>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-2">
                                        <div class="card">
                                            <div class="card-body p-0" id="spare-part-table-counter">
                                                <a href="javascript:void(0)"
                                                    class="d-flex justify-content-between green-variant px-1 py-2 rounded-top text-white"
                                                    onclick="viewTable(this);" target-table="spare-table">
                                                    <p class="m-0"><b>Spare(<span class="tr-count">0</span>)</b></p>
                                                    <p class="text-end m-0">
                                                        <b class="">₹<span class="total-count">0.00</span></b>
                                                        <small class="d-block ">₹<span
                                                                class="discount-count">0.00</span></small>
                                                    </p>
                                                </a>
                                                <div class="d-flex justify-content-between p-2 ps-0">
                                                    <div class="fs-3 p-1 green-variant rounded mx-1 px-2"><i
                                                            class="fa fa-user text-white"></i></div>
                                                    <div class="text-end">
                                                        <p class="m-0 ">Total: ₹<span
                                                                class="final-total-count">0.00</span></p>
                                                        <div class="d-flex">
                                                            discount
                                                            <input type="number" class="w-100">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="card">
                                            <div class="card-body p-0" id="lubes-table-counter">
                                                <a href="javascript:void(0)"
                                                    class="d-flex justify-content-between blue-variant px-1 py-2 rounded-top text-white"
                                                    onclick="viewTable(this);" target-table="lubes-table">
                                                    <p class="m-0"><b>Lubes(<span class="tr-count">0</span>)</b></p>
                                                    <p class="text-end m-0">
                                                        <b class="">₹<span class="total-count">0.00</span></b>
                                                        <small class="d-block ">₹<span
                                                                class="discount-count">0.00</span></small>
                                                    </p>
                                                </a>
                                                <div class="d-flex justify-content-between p-2 ps-0">
                                                    <div class="fs-3 p-1 blue-variant rounded mx-1 px-2"><i
                                                            class="fa fa-user text-white"></i></div>
                                                    <div class="text-end">
                                                        <p class="m-0 ">Total: ₹<span
                                                                class="final-total-count">0.00</span></p>
                                                        <div class="d-flex">
                                                            discount
                                                            <input type="number" class="w-100">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="card">
                                            <div class="card-body p-0" id="tools-table-counter">
                                                <a href="javascript:void(0)"
                                                    class="d-flex justify-content-between orange-variant px-1 py-2 rounded-top text-white"
                                                    onclick="viewTable(this);" target-table="tools-table">
                                                    <p class="m-0"><b>Tool(<span class="tr-count">0</span>)</b></p>
                                                    <p class="text-end m-0">
                                                        <b class="">₹<span class="total-count">0.00</span></b>
                                                        <small class="d-block ">₹<span
                                                                class="discount-count">0.00</span></small>
                                                    </p>
                                                </a>
                                                <div class="d-flex justify-content-between p-2 ps-0">
                                                    <div class="fs-3 p-1 orange-variant rounded mx-1 px-2"><i
                                                            class="fa fa-user text-white"></i></div>
                                                    <div class="text-end">
                                                        <p class="m-0 ">Total: ₹<span
                                                                class="final-total-count">0.00</span></p>
                                                        <div class="d-flex">
                                                            discount
                                                            <input type="number" class="w-100">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="card">
                                            <div class="card-body p-0" id="accessory-table-counter">
                                                <a href="javascript:void(0)"
                                                    class="d-flex justify-content-between purple-variant px-1 py-2 rounded-top text-white"
                                                    onclick="viewTable(this);" target-table="accessory-table">
                                                    <p class="m-0"><b>Accessory(<span class="tr-count">0</span>)</b>
                                                    </p>
                                                    <p class="text-end m-0">
                                                        <b class="">₹<span class="total-count">0.00</span></b>
                                                        <small class="d-block ">₹<span
                                                                class="discount-count">0.00</span></small>
                                                    </p>
                                                </a>
                                                <div class="d-flex justify-content-between p-2 ps-0">
                                                    <div class="fs-3 p-1 purple-variant rounded mx-1 px-2"><i
                                                            class="fa fa-user text-white"></i></div>
                                                    <div class="text-end">
                                                        <p class="m-0 ">Total: ₹<span
                                                                class="final-total-count">0.00</span></p>
                                                        <div class="d-flex">
                                                            discount
                                                            <input type="number" class="w-100">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="card">
                                            <div class="card-body p-0" id="total-counter">
                                                <a href="javascript:void(0)"
                                                    class="d-flex justify-content-between grey-variant px-1 py-2 rounded-top text-white">
                                                    <p class="m-0"><b>Total(<span class="tr-count">0</span>)</b></p>
                                                    <p class="text-end m-0">
                                                        <b class="">₹<span class="total-count">0.00</span></b>
                                                        <small class="d-block ">₹<span
                                                                class="discount-count">0.00</span></small>
                                                    </p>
                                                </a>
                                                <div class="d-flex justify-content-between p-2 ps-0">
                                                    <div class="fs-3 p-1 grey-variant rounded mx-1 px-2"><i
                                                            class="fa fa-user text-white"></i></div>
                                                    <div class="text-end">
                                                        <p class="m-0 ">Total: ₹<span
                                                                class="final-total-count">0.00</span></p>
                                                        <div class="d-flex">
                                                            discount
                                                            <input type="number" class="w-100">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 table-view" id="spare-table">
                                    <table class="table table-bordered" id="spare-part-table">
                                        <thead>
                                            <tr>
                                                <th>Part Name</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Total Amount</th>
                                                <th>Discount</th>
                                                <th>Final Amount</th>
                                                <th>Assigned Mechanic</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <td>
                                                    <div class="d-flex">
                                                        <select class="form-control select2" id="spare-parts-dropdown"
                                                            onchange="addJobCardRow(this,'spare-part-table')"
                                                            style="min-width: 300px !important;">
                                                            <option value="" selected disabled>--Select Spare Part --
                                                            </option>
                                                        </select>
                                                        <button type="button"
                                                            class="btn btn-outline-secondary add-spare-btn"
                                                            onclick=addForm(this);return;false;> + </button>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="mt-4 table-view" id="lubes-table" style="display: none;">
                                    <table class="table table-bordered" id="lubes-table">
                                        <thead>
                                            <tr>
                                                <th>Part Name</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Total Amount</th>
                                                <th>Discount</th>
                                                <th>Final Amount</th>
                                                <th>Assigned Mechanic</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <td>
                                                    <div class="d-flex">
                                                        <select class="form-control select2" id="lubes-dropdown"
                                                            onchange="addJobCardRow(this,'lubes-table')"
                                                            style="min-width: 300px !important;">
                                                            <option value="" selected disabled>--Select Spare Part --
                                                            </option>
                                                        </select>
                                                        <button type="button"
                                                            class="btn btn-outline-secondary add-spare-btn"
                                                            onclick=addForm(this);return;false;> + </button>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="mt-4 table-view" id="tools-table" style="display: none;">
                                    <table class="table table-bordered" id="tools-table">
                                        <thead>
                                            <tr>
                                                <th>Part Name</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Total Amount</th>
                                                <th>Discount</th>
                                                <th>Final Amount</th>
                                                <th>Assigned Mechanic</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <td>
                                                    <div class="d-flex">
                                                        <select class="form-control select2" id="tools-dropdown"
                                                            onchange="addJobCardRow(this,'tools-table')"
                                                            style="min-width: 300px !important;">
                                                            <option value="" selected disabled>--Select Spare Part --
                                                            </option>
                                                        </select>
                                                        <button type="button"
                                                            class="btn btn-outline-secondary add-spare-btn"
                                                            onclick=addForm(this);return;false;> + </button>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="mt-4 table-view" id="accessory-table" style="display: none;">
                                    <table class="table table-bordered" id="accessory-table">
                                        <thead>
                                            <tr>
                                                <th>Part Name</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Total Amount</th>
                                                <th>Discount</th>
                                                <th>Final Amount</th>
                                                <th>Assigned Mechanic</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <td>
                                                    <div class="d-flex">
                                                        <select class="form-control select2" id="accessory-dropdown"
                                                            onchange="addJobCardRow(this,'accessory-table')"
                                                            style="min-width: 300px !important;">
                                                            <option value="" selected disabled>--Select Spare Part --
                                                            </option>
                                                        </select>
                                                        <button type="button"
                                                            class="btn btn-outline-secondary add-spare-btn"
                                                            onclick=addForm(this);return;false;> + </button>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <hr class="my-4">
                                <div class="row ">
                                    <div class="col-md-6">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5>JobCard Details</h5>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="cost-estimate" class="form-label">Cost Estimate</label>
                                                            <input type="text" id="cost-estimate" name="cost-estimate"
                                                                value="0" placeholder="Cost Estimate"
                                                                class="form-control rounded">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="delivery_date" class="form-label">Delivery Date</label>
                                                            <input type="date" id="delivery_date" name="delivery_date"
                                                            value="{{ now()->format('Y-m-d') }}" placeholder="Delivery Date"
                                                                class="form-control rounded">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="delivery_time" class="form-label">Delivery Time</label>
                                                            <input type="time" id="delivery_time" name="delivery_time"
                                                            value="{{ now()->format('m:i') }}" placeholder="Delivery Time"
                                                                class="form-control rounded">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card  h-100">
                                            <div class="card-body ">
                                                <h5>Billing Details</h5>
                                                <div class="mb-3">
                                                    <label for="total-amount" class="form-label">Total Amount</label>
                                                    <input type="text" id="total-amount" name="total-amount"
                                                        value="0" placeholder="Total Amount"
                                                        class="form-control w-50 bg-secondary text-white rounded" readonly>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="final-amount" class="form-label">Payable
                                                                Amount</label>
                                                            <input type="text" id="final-amount" name="final-amount"
                                                                value="0" placeholder="Payable Amount"
                                                                class="form-control bg-secondary text-white rounded"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="advance" class="form-label">Advance</label>
                                                            <input type="text" id="advance" name="advance"
                                                                value="0" placeholder="Advance"
                                                                class="form-control rounded" oninput="totalCounter()">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="pending-amount" class="form-label">Pending
                                                                Amount</label>
                                                            <input type="text" id="pending-amount"
                                                                name="pending-amount" value="0"
                                                                placeholder="Pending Amount"
                                                                class="form-control bg-secondary text-white rounded"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5 text-end">
                                    <button type="submit" class="btn btn-success">Create Job Card</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--customer add model -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1">{{ trans('message.Customer Details') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="x_content">
                        <form id="formcustomer" method="POST" name="formcustomer" enctype="multipart/form-data"
                            data-parsley-validate class="form-horizontal form-label-left input_mask">

                            <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 space">
                                <h4><b>{{ trans('message.PERSONAL INFORMATION') }}</b></h4>
                                <p class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ln_solid"></p>
                            </div>
                            <div class="row mt-3">
                                <div
                                    class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                        for="first-name">{{ trans('message.First Name') }} <label
                                            class="color-danger">*</label> </label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="text" id="firstname" name="firstname" class="form-control"
                                            value="{{ old('firstname') }}"
                                            placeholder="{{ trans('message.Enter First Name') }}" maxlength="25"
                                            required />
                                        <span class="color-danger" id="errorlfirstname"></span>
                                    </div>
                                </div>

                                <div
                                    class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback {{ $errors->has('lastname') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                        for="last-name">{{ trans('message.Last Name') }} <label
                                            class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="text" id="lastname" name="lastname"
                                            placeholder="{{ trans('message.Enter Last Name') }}"
                                            value="{{ old('lastname') }}" maxlength="25" class="form-control" required>
                                        <span class="color-danger" id="errorllastname"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div
                                    class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                                        {{ trans('message.Gender') }}
                                        <label class="color-danger"></label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 gender">
                                        <input type="radio" class="gender" name="gender"
                                            value="0">{{ trans('message.Male') }}
                                        <input type="radio" class="gender" name="gender" value="1">
                                        {{ trans('message.Female') }}

                                    </div>
                                </div>
                                <div
                                    class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback {{ $errors->has('mobile') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                        for="mobile">{{ trans('message.Mobile No') }}. <label
                                            class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="text" id="mobile" name="mobile"
                                            placeholder="{{ trans('message.Enter Mobile No') }}"
                                            value="{{ old('mobile') }}" class="form-control" maxlength="16"
                                            minlength="6" required>
                                        <span class="color-danger" id="errorlmobile"></span>
                                    </div>
                                </div>

                            </div>
                            <div class="row mt-3">
                                <div
                                    class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                        for="Email">{{ trans('message.Email') }} <label
                                            class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="text" id="email" name="email"
                                            placeholder="{{ trans('message.Enter Email') }}" value="{{ old('email') }}"
                                            class="form-control" maxlength="50" required>
                                        <span class="color-danger" id="errorlemail"></span>
                                    </div>
                                </div>

                                <div
                                    class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                        for="Password">{{ trans('message.Password') }} <label
                                            class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="password" id="password" name="password"
                                            placeholder="{{ trans('message.Enter Password') }}"
                                            class="form-control col-md-7 col-xs-12" maxlength="20" required>
                                        <span class="color-danger" id="errorlpassword"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div
                                    class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                    <label
                                        class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 currency p-0 ps-2 px-5"
                                        for="Password">{{ trans('message.Confirm Password') }}
                                        <label class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            placeholder="{{ trans('message.Enter Confirm Password') }}"
                                            class="form-control col-md-7 col-xs-12" maxlength="20" required>
                                        <span class="color-danger" id="errorlpassword_confirmation"></span>
                                    </div>
                                </div>

                                <div
                                    class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group {{ $errors->has('dob') ? ' has-error' : '' }}">
                                    <label
                                        class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">{{ trans('message.Date of Birth') }}</label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 date ">
                                        <input type="text" id="datepicker" autocomplete="off"
                                            class="form-control datepickercustmore" placeholder="<?php echo getDatepicker(); ?>"
                                            name="dob" value="{{ old('dob') }}" onkeypress="return false;" />
                                    </div>
                                    <span class="color-danger" id="errorldatepicker"></span>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div
                                    class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback {{ $errors->has('displayname') ? ' has-error' : '' }} ">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                        for="display-name">{{ trans('message.Display Name') }}</label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="text" id="displayname" name="displayname"
                                            placeholder="{{ trans('message.Enter Display Name') }}"
                                            value="{{ old('displayname') }}" class="form-control" maxlength="25">
                                        <span class="color-danger" id="errorldisplayname"></span>
                                    </div>
                                </div>

                                <div
                                    class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback {{ $errors->has('company_name') ? ' has-error' : '' }} ">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 p-0"
                                        for="display-name">{{ trans('message.Company Name') }}</label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="text" id="company_name" name="company_name"
                                            placeholder="{{ trans('message.Enter Company Name') }}"
                                            value="{{ old('company_name') }}" class="form-control" maxlength="25">
                                        <span class="color-danger" id="errorlcompanyName"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div
                                    class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback {{ $errors->has('landlineno') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                        for="landline-no">{{ trans('message.Landline No') }}. </label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="text" id="landlineno" name="landlineno"
                                            placeholder="{{ trans('message.Enter LandLine No') }}"
                                            value="{{ old('landlineno') }}" class="form-control">
                                        <span class="color-danger" id="errorllandlineno"></span>
                                    </div>
                                </div>

                                <div
                                    class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                        for="image">
                                        {{ trans('message.Image') }} </label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="file" id="image" name="image" value="{{ old('image') }}"
                                            class="form-control chooseImage">

                                        <img src="{{ url('public/customer/avtar.png') }}" id="imagePreview"
                                            alt="User Image" class="datatable_img mt-2" style="width: 52px;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 space">
                                <h4><b>{{ trans('message.ADDRESS') }}</b></h4>
                                <p class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ln_solid"></p>
                            </div>
                            <div class="row mt-3">
                                <div
                                    class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                        for="Country">{{ trans('message.Country') }} <label
                                            class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <select class="form-control select_country form-select" id="country_id"
                                            name="country_id" countryurl="{!! url('/getstatefromcountry') !!}" required>
                                            <option value="">{{ trans('message.Select Country') }}</option>
                                            @foreach ($country as $countrys)
                                                <option value="{{ $countrys->id }}">{{ $countrys->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="color-danger" id="errorlcountry_id"></span>
                                    </div>
                                </div>

                                <div
                                    class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                        for="State ">{{ trans('message.State') }} </label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <select class="form-control state_of_country form-select" id="state_id"
                                            name="state_id" stateurl="{!! url('/getcityfromstate') !!}">
                                            <option value="">{{ trans('message.Select State') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div
                                    class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                        for="Town/City">{{ trans('message.Town/City') }}</label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <select class="form-control city_of_state form-select" id="city"
                                            name="city">
                                            <option value="">{{ trans('message.Select City') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div
                                    class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"
                                        for="Address">{{ trans('message.Address') }} <label
                                            class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <textarea class="form-control" id="address" name="address" maxlength="100" required>{{ old('address') }}</textarea>
                                        <span class="color-danger" id="errorladdress"></span>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                    <!-- <a class="btn btn-primary cancelcustomer" data-bs-dismiss="modal">{{ trans('message.CANCEL') }}</a> -->
                                    <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 my-1 mx-0">
                                        <button type="submit"
                                            class="btn btn-success addcustomer">{{ trans('message.SUBMIT') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm mx-1"
                        data-bs-dismiss="modal">{{ trans('message.Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <script>
        $(document).ready(function() {

            $('.select2').select2();

            $(".datepickercustmore").datetimepicker({
                format: "<?php echo getDatepicker(); ?>",
                minDate: new Date(),
                todayBtn: true,
                autoclose: 1,
                minView: 2,
                language: "{{ getLangCode() }}",
            });

            getJobCardLabel();

            $('body').on('change', '.chooseImage', function() {
                var imageName = $(this).val();
                var imageExtension = /(\.jpg|\.jpeg|\.png)$/i;

                if (imageExtension.test(imageName)) {
                    $('.imageHideShow').css({
                        "display": ""
                    });
                } else {
                    $('.imageHideShow').css({
                        "display": "none"
                    });
                }
            });

            $('.select_country').change(function() {
                countryid = $(this).val();
                var url = $(this).attr('countryurl');
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {
                        countryid: countryid
                    },
                    success: function(response) {
                        $('.state_of_country').html(response);
                    }
                });
            });


            $('body').on('change', '.state_of_country', function() {
                stateid = $(this).val();

                var url = $(this).attr('stateurl');
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {
                        stateid: stateid
                    },
                    success: function(response) {
                        $('.city_of_state').html(response);
                    }
                });
            });

            /*For image preview at selected image*/
            function readUrl(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#imagePreview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $("#image").change(function() {
                readUrl(this);
                $("#imagePreview").css("display", "block");
            });

            $("#formcustomer").on('submit', (function(event) {

                function define_variable() {
                    return {
                        firstname: $("#firstname").val(),
                        lastname: $("#lastname").val(),
                        displayname: $("#displayname").val(),
                        company_name: $("#company_name").val(),
                        email: $("#email").val(),
                        password: $("#password").val(),
                        password_confirmation: $("#password_confirmation").val(),
                        mobile: $("#mobile").val(),
                        landlineno: $("#landlineno").val(),
                        image: $("#image").val(),
                        country_id: $("#country_id option:selected").val(),
                        state_id: $("#state_id option:selected").val(),
                        city: $("#city option:selected").val(),
                        address: $("#address").val(),
                        name_pattern: /^[a-zA-Z\u0621-\u064A\u00C0-\u017F\u0600-\u06FF\u0750-\u077F\uFB50-\uFDFF\uFE70-\uFEFF\u2E80-\u2FD5\u3190-\u319f\u3400-\u4DBF\u4E00-\u9FCC\uF900-\uFAAD\u0900-\u097F\s]+$/,
                        name_pattern2: /^[a-zA-Z\u0621-\u064A\u00C0-\u017F\u0600-\u06FF\u0750-\u077F\uFB50-\uFDFF\uFE70-\uFEFF\u2E80-\u2FD5\u3190-\u319f\u3400-\u4DBF\u4E00-\u9FCC\uF900-\uFAAD\u0900-\u097F\s]*$/,
                        company_patt: /^[a-zA-Z0-9\u0621-\u064A\u00C0-\u017F\u0600-\u06FF\u0750-\u077F\uFB50-\uFDFF\uFE70-\uFEFF\u2E80-\u2FD5\u3190-\u319f\u3400-\u4DBF\u4E00-\u9FCC\uF900-\uFAAD\u0900-\u097F\s]*$/,
                        lenghtLimit: /^[0-9]{6,16}$/,
                        mobile_pattern: /^[- +()]*[0-9][- +()0-9]*$/,
                        email_pattern: /^([a-zA-Z0-9_\.\-\+\'])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/
                    };
                }

                event.preventDefault();
                var call_var_customeradd = define_variable();
                var errro_msg = [];
                //first name
                if (call_var_customeradd.firstname == "") {
                    var msg = "{{ trans('message.First name is required.') }}";
                    $('#errorlfirstname').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else {
                    $('#errorlfirstname').html("");
                    errro_msg = [];
                }

                if (!call_var_customeradd.name_pattern.test(call_var_customeradd.firstname)) {
                    var msg = "{{ trans('message.First name is only alphabets and space.') }}";
                    $("#firstname").val("");
                    $('#errorlfirstname').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else {
                    $('#errorlfirstname').html("");
                    errro_msg = [];
                }

                if (!call_var_customeradd.firstname.replace(/\s/g, '').length) {

                    var msg = "{{ trans('message.Only blank space not allowed') }}";
                    $("#firstname").val("");
                    $('#errorlfirstname').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else {
                    $('#errorlfirstname').html("");
                    errro_msg = [];
                }

                if (!call_var_customeradd.name_pattern2.test(call_var_customeradd.firstname)) {
                    var msg = "{{ trans('message.At first position only alphabets are allowed.') }}";
                    $("#firstname").val("");
                    $('#errorlfirstname').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else {
                    $('#errorlfirstname').html("");
                    errro_msg = [];
                }

                //last name
                if (call_var_customeradd.lastname == "") {
                    var msg = "{{ trans('message.Last name is required.') }}";
                    $('#errorllastname').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else {
                    $('#errorllastname').html("");
                    errro_msg = [];
                }
                if (!call_var_customeradd.name_pattern.test(call_var_customeradd.lastname)) {
                    var msg = "{{ trans('message.Last name is only alphabets and space.') }}";
                    $('#errorllastname').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else {
                    $('#errorllastname').html("");
                    errro_msg = [];
                }

                if (!call_var_customeradd.lastname.replace(/\s/g, '').length) {

                    var msg = "{{ trans('message.Only blank space not allowed') }}";
                    $("#lastname").val("");
                    $('#errorllastname').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else {
                    $('#errorllastname').html("");
                    errro_msg = [];
                }

                if (!call_var_customeradd.name_pattern2.test(call_var_customeradd.lastname)) {
                    var msg = "{{ trans('message.At first position only alphabets are allowed.') }}";
                    $('#errorllastname').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else {
                    $('#errorllastname').html("");
                    errro_msg = [];
                }
                //Display name
                if (call_var_customeradd.displayname != "") {

                    if (!call_var_customeradd.name_pattern.test(call_var_customeradd.displayname)) {
                        var msg = "{{ trans('message.Display name is only alphabets and space.') }}";
                        $("#displayname").val("");
                        $('#errorldisplayname').html(msg);
                        errro_msg.push(msg);
                        return false;
                    } else if (!call_var_customeradd.displayname.replace(/\s/g, '').length) {

                        var msg = "{{ trans('message.Only blank space not allowed') }}";
                        $("#displayname").val("");
                        $('#errorldisplayname').html(msg);
                        errro_msg.push(msg);
                        return false;
                    } else if (!call_var_customeradd.name_pattern2.test(call_var_customeradd
                            .displayname)) {
                        var msg =
                            "{{ trans('message.At first position only alphabets are allowed.') }}";
                        $("#displayname").val("");
                        $('#errorldisplayname').html(msg);
                        errro_msg.push(msg);
                        return false;
                    } else {
                        $('#errorldisplayname').html("");
                        errro_msg = [];
                    }
                } else {
                    $('#errorldisplayname').html("");
                    errro_msg = [];
                }

                //Company name
                if (call_var_customeradd.company_name != "") {

                    if (!call_var_customeradd.company_name.replace(/\s/g, '').length) {

                        var msg = "{{ trans('message.Only blank space not allowed') }}";
                        $("#company_name").val("");
                        $('#errorlcompanyName').html(msg);
                        errro_msg.push(msg);
                        return false;
                    } else if (!call_var_customeradd.company_patt.test(call_var_customeradd
                            .company_name)) {
                        var msg =
                            "{{ trans('message.Only alphanumeric, space, dot, @, _, and - are allowed.') }}";
                        $("#company_name").val("");
                        $('#errorlcompanyName').html(msg);
                        errro_msg.push(msg);
                        return false;
                    } else if (!call_var_customeradd.name_pattern2.test(call_var_customeradd
                            .company_name)) {
                        var msg =
                            "{{ trans('message.At first position only alphabets are allowed.') }}";
                        $("#company_name").val("");
                        $('#errorlcompanyName').html(msg);
                        errro_msg.push(msg);
                        return false;
                    } else {
                        $('#errorlcompanyName').html("");
                        errro_msg = [];
                    }
                } else {
                    $('#errorlcompanyName').html("");
                    errro_msg = [];
                }
                //Email 
                if (call_var_customeradd.email == "") {
                    var msg = "{{ trans('message.Email is required.') }}";
                    $('#errorlemail').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else {
                    $('#errorlemail').html("");
                    errro_msg = [];
                }

                if (!call_var_customeradd.email.replace(/\s/g, '').length) {

                    var msg = "{{ trans('message.Only blank space not allowed') }}";
                    $("#email").val("");
                    $('#errorlemail').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else {
                    $('#errorlfirstname').html("");
                    errro_msg = [];
                }

                if (!call_var_customeradd.email_pattern.test(call_var_customeradd.email)) {
                    var msg =
                        "{{ trans('message.Please enter a valid email address. Like : sales@mojoomla.com') }}";
                    $('#errorlemail').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else {
                    $('#errorlemail').html("");
                    errro_msg = [];
                }

                //Password 
                if (call_var_customeradd.password == "") {
                    var msg = "{{ trans('message.Password is required.') }}";
                    $('#errorlpassword').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else {
                    $('#errorlpassword').html("");
                    errro_msg = [];
                }
                //Confirm Password 
                if (call_var_customeradd.password_confirmation == "") {
                    var msg = "{{ trans('message.Confirm password is required.') }}";
                    $('#errorlpassword_confirmation').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else {
                    $('#errorlpassword_confirmation').html("");
                    errro_msg = [];
                }

                //same Password and password_confirmation  
                if (call_var_customeradd.password != call_var_customeradd.password_confirmation) {
                    var msg = "{{ trans('message.Password and Confirm Password does not match.') }}";
                    $('#errorlpassword_confirmation').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else {
                    $('#errorlpassword').html("");
                    errro_msg = [];
                }

                //Mobile number 
                if (call_var_customeradd.mobile == "") {
                    var msg = "{{ trans('message.Contact number is required.') }}";
                    $('#errorlmobile').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else {
                    $('#errorlmobile').html("");
                    errro_msg = [];
                }
                if (!call_var_customeradd.mobile_pattern.test(call_var_customeradd.mobile)) {
                    var msg =
                        "{{ trans('message.Contact number must be number, plus, minus and space only.') }}";
                    $("#mobile").val("");
                    $('#errorlmobile').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else {
                    $('#errorlmobile').html("");
                    errro_msg = [];
                }

                if (!call_var_customeradd.mobile.replace(/\s/g, '').length) {

                    var msg = "{{ trans('message.Only blank space not allowed') }}";
                    $("#mobile").val("");
                    $('#errorlmobile').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else {
                    $('#errorlmobile').html("");
                    errro_msg = [];
                }

                //LandLine number
                if (call_var_customeradd.landlineno != "") {
                    if (!call_var_customeradd.mobile_pattern.test(call_var_customeradd.landlineno)) {
                        var msg =
                            "{{ trans('message.Landline number must be number, plus, minus and space only.') }}";
                        $("#landlineno").val("");
                        $('#errorllandlineno').html(msg);
                        errro_msg.push(msg);
                        return false;
                    } else if (!call_var_customeradd.lenghtLimit.test(call_var_customeradd
                            .landlineno)) {
                        var msg = "{{ trans('message.Landline number between 6 to 16 digits only') }}";
                        $("#landlineno").val("");
                        $('#errorllandlineno').html(msg);
                        errro_msg.push(msg);
                        return false;
                    } else if (!call_var_customeradd.landlineno.replace(/\s/g, '').length) {

                        var msg = "{{ trans('message.Only blank space not allowed') }}";
                        $("#landlineno").val("");
                        $('#errorllandlineno').html(msg);
                        errro_msg.push(msg);
                        return false;
                    } else {
                        $('#errorllandlineno').html("");
                        errro_msg = [];
                    }
                } else {
                    $('#errorllandlineno').html("");
                    errro_msg = [];
                }

                //Country 
                if (call_var_customeradd.country_id == "") {
                    var msg = "{{ trans('message.Country field is required.') }}";
                    $('#errorlcountry_id').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else {
                    $('#errorlcountry_id').html("");
                    errro_msg = [];
                }
                //Address 
                if (call_var_customeradd.address == "") {
                    var msg = "{{ trans('message.Address field is required.') }}";
                    $('#errorladdress').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else {
                    $('#errorladdress').html("");
                    errro_msg = [];
                }

                if (!call_var_customeradd.address.replace(/\s/g, '').length) {

                    var msg = "{{ trans('message.Only blank space not allowed') }}";
                    $("#address").val("");
                    $('#errorladdress').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else {
                    $('#errorladdress').html("");
                    errro_msg = [];
                }

                if (errro_msg == "") {
                    var firstname = $('#firstname').val();
                    var lastname = $('#lastname').val();
                    var displayname = $('#displayname').val();
                    var company_name = $('#company_name').val();
                    var gender = $(".gender:checked").val();
                    var dob = $("#datepicker").val();
                    var email = $("#email").val();
                    var password = $("#password").val();
                    var mobile = $("#mobile").val();
                    var landlineno = $("#landlineno").val();
                    var image = $("#image").val();
                    var country_id = $("#country_id option:selected").val();
                    var state_id = $("#state_id option:selected").val();
                    var city = $("#city option:selected").val();
                    var address = $("#address").val();

                    $.ajax({
                        type: 'POST',
                        url: '{!! url('service/customeradd') !!}',
                        data: new FormData(this),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        },
                        contentType: false,
                        cache: false,
                        processData: false,

                        success: function(data) {
                            $('.select_vhi').append('<option selected value=' + data[
                                    'customerId'] +
                                '>' + data[
                                    'customer_fullname'] + '</option>');

                            $('#customer_name').append('<option selected value=' + data[
                                    'customerId'] +
                                '>' + data[
                                    'customer_fullname'] + '</option>');

                            var firstname = $('#firstname').val('');
                            var lastname = $('#lastname').val('');
                            var displayname = $('#displayname').val('');
                            var gender = $(".gender:checked").val('');
                            var dob = $("#datepicker").val('');
                            var email = $("#email").val('');
                            var password = $("#password").val('');
                            var mobile = $("#mobile").val('');
                            var landlineno = $("#landlineno").val('');
                            var image = $("#image").val('');
                            var country_id = $("#country_id option:selected").val('');
                            var state_id = $("#state_id option:selected").val('');
                            var city = $("#city option:selected").val('');
                            var address = $("#address").val('');
                            var company_name = $("#company_name").val('');
                            $(".addcustomermsg").removeClass("hide");

                            $('.hidden_customer_id').val(data['customerId']);

                            // modal close after add data
                            $('#myModal').modal('toggle');
                            $('.select2').select2();

                        },
                        error: function(e) {
                            alert(msg100 + " " + e.responseText);
                            console.log(e);
                        }
                    });
                }
            }));

        });

        function submitJobcard(e) {

            let url = $(e).attr('action');
            let formData = $(e).serialize();

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.status === 1) {
                        toastr.success('Validation Passed and Data Submitted Successfully!');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;

                        let errorMessage = '<ul>';
                        for (const field in errors) {
                            if (errors.hasOwnProperty(field)) {
                                errorMessage +=
                                    `<li>${errors[field][0]}</li>`;
                            }
                        }
                        errorMessage += '</ul>';

                        toastr.error(errorMessage, 'Validation Errors', {
                            timeOut: 5000,
                            closeButton: true,
                            progressBar: true,
                            escapeHtml: false,
                        });
                    } else {
                        toastr.error('An unexpected error occurred. Please try again.');
                    }
                }
            });
        }

        function select2function(id) {
            $('#' + id).select2({
                escapeMarkup: function(markup) {
                    return markup;
                },
                templateResult: formatOption,
                templateSelection: formatSelection
            });
        }

        function formatOption(option) {
            if (!option.id) {
                return option.text;
            }

            const labelName = `<b> ${$(option.element).data('label-name')}</b>`;
            const stock = ($(option.element).data('stock') == 0) ?
                ` (Current Qty: <span class=text-danger><b>${$(option.element).data('stock')}</b></span>),` :
                `<span>(Current Qty: ${$(option.element).data('stock')})</span>,`;
            const price = ($(option.element).data('price') == 0) ?
                ` (Price : <span class=text-danger><b>${$(option.element).data('price')}</b></span>),` :
                `<span>(Price : ${$(option.element).data('price')})</span>`;

            return `<div>${labelName}${stock}${price}</div>`;
        }

        function formatSelection(option) {
            return $(option.element).data('label-name') || option.text;
        }

        function submitFormAjax(event, formId) {
            event.preventDefault();

            let form = $('#' + formId);
            let url = form.attr('action');
            let formData = form.serialize();

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                success: function(response) {

                    toastr.clear();

                    if (response.status == 'success') {
                        toastr.success(response.message, 'Success');
                    }

                    // Hide modal and clear modal body
                    $("#bs-example-modal-xl").modal("hide");
                    $(".modal-body-data").html('');
                },
                error: function(xhr) {
                    toastr.clear();

                    let errorMessages = '';
                    let errors = xhr.responseJSON?.errors || {}; // Handle error response
                    for (let field in errors) {
                        if (errors.hasOwnProperty(field)) {
                            let messages = errors[field];
                            messages.forEach(function(message) {
                                errorMessages += message + '<br>';
                            });
                        }
                    }
                    toastr.error(errorMessages, 'Validation Errors');
                }
            });
        }

        function addJobCardRow(e, id) {

            let row = $('#' + id + ' tbody tr').length + 1;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('jobcard.spare_part.addRow') }}",
                method: "post",
                data: {
                    row: row,
                    id: $(e).val(),
                    price: $(e).find('option:selected').attr('data-price')
                },
                success: function(res) {
                    if (res.status == 1) {
                        $('#' + id + ' tbody').append(res.html);
                        $(e).val('');
                        select2function($(e).attr('id'));
                        tableCounter($(e).parents('table').attr('id'));
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

        function getJobCardLabel() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('jobcard.stock.label') }}",
                method: "post",
                data: {},
                success: function(res) {
                    if (res.status == 1) {
                        $('#spare-parts-dropdown').html(res.spare);
                        $('#lubes-dropdown').html(res.lube);
                        $('#tools-dropdown').html(res.tool);
                        $('#accessory-dropdown').html(res.accessory);
                        // $('.select2').select2();
                        select2function('spare-parts-dropdown');
                        select2function('lubes-dropdown');
                        select2function('tools-dropdown');
                        select2function('accessory-dropdown');
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

        function getJobCardPrice(e) {
            let row = $(e).parents('tr').attr('data-row');
            const table = $(e).parents('table');

            let qty = table.find('input[name="jobcard_quantity[]"]').eq(row - 1).val();
            let price = table.find('input[name="jobcard_price[]"]').eq(row - 1).val();
            let discount = table.find('input[name="jobcard_discount[]"]').eq(row - 1).val();

            let totalAmount = parseInt(qty) * parseInt(price);
            let finalAmount = totalAmount - (parseInt(discount) || 0);

            table.find('input[name="jobcard_total_amount[]"]').eq(row - 1).val(totalAmount);
            table.find('input[name="jobcard_final_amount[]"]').eq(row - 1).val(finalAmount);

            tableCounter(table.attr('id'));
        }

        function removeJobCardRow(e) {
            const table = $(e).parents('table');
            $(e).parents('tr').remove();

            let i = 1;
            $('#' + table.attr('id') + ' tbody tr').each(function() {
                $(this).attr('data-row', i++);
            });

            tableCounter(table.attr('id'));
        }

        function tableCounter(id) {
            let trcount = 0;
            let total = 0;
            let discount = 0;
            let finalTotal = 0;

            $('#' + id + ' tbody input[name="jobcard_total_amount[]"]').each(function() {
                total += parseFloat($(this).val()) || 0;
                trcount++;
            });

            $('#' + id + ' tbody input[name="jobcard_discount[]"]').each(function() {
                discount += parseFloat($(this).val()) || 0;
            });

            $('#' + id + ' tbody input[name="jobcard_final_amount[]"]').each(function() {
                finalTotal += parseFloat($(this).val()) || 0;
            });

            $('#' + id + '-counter span.tr-count').text(trcount);
            $('#' + id + '-counter span.total-count').text(total);
            $('#' + id + '-counter span.discount-count').text(discount);
            $('#' + id + '-counter span.final-total-count').text(finalTotal);

            totalCounter();
        }

        function totalCounter() {
            let trcount = 0;
            let total = 0;
            let discount = 0;
            let finalTotal = 0;

            $('input[name="jobcard_total_amount[]"]').each(function() {
                total += parseFloat($(this).val()) || 0;
                trcount++;
            });

            $('input[name="jobcard_discount[]"]').each(function() {
                discount += parseFloat($(this).val()) || 0;
            });

            $('input[name="jobcard_final_amount[]"]').each(function() {
                finalTotal += parseFloat($(this).val()) || 0;
            });

            $('#total-counter span.tr-count').text(trcount);
            $('#total-counter span.total-count').text(total);
            $('#total-counter span.discount-count').text(discount);
            $('#total-counter span.final-total-count').text(finalTotal);

            $('#cost-estimate').val(total);
            $('#total-amount').val(total);
            $('#final-amount').val(finalTotal);

            let pendingAmount = parseFloat(finalTotal) - parseFloat($('#advance').val());

            $('#pending-amount').val(pendingAmount);

        }

        function addForm(e) {
            var contentUrl = "{{ route('purchase_spare_part.create') }}";
            $.ajax({
                type: "GET",
                url: contentUrl,
                success: function(data) {
                    $(".modal-body-data").html(data);
                    $("#bs-example-modal-xl").modal("show");
                },
                error: function() {
                    alert("Failed to load content.");
                }
            });
        }

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

            let row = $('#create-order-sparepart tbody tr').length + 1;

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
                        $('#create-order-sparepart tbody').append(res.html);
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
            $('#create-order-sparepart tbody tr').each(function() {
                $(this).attr('data-row', $i++);
            });
            totalAmount();
        }

        function viewTable(e) {
            $('.table-view').hide();
            let id = $(e).attr('target-table');
            $('#' + id).show();

            select2function('spare-parts-dropdown');
            select2function('lubes-dropdown');
            select2function('tools-dropdown');
            select2function('accessory-dropdown');
        }


        function addDentMark(e) {
             var jobcard_number = $('#jobcard_number').val();
    var contentUrl = "{{route('newjobcard.addDentMark')}}";
    $.ajax({
        type: "GET",
        url: contentUrl,
         data: {
            jobcard_number: jobcard_number
        },
        success: function(data) {  
            $(".modal-body-data").html(data);
            $("#bs-example-modal-lg").modal("show");
        },
        error: function() {
            alert("Failed to load content.");
        }
    });
}



        function customerVoice(e) {
    var jobcard_number = $('#jobcard_number').val();
    var contentUrl = "{{route('newjobcard.customerVoice')}}";
    $.ajax({
        type: "GET",
        url: contentUrl,
          data: {
            jobcard_number: jobcard_number
        },
        success: function(data) {  
            $(".modal-body-data").html(data);
            $("#bs-example-modal-xl").modal("show");

            $('.select2-name').select2({
                dropdownParent: $('.custommodal-xl'),
            });
        },
        error: function() {
            alert("Failed to load content.");
        }
    });
}

        function workNotes(e) {
    var contentUrl = "{{route('newjobcard.workNotes')}}";
    $.ajax({
        type: "GET",
        url: contentUrl,
        success: function(data) {  
            $(".modal-body-data").html(data);
            $("#bs-example-modal-xl").modal("show");
            
        },
        error: function() {
            alert("Failed to load content.");
        }
    });
}

        function accessories(e) {
    var contentUrl = "{{route('newjobcard.accessories')}}";
    $.ajax({
        type: "GET",
        url: contentUrl,
        success: function(data) {  
            $(".modal-body-data").html(data);
            $("#bs-example-modal-xl").modal("show");
        },
        error: function() {
            alert("Failed to load content.");
        }
    });
}



function addPhoto(e) {
    var jobcard_number = $('#jobcard_number').val();
    var contentUrl = "{{ route('newjobcard.addphoto') }}";
 
    $.ajax({
        type: "GET",
        url: contentUrl,
        data: {
            jobcard_number: jobcard_number
        },
        success: function(data) {  
            $(".modal-body-data").html(data);
            $("#bs-example-modal-xl").modal("show");
        },
        error: function(xhr, status, error) {
            // Use a Toastr notification for better UX
            toastr.error("Failed to load content: " + error);
        }
    });
}


   function form_submit_images(e) {

      $(e).find('.st_loader').show();
      $.ajax({
         url: $(e).attr('action'),
         method: "POST",
         dataType: "json",
         data: $(e).serialize(),
         success: function(data) {

            if (data.success == 1) {
               toastr.success(data.message, 'Success'); 
            $("#bs-example-modal-xl").modal("hide");
               dataTable.draw(false); 

            }else if (data.success == 0) {
               toastr.error(data.message, 'Error');
               $(e).find('.st_loader').hide(); 
            }
         },
         error: function(data) {
            if (typeof data.responseJSON.status !== 'undefined') {
               toastr.error(data.responseJSON.error, 'Error');
            } else {
               $.each(data.responseJSON.errors, function(key, value) {
                  toastr.error(value, 'Error');
               });
            }
            $(e).find('.st_loader').hide();
         }
      });
   }

 
    </script>
@endsection
