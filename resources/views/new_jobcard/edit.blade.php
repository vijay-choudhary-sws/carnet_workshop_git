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
        #accessory-table th,
        #accessory-table .add-spare-btn,
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

        #labour-table th,
        #labour-table .add-spare-btn,
        .bg-yellow-orange {
            background: #A52A2A !important;
            color: #fff !important;
        }

        .table-view table th {
            text-wrap: nowrap !important;
        }


        #counters div.card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        #counters div.card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .footer {
            position: sticky;
            bottom: 0;
            background-color: #f8f9fa;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #ccc;
        }

        /* Full-screen overlay */
        #loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.652);
            /* Light background with transparency */
            z-index: 9999;
            /* Ensure it overlays everything */
            display: flex;
            justify-content: center;
            align-items: center;
            visibility: hidden;
            /* Hidden by default */
        }

        /* Spinner style */
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(0, 0, 0, 0.1);
            border-top: 5px solid #3498db;
            /* Spinner color */
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        /* Spinner animation */
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>
    <div id="loader">
        <div class="spinner"></div>
    </div>
    <div class="right_col " role="main">
        <div class="">
            <div class="page-title">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
                            <a href="{{ route('newjobcard.list') }}" id=""><span class="titleup"><img
                                        src="{{ URL::asset('public/supplier/Back Arrow.png') }}" class="back-arrow">Edit
                                    JobCard</span></a>
                        </div>
                        @include('dashboard.profile')
                        <div class="ulprofile">
                            <div class="input-group mt-2">
                                <span class="input-group-text">JobCard No.</span>
                                <input type="text" form="jobcard-form" id="jobcard_number" name="jobcard_number"
                                    class="form-control bg-light" value="{{ $jobcard->jobcard_number ?? '' }}" readonly
                                    placeholder="Job Card Number" aria-label="Job Card Number">
                                <span class="input-group-text">Date</span>
                                <input type="date" class="form-control bg-light"
                                    value="{{ \carbon\carbon::parse($jobcard->entry_date)->format('Y-m-d') ?? '' }}"
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
                            <form id="jobcard-form" action="{{ route('newjobcard.update') }}" method="POST"
                                onsubmit="event.preventDefault();submitJobcard(this);">
                                @csrf
                                <input type="hidden" name="id" value="{{ $jobcard->id }}">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h6 class="text-center m-0">
                                                    <b>Search or Create New User</b>
                                                </h6>
                                                <hr>

                                                <label for="customer-dropdown" class="form-label">Customer Name</label>
                                                <div class="input-group mb-3">
                                                    <select name="customer_name" id="customer-dropdown"
                                                        class="form-control @if ($jobcard->status == 0) select2 @endif"
                                                        style="width: 75%;" onchange="getVehicle(this)" @readonly($jobcard->status != 0)>
                                                        <option value="{{ $jobcard->customer_id }}" selected>
                                                            {{ $jobcard->customer_name ?? '' }}</option>
                                                    </select>
                                                    @if ($jobcard->status == 0)
                                                        <button type="button" data-bs-toggle="modal"
                                                            data-bs-target="#myModal"
                                                            class="btn btn-outline-secondary input-group-text fl margin-left-0">{{ trans('+') }}</button>
                                                    @endif
                                                </div>

                                                <label for="vehicle-id" class="form-label">Vehical Name</label>
                                                <div class="input-group mb-3">
                                                    <select name="vehicle_id" id="vehicle-id"
                                                        class="form-control @if ($jobcard->status == 0) select2 @endif modelnameappend"
                                                        style="width: 75% !important;" @readonly($jobcard->status != 0)>
                                                        <option value="{{ $jobcard->vehicle_id }}" selected>
                                                            {{ $jobcard->vehical_number ?? '' }}</option>
                                                    </select>
                                                    @if ($jobcard->status == 0)
                                                        <button type="button" data-bs-toggle="modal"
                                                            data-bs-target="#vehiclemymodel"
                                                            class="btn btn-outline-secondary  input-group-text vehiclemodel">{{ trans('+') }}
                                                        </button>
                                                    @endif
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
                                                                id="km_reading" value="{{ $jobcard->km_runing }}"
                                                                min="1" @readonly($jobcard->status != 0)>
                                                            <label for="km_reading">KM Reading</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <label for="fual level" class="form-label">Fual Level</label>
                                                        <input type="range" class="form-range" id="fual level"
                                                            @if ($jobcard->status == 0) name="fual_level" @endif
                                                            min="0" max="100"
                                                            value="{{ $jobcard->fual_level }}"
                                                            @disabled($jobcard->status != 0)>
                                                        @if ($jobcard->status != 0)
                                                            <input type="hidden" name="fual_level"
                                                                value="{{ $jobcard->fual_level }}">
                                                        @endif
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="form-floating">
                                                            <select class="form-select" id="supervisor" name="supervisor"
                                                                aria-label="Floating label example" required
                                                                @disabled($jobcard->status != 0)>
                                                                @foreach ($supervisors as $supervisor)
                                                                    <option value="{{ $supervisor->id }}"
                                                                        @selected($supervisor->id == $jobcard->supervisor_id)>
                                                                        {{ getUserFullName($supervisor->id) }}</option>
                                                                @endforeach
                                                            </select>
                                                            @if ($jobcard->status != 0)
                                                                <input type="hidden" name="supervisor"
                                                                    value="{{ $jobcard->supervisor_id }}">
                                                            @endif
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
                                                        <p class="position-absolute list-count rounded-circle bg-primary text-white  customerVoiceCount"
                                                            style="width: 10px !important;height:10px !important;">
                                                            @if (!empty($jobCardscustomervoice))
                                                                {{ $jobCardscustomervoice->count() }}
                                                            @else
                                                                0
                                                            @endif
                                                        </p>
                                                    </a>
                                                    <a href="javascript:void(0)" onclick=addDentMark(this);return;false;
                                                        class="text-center p-2 position-relative d-flex flex-column justify-content-between">
                                                        <img src="{{ asset('public/assets/jobcard_img/dent.jpg') }}"
                                                            alt="image" width="80">
                                                        <p class="my-2">Dent Marks</p>
                                                        <p class="position-absolute list-count rounded-circle bg-primary text-white"
                                                            style="width: 10px !important;height:10px !important;">
                                                            @if (!empty($jobCardsDentMark))
                                                                {{ $jobCardsDentMark->count() }}
                                                            @else
                                                                0
                                                            @endif
                                                        </p>
                                                    </a>
                                                    <a href="javascript:void(0)" onclick=addPhoto(this);return;false;
                                                        class="text-center p-2 position-relative d-flex flex-column justify-content-between">
                                                        <img src="{{ asset('public/assets/jobcard_img/photo.jpg') }}"
                                                            alt="image" width="80">
                                                        <p class="my-2">Photos</p>
                                                        <p class="position-absolute list-count rounded-circle bg-primary text-white jobcardImage"
                                                            style="width: 10px !important;height:10px !important;">
                                                            @if (!empty($jobCardsImage))
                                                                {{ count(explode(',', $jobCardsImage)) }}
                                                            @else
                                                                0
                                                            @endif
                                                        </p>
                                                    </a>
                                                    <a href="javascript:void(0)" onclick=accessories(this);return;false;
                                                        class="text-center p-2 position-relative d-flex flex-column justify-content-between">
                                                        <img src="{{ asset('public/assets/jobcard_img/accessory.jpg') }}"
                                                            alt="image" width="80">
                                                        <p class="my-2">Accessories</p>
                                                        <p class="accessaryCount position-absolute list-count rounded-circle bg-primary text-white"
                                                            style="width: 10px !important;height:10px !important;">
                                                            @if (!empty($jobCardsaccessary))
                                                                {{ $jobCardsaccessary->count() }}
                                                            @else
                                                                0
                                                            @endif
                                                        </p>
                                                    </a>
                                                    <a href="javascript:void(0)" onclick=workNotes(this);return;false;
                                                        class="text-center p-2 position-relative d-flex flex-column justify-content-between">
                                                        <img src="{{ asset('public/assets/jobcard_img/work_note.jpg') }}"
                                                            alt="image" width="80">
                                                        <p class="my-2">Work Note</p>
                                                        <p class="workNoteCount position-absolute list-count rounded-circle bg-primary text-white worknotecount"
                                                            style="width: 10px !important;height:10px !important;">
                                                            @if (!empty($jobCardsworknote))
                                                                {{ $jobCardsworknote->count() }}
                                                            @else
                                                                0
                                                            @endif
                                                        </p>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4" id="counters">
                                    <div class="col-2">
                                        <div class="card">
                                            <div class="card-body p-0" id="labour-table-counter">
                                                <a href="javascript:void(0)"
                                                    class="d-flex justify-content-between bg-yellow-orange px-1 py-2 rounded-top text-white"
                                                    onclick="viewTable(this);" target-table="labour-div">
                                                    <p class="m-0"><b>Labour(<span class="tr-count">0</span>)</b></p>
                                                    <p class="text-end m-0">
                                                        <b class="">₹<span class="total-count">0.00</span></b>
                                                        <small class="d-block ">₹<span
                                                                class="discount-count">0.00</span></small>
                                                    </p>
                                                </a>
                                                <div class="d-flex justify-content-between align-items-center p-1 ps-0 ">
                                                    <div class="fs-5 bg-yellow-orange rounded mx-1 px-2"><i
                                                            class="fa-solid fa-user text-white"></i></div>
                                                    <div class="text-end">
                                                        <p class="m-0 ">Total: ₹<span
                                                                class="final-total-count">0.00</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                                                <div class="d-flex justify-content-between align-items-center p-1 ps-0 ">
                                                    <div class="fs-5 green-variant rounded mx-1 px-2"><i
                                                            class="fa-solid fa-cogs text-white"></i></div>
                                                    <div class="text-end">
                                                        <p class="m-0 ">Total: ₹<span
                                                                class="final-total-count">0.00</span></p>
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
                                                <div class="d-flex justify-content-between align-items-center p-1 ps-0 ">
                                                    <div class="fs-5 blue-variant rounded mx-1 px-2"><i
                                                            class="fa-solid fa-oil-can text-white"></i></div>
                                                    <div class="text-end">
                                                        <p class="m-0 ">Total: ₹<span
                                                                class="final-total-count">0.00</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-2">
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
                                                <div class="d-flex justify-content-between align-items-center p-1 ps-0 ">
                                                    <div class="fs-5 orange-variant rounded mx-1 px-2"><i
                                                            class="fa-solid fa-wrench text-white"></i></div>
                                                    <div class="text-end">
                                                        <p class="m-0 ">Total: ₹<span
                                                                class="final-total-count">0.00</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="col-2">
                                        <div class="card">
                                            <div class="card-body p-0" id="accessory-table-counter">
                                                <a href="javascript:void(0)"
                                                    class="d-flex justify-content-between orange-variant px-1 py-2 rounded-top text-white"
                                                    onclick="viewTable(this);" target-table="accessory-table">
                                                    <p class="m-0"><b>Accessory(<span class="tr-count">0</span>)</b>
                                                    </p>
                                                    <p class="text-end m-0">
                                                        <b class="">₹<span class="total-count">0.00</span></b>
                                                        <small class="d-block ">₹<span
                                                                class="discount-count">0.00</span></small>
                                                    </p>
                                                </a>
                                                <div class="d-flex justify-content-between align-items-center p-1 ps-0 ">
                                                    <div class="fs-5 orange-variant rounded mx-1 px-2"><i
                                                            class="fa-solid fa-toolbox text-white"></i></div>
                                                    <div class="text-end">
                                                        <p class="m-0 ">Total: ₹<span
                                                                class="final-total-count">0.00</span></p>
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
                                                <div class="d-flex justify-content-between align-items-center p-1 ps-0 ">
                                                    <div class="fs-5 grey-variant rounded mx-1 px-2"><i
                                                            class="fa-solid fa-calculator text-white"></i></div>
                                                    <div class="text-end">
                                                        <p class="m-0 ">Total: ₹<span
                                                                class="final-total-count">0.00</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 table-view" id="labour-div" style="display: none;">
                                    <table class="table table-bordered" id="labour-table">
                                        <thead>
                                            <th>Label</th>
                                            <th>Amount</th>
                                            <th>Assign Machanic</th>
                                            <th>Action</th>
                                        </thead>
                                        <tbody>
                                            @if (count($jobCardExtraCharges) > 0)
                                                @foreach ($jobCardExtraCharges as $jobCardExtraCharge)
                                                    <tr>
                                                        <td>
                                                            <input type="text" class="form-control" name="label[]"
                                                                placeholder="Enter Label Here"
                                                                oninput="$(this).removeClass('is-invalid');"
                                                                value="{{ $jobCardExtraCharge->label }}">
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control"
                                                                oninput="getextracharges();$(this).removeClass('is-invalid');"
                                                                name="charge[]" placeholder="Enter Charge Here"
                                                                value="{{ $jobCardExtraCharge->charges }}">
                                                        </td>
                                                        <td>
                                                            <select name="employee[]" class="form-select"
                                                                style="max-width: 200px !important;">
                                                                <option value="" selected disabled>-- Select Machanic
                                                                    --</option>
                                                                @foreach ($employee as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        @selected($jobCardExtraCharge->machanic_id == $item->id)>
                                                                        {{ $item->display_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm remove-field rounded text-white border-white"
                                                                style="margin-top: 5px;" fdprocessedid="dak07">
                                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td>
                                                    @if ($jobcard->status == 0 || $jobcard->status == 1)
                                                        <div class="d-flex w-100">
                                                            <select class="form-control select2"
                                                                id="labour-charges-dropdown"
                                                                style="min-width: 200px !important;"
                                                                onchange="addextracharge(this);return;false;">
                                                                <option value="" selected disabled>--Select Labour
                                                                    charge --</option>
                                                            </select>
                                                            <button type="button"
                                                                class="btn btn-outline-secondary add-spare-btn"
                                                                title="Click here for add new row."
                                                                onclick="addextracharge(this);return;false;"
                                                                style="background-color:#4b5f71;color:white;"> + </button>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
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
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($jobcard->jobCardSpareParts->where('category', 2) as $sparePartItem)
                                                <tr data-row="{{ $loop->iteration }}">
                                                    <td>
                                                        <span>{{ $sparePartItem->stock_label_name }}</span>
                                                        <input type="hidden" class="form-control"
                                                            name="jobcard_item_id[]"
                                                            value="{{ $sparePartItem->stock_label_id }}">
                                                    </td>
                                                    <td><input type="number" class="form-control"
                                                            name="jobcard_quantity[]"
                                                            value="{{ $sparePartItem->quantity }}" min="1"
                                                            max="" step="1"
                                                            oninput="getJobCardPrice(this)"></td>
                                                    <td><input type="number" class="form-control" name="jobcard_price[]"
                                                            value="{{ $sparePartItem->price }}" min="1"
                                                            step="1" oninput="getJobCardPrice(this)"></td>
                                                    <td><input type="text" class="form-control bg-light"
                                                            name="jobcard_total_amount[]" readonly
                                                            value="{{ $sparePartItem->total_amount }}" min="1"
                                                            step="1"></td>
                                                    <td><input type="number" class="form-control"
                                                            name="jobcard_discount[]"
                                                            value="{{ $sparePartItem->discount }}" min="0"
                                                            step="1" oninput="getJobCardPrice(this)"></td>
                                                    <td><input type="text" class="form-control bg-light"
                                                            name="jobcard_final_amount[]"
                                                            value="{{ $sparePartItem->final_amount }}" min="1"
                                                            step="1" readonly></td>
                                                    <td><button type="button"
                                                            class="btn btn-sm btn-danger rounded border-0 text-white"
                                                            onclick="removeJobCardRow(this)"><i class="fa fa-trash"
                                                                aria-hidden="true"></i></button></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td>
                                                    @if ($jobcard->status == 0 || $jobcard->status == 1)
                                                        <div class="d-flex">
                                                            <select class="form-control select2" id="spare-parts-dropdown"
                                                                onchange="addJobCardRow(this,'spare-part-table')"
                                                                style="min-width: 200px !important;">
                                                                <option value="" selected disabled>--Select Spare
                                                                    Part --
                                                                </option>
                                                            </select>
                                                            <button type="button"
                                                                class="btn btn-outline-secondary add-spare-btn"
                                                                onclick=addForm(this);return;false;> + </button>
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-secondary add-spare-btn"
                                                                onclick=addStock(this);return;false;> <span>Add
                                                                    Stock</span></button>
                                                        </div>
                                                    @endif
                                                </td>
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
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($jobcard->jobCardSpareParts->where('category', 4) as $sparePartItem)
                                                <tr data-row="{{ $loop->iteration }}">
                                                    <td>
                                                        <span>{{ $sparePartItem->stock_label_name }}</span>
                                                        <input type="hidden" class="form-control"
                                                            name="jobcard_item_id[]"
                                                            value="{{ $sparePartItem->stock_label_id }}">
                                                    </td>
                                                    <td><input type="number" class="form-control"
                                                            name="jobcard_quantity[]"
                                                            value="{{ $sparePartItem->quantity }}" min="1"
                                                            max="" step="1"
                                                            oninput="getJobCardPrice(this)"></td>
                                                    <td><input type="number" class="form-control" name="jobcard_price[]"
                                                            value="{{ $sparePartItem->price }}" min="1"
                                                            step="1" oninput="getJobCardPrice(this)"></td>
                                                    <td><input type="text" class="form-control bg-light"
                                                            name="jobcard_total_amount[]" readonly
                                                            value="{{ $sparePartItem->total_amount }}" min="1"
                                                            step="1"></td>
                                                    <td><input type="number" class="form-control"
                                                            name="jobcard_discount[]"
                                                            value="{{ $sparePartItem->discount }}" min="0"
                                                            step="1" oninput="getJobCardPrice(this)"></td>
                                                    <td><input type="text" class="form-control bg-light"
                                                            name="jobcard_final_amount[]"
                                                            value="{{ $sparePartItem->final_amount }}" min="1"
                                                            step="1" readonly></td>
                                                    <td><button type="button"
                                                            class="btn btn-sm btn-danger rounded border-0 text-white"
                                                            onclick="removeJobCardRow(this)"><i class="fa fa-trash"
                                                                aria-hidden="true"></i></button></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td>
                                                    @if ($jobcard->status == 0 || $jobcard->status == 1)
                                                        <div class="d-flex">
                                                            <select class="form-control select2" id="lubes-dropdown"
                                                                onchange="addJobCardRow(this,'lubes-table')"
                                                                style="min-width: 300px !important;">
                                                                <option value="" selected disabled>--Select Spare
                                                                    Part --
                                                                </option>
                                                            </select>
                                                            <button type="button"
                                                                class="btn btn-outline-secondary add-spare-btn"
                                                                onclick=addForm(this);return;false;> + </button>
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-secondary add-spare-btn"
                                                                onclick=addStock(this);return;false;> <span>Add
                                                                    Stock</span></button>
                                                        </div>
                                                    @endif
                                                </td>
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
                                {{-- <div class="mt-4 table-view" id="tools-table" style="display: none;">
                                    <table class="table table-bordered" id="tools-table">
                                        <thead>
                                            <tr>
                                                <th>Part Name</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Total Amount</th>
                                                <th>Discount</th>
                                                <th>Final Amount</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($jobcard->jobCardSpareParts->where('category', 3) as $sparePartItem)
                                                <tr data-row="{{ $loop->iteration }}">
                                                    <td>
                                                        <span>{{ $sparePartItem->stock_label_name }}</span>
                                                        <input type="hidden" class="form-control"
                                                            name="jobcard_item_id[]"
                                                            value="{{ $sparePartItem->stock_label_id }}">
                                                    </td>
                                                    <td><input type="number" class="form-control"
                                                            name="jobcard_quantity[]"
                                                            value="{{ $sparePartItem->quantity }}" min="1"
                                                            max="" step="1"
                                                            oninput="getJobCardPrice(this)"></td>
                                                    <td><input type="number" class="form-control" name="jobcard_price[]"
                                                            value="{{ $sparePartItem->price }}" min="1"
                                                            step="1" oninput="getJobCardPrice(this)"></td>
                                                    <td><input type="text" class="form-control bg-light"
                                                            name="jobcard_total_amount[]" readonly
                                                            value="{{ $sparePartItem->total_amount }}" min="1"
                                                            step="1"></td>
                                                    <td><input type="number" class="form-control"
                                                            name="jobcard_discount[]"
                                                            value="{{ $sparePartItem->discount }}" min="0"
                                                            step="1" oninput="getJobCardPrice(this)"></td>
                                                    <td><input type="text" class="form-control bg-light"
                                                            name="jobcard_final_amount[]"
                                                            value="{{ $sparePartItem->final_amount }}" min="1"
                                                            step="1" readonly></td>
                                                    <td><button class="btn btn-sm btn-danger rounded border-0 text-white"
                                                            onclick="removeJobCardRow(this)">Remove</button></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
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
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div> --}}
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
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($jobcard->jobCardSpareParts->where('category', 1) as $sparePartItem)
                                                <tr data-row="{{ $loop->iteration }}">
                                                    <td>
                                                        <span>{{ $sparePartItem->stock_label_name }}</span>
                                                        <input type="hidden" class="form-control"
                                                            name="jobcard_item_id[]"
                                                            value="{{ $sparePartItem->stock_label_id }}">
                                                    </td>
                                                    <td><input type="number" class="form-control"
                                                            name="jobcard_quantity[]"
                                                            value="{{ $sparePartItem->quantity }}" min="1"
                                                            max="" step="1"
                                                            oninput="getJobCardPrice(this)"></td>
                                                    <td><input type="number" class="form-control" name="jobcard_price[]"
                                                            value="{{ $sparePartItem->price }}" min="1"
                                                            step="1" oninput="getJobCardPrice(this)"></td>
                                                    <td><input type="text" class="form-control bg-light"
                                                            name="jobcard_total_amount[]" readonly
                                                            value="{{ $sparePartItem->total_amount }}" min="1"
                                                            step="1"></td>
                                                    <td><input type="number" class="form-control"
                                                            name="jobcard_discount[]"
                                                            value="{{ $sparePartItem->discount }}" min="0"
                                                            step="1" oninput="getJobCardPrice(this)"></td>
                                                    <td><input type="text" class="form-control bg-light"
                                                            name="jobcard_final_amount[]"
                                                            value="{{ $sparePartItem->final_amount }}" min="1"
                                                            step="1" readonly></td>
                                                    <td><button type="button"
                                                            class="btn btn-sm btn-danger rounded border-0 text-white"
                                                            onclick="removeJobCardRow(this)"><i class="fa fa-trash"
                                                                aria-hidden="true"></i></button></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td>
                                                    @if ($jobcard->status == 0 || $jobcard->status == 1)
                                                        <div class="d-flex">
                                                            <select class="form-control select2" id="accessory-dropdown"
                                                                onchange="addJobCardRow(this,'accessory-table')"
                                                                style="min-width: 300px !important;">
                                                                <option value="" selected disabled>--Select Spare
                                                                    Part --
                                                                </option>
                                                            </select>
                                                            <button type="button"
                                                                class="btn btn-outline-secondary add-spare-btn"
                                                                onclick=addForm(this);return;false;> + </button>
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-secondary add-spare-btn"
                                                                onclick=addStock(this);return;false;> <span>Add
                                                                    Stock</span></button>
                                                        </div>
                                                    @endif
                                                </td>
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

                                {{-- <hr>

                                <p class="btn btn-dark rounded" onclick="addextracharge(this)">Add Other Charges</p>

                                <div class="row newInputFieldOuter {{ count($jobCardExtraCharges) > 0 ? '' : 'd-none' }}">
                                    <div class="col-md-12">
                                        <div class="newInputFieldExtra">
                                            <table class="table table-bordered" id="other-charges">
                                                <thead>
                                                    <th style="background-color:#4b5f71;color:white;">Label</th>
                                                    <th style="background-color:#4b5f71;color:white;">Amount</th>
                                                    <th style="background-color:#4b5f71;color:white;">Action</th>
                                                </thead>
                                                <tbody>
                                                    @if (count($jobCardExtraCharges) > 0)
                                                        @foreach ($jobCardExtraCharges as $jobCardExtraCharge)
                                                            <tr>
                                                                <td>
                                                                    <input type="text" class="form-control"
                                                                        name="label[]" placeholder="Enter Label Here"
                                                                        oninput="$(this).removeClass('is-invalid');"
                                                                        value="{{ $jobCardExtraCharge->label }}">
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control"
                                                                        oninput="getextracharges();$(this).removeClass('is-invalid');"
                                                                        name="charge[]" placeholder="Enter Charge Here"
                                                                        value="{{ $jobCardExtraCharge->charges }}">
                                                                </td>
                                                                <td>
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm remove-field rounded text-white border-white"
                                                                        style="margin-top: 5px;" fdprocessedid="dak07">
                                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-outline-secondary add-spare-btn"
                                                                title="Click here for add new row."
                                                                onclick="addextracharge(this);return;false;"
                                                                style="background-color:#4b5f71;color:white;"> + </button>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="row ">
                                    <div class="col-md-6">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5>JobCard Details</h5>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="cost-estimate" class="form-label">Cost
                                                                Estimate</label>
                                                            <input type="text" id="cost-estimate" name="cost-estimate"
                                                                value="0" placeholder="Cost Estimate"
                                                                class="form-control rounded" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="delivery_date" class="form-label">Delivery
                                                                Date</label>
                                                            <input type="date" id="delivery_date" name="delivery_date"
                                                                value="{{ $jobcard->delivery_date ?: \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                placeholder="Delivery Date" class="form-control rounded">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="delivery_time" class="form-label">Delivery
                                                                Time</label>
                                                            <input type="time" id="delivery_time" name="delivery_time"
                                                                value="{{ $jobcard->delivery_time ? \Carbon\Carbon::parse($jobcard->delivery_time)->format('H:i') : \Carbon\Carbon::now()->format('H:i') }}"
                                                                placeholder="Delivery Time" class="form-control rounded">
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
                                                @if ($jobcard->status == 2 || $jobcard->status == 3)
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label for="total-amount" class="form-label">Total
                                                                    Amount</label>
                                                                <input type="text" id="total-amount"
                                                                    name="total_amount" value="0"
                                                                    placeholder="Total Amount"
                                                                    class="form-control bg-secondary text-white rounded"
                                                                    readonly>
                                                                <input type="hidden" name="total_discount"
                                                                    id="total-discount" value="0">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label for="final_discount"
                                                                    class="form-label">Discount</label>
                                                                <input type="number" id="final_discount"
                                                                    name="final_discount"
                                                                    value="{{ $jobcard->final_discount ?? 0 }}"
                                                                    min="0" step="1" placeholder="Discount"
                                                                    class="form-control rounded" oninput="totalCounter()">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label for="final-amount" class="form-label">Payable
                                                                    Amount</label>
                                                                <input type="text" id="final-amount"
                                                                    name="final_amount" value="0"
                                                                    placeholder="Payable Amount"
                                                                    class="form-control bg-secondary text-white rounded"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label for="advance" class="form-label">Advance
                                                                    Received</label>
                                                                <input type="text" id="advance" name="advance"
                                                                    value="{{ $jobcard->advance }}" placeholder="Advance"
                                                                    class="form-control bg-secondary text-white rounded"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label for="final_paid" class="form-label">Pay</label>
                                                                <input type="number" id="final_paid" name="final_paid"
                                                                    value="{{ $jobcard->final_paid ?? 0 }}"
                                                                    placeholder="Pay" min="0" step="1"
                                                                    class="form-control rounded" oninput="totalCounter()">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label for="pending-amount" class="form-label">Pending
                                                                    Amount</label>
                                                                <input type="text" id="pending-amount"
                                                                    name="balance_amount" value="0"
                                                                    placeholder="Pending Amount"
                                                                    class="form-control bg-secondary text-white rounded"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="mb-3">
                                                        <label for="total-amount" class="form-label">Total Amount</label>
                                                        <input type="text" id="total-amount" name="total_amount"
                                                            value="0" placeholder="Total Amount"
                                                            class="form-control w-50 bg-secondary text-white rounded"
                                                            readonly>
                                                        <input type="hidden" name="total_discount" id="total-discount"
                                                            value="0">
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label for="final-amount" class="form-label">Payable
                                                                    Amount</label>
                                                                <input type="text" id="final-amount"
                                                                    name="final_amount" value="0"
                                                                    placeholder="Payable Amount"
                                                                    class="form-control bg-secondary text-white rounded"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label for="advance" class="form-label">Advance</label>
                                                                <input type="text" id="advance" name="advance"
                                                                    value="{{ $jobcard->advance }}" placeholder="Advance"
                                                                    class="form-control rounded" oninput="totalCounter()">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label for="pending-amount" class="form-label">Pending
                                                                    Amount</label>
                                                                <input type="text" id="pending-amount"
                                                                    name="balance_amount" value="0"
                                                                    placeholder="Pending Amount"
                                                                    class="form-control bg-secondary text-white rounded"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="footer align-items-center">
                                    <div>
                                        @if ($jobcard->status == 2 || $jobcard->status == 3)
                                            <a href="{{ route('pdf.view') }}?url={{ base64_encode(route('download.invoice', [$jobcard->id, 1])) }}"
                                                target="_blank" class="btn btn-secondary">
                                                <i class="fa fa-print"></i></a>
                                            <a href="{{ route('download.invoice', ['id' => $jobcard->id, 'type' => 0]) }}"
                                                class="btn btn-secondary">
                                                <i class="fa fa-file-pdf-o"></i></a>
                                        @else
                                            <a href="javascript:void(0);" class="btn btn-secondary"
                                                data-bs-toggle="modal" data-bs-target="#pdfPrint"><i
                                                    class="fa fa-print"></i></a>
                                            <a href="javascript:void(0);" class="btn btn-secondary"
                                                data-bs-toggle="modal" data-bs-target="#pdfDownload"><i
                                                    class="fa fa-file-pdf-o"></i></a>
                                        @endif


                                        @if ($jobcard->step != 0)
                                            <a href="javascript:void(0);" class="btn btn-secondary"
                                                onclick="sendMail('{{ route('send.invoice.mail', $jobcard->id) }}')"><i
                                                    class="fa fa-envelope"></i></a>
                                        @endif
                                        {{-- <a href="javascript:void(0);" class="btn btn-secondary"><i
                                                class="fa fa-whatsapp"></i></a> --}}
                                    </div>
                                    <div class="form-check form-switch ">
                                        <input class="form-check-input fs-3" type="checkbox" role="switch"
                                            id="sms-alert" name="sms_alert" @checked($jobcard->sms_alert == 1) value="1">
                                        <label class="form-check-label fs-5" for="sms-alert">SMS Alert</label>
                                    </div>
                                    <div>
                                        {{-- <a class="btn btn-danger rounded border-0 text-white sa-warning"
                                            url="{{ route('newjobcard.destory', $jobcard->id) }}">
                                            {{ trans('message.Delete') }}
                                        </a> --}}
                                        @if ($jobcard->status == 0 || $jobcard->status == 1)
                                            <button type="button" class="btn btn-primary rounded border-0 text-white"
                                                onclick="qtyCheck({{ $jobcard->status }})">
                                                <small>Update Jobcard</small>
                                            </button>
                                        @endif

                                        @if ($jobcard->status == 0)
                                            @if ($jobcard->step == 0)
                                                <button type="button" class="btn btn-primary rounded border-0 text-white"
                                                    onclick="stepCheck(1);qtyCheck(0);">
                                                    <small>Process Jobcard</small>
                                                </button>
                                            {{-- @else
                                                <button type="button" class="btn btn-primary rounded border-0 text-white"
                                                    onclick="qtyCheck(1);">
                                                    <small>Confirm Jobcard</small>
                                                </button> --}}
                                            @endif 
                                        @elseif ($jobcard->status == 1)
                                            <button type="button" class="btn btn-primary rounded border-0 text-white"
                                                onclick="qtyCheck(2)">
                                                <small>Complete Jobcard</small>
                                            </button>
                                        @elseif ($jobcard->status == 2)
                                            <button type="button" class="btn btn-primary rounded border-0 text-white"
                                                onclick="qtyCheck(3)">
                                                <small>Close Jobcard</small>
                                            </button>
                                        @else
                                            <span class="btn btn-warning border-0">Jobcard Closed</span>
                                        @endif

                                        <input type="hidden" name="step" id="step" />
                                        <input type="hidden" name="status" id="status" />
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pdf download modal -->
    <div class="modal fade" id="pdfDownload" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="pdfDownloadLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="pdfDownloadLabel">Download pdf</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="my-4">
                        <h5>Please select type <span class="text-danger">*</span></h5>

                        <div class="py-4">
                            <input class="form-check-input" type="radio" name="pdf_type" id="machanic-worksheet"
                                value="1" checked>
                            <label class="" for="machanic-worksheet">
                                @if ($jobcard->status == 0 && $jobcard->step == 0)
                                    Machanic Copy
                                @else
                                    Machanic Worksheet
                                @endif
                            </label>

                            <input class="form-check-input" type="radio" name="pdf_type" id="estimate-invoice"
                                value="2" @disabled($jobcard->status == 0 && $jobcard->step == 0)>
                            <label class="" for="estimate-invoice">Estimate</label>
                        </div>

                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary rounded border-0 text-white"
                        data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary  rounded border-0 text-white"
                        onclick="downloadPdf()">Download</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Pdf download modal -->
    <div class="modal fade" id="pdfPrint" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="pdfPrintLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="my-4">
                        <h5>Please select type <span class="text-danger">*</span></h5>

                        <div class="py-4">
                            <input class="form-check-input" type="radio" name="print_pdf_type"
                                id="machanic-worksheet-print" value="1" checked>
                            <label class="" for="machanic-worksheet-print">
                                @if ($jobcard->status == 0 && $jobcard->step == 0)
                                    Machanic Copy
                                @else
                                    Machanic Worksheet
                                @endif
                            </label>

                            <input class="form-check-input" type="radio" name="print_pdf_type"
                                id="estimate-invoice-print" value="2" @disabled($jobcard->status == 0 && $jobcard->step == 0)>
                            <label class="" for="estimate-invoice-print">Estimate</label>
                        </div>

                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary rounded border-0 text-white"
                        data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary  rounded border-0 text-white"
                        onclick="printPdf()">Print</button>
                </div>
            </div>
        </div>
    </div>

    @include('new_jobcard.includes.model')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let btnClick = false;
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
            tableCounter('spare-part-table');
            tableCounter('lubes-table');
            tableCounter('tools-table');
            tableCounter('accessory-table');
            getextracharges();

            @if ($jobcard->status == 0)
                $('#customer-dropdown').select2({
                    placeholder: 'Search By Name, Mobile No. or Vehicle No.',
                    minimumInputLength: 2,
                    ajax: {
                        url: '{{ route('newjobcard.getData') }}',
                        type: 'GET',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                query: params.term,
                                page: params.page || 1,
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.results,
                                pagination: {
                                    more: data.pagination.more,
                                },
                            };
                        },
                        cache: true,
                    },
                });
            @endif



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

            /*vehical Type from brand*/
            $('.select_vehicaltype').change(function() {
                vehical_id = $(this).val();
                var url = $(this).attr('vehicalurl');
                sessionStorage.setItem('selectedType', vehical_id);

                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {
                        vehical_id: vehical_id
                    },
                    success: function(response) {
                        $('.select_vehicalbrand').html(response);

                        $('.select_vehicalbrand').trigger('change');
                    }
                });
            });

            /*vehical Model from brand*/
            $('.select_vehicalbrand').change(function() {
                id = $(this).val();
                var url = $(this).attr('url');
                sessionStorage.setItem('selectedBrand', id);

                $('.vehical_id').val(id).trigger('change');

                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {
                        id: id
                    },
                    success: function(response) {
                        $('.model_addname').html(response);
                    }
                });
            });

            Array.from(document.getElementsByClassName('showmodal')).forEach((e) => {
                e.addEventListener('click', function(element) {
                    element.preventDefault();
                    if (e.hasAttribute('data-show-modal')) {
                        showModal(e.getAttribute('data-show-modal'));
                    }
                });
            });

            // Show modal dialog
            function showModal(modal) {
                const mid = document.getElementById(modal);
                let myModal = new bootstrap.Modal(mid);
                myModal.show();
            }

            $("#formcustomer").on('submit', function(event) {
                event.preventDefault();
                $.ajax({
                    type: 'post',
                    url: '{!! url('service/customeradd') !!}',
                    data: new FormData(this),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    contentType: false,
                    cache: false,
                    processData: false,

                    success: function(data) {
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
                        let option = "<option value='" + data['customerId'] + "' selected>" +
                            data['customer_fullname'] + "</option>";
                        $('#customer-dropdown').append(option);
                        $('.modelnameappend').html('');
                        getVehicle("#customer-dropdown");
                        $('.select2').select2();
                        $('#myModal').modal('hide');
                        toastr.success("Customer Addes Successfully.", "Success");

                    },
                    error: function(e) {
                        alert(msg100 + " " + e.responseText);
                        console.log(e);
                    }
                });
            });

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

        function submitJobcard(e) {

            let url = $(e).attr('action');
            let formData = $(e).serialize();

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.status === 1) {
                        toastr.success('Job Card Updates Successfully!');
                        setTimeout(() => {
                            window.location.replace("{{ route('newjobcard.list') }}");
                        }, 2000);
                    } else {
                        Swal.fire({
                            title: "Oops...",
                            html: response.msg || "Something wrong found! Please try again.",
                            icon: "error",
                        });
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;

                        let errorMessage = '<ul class="list-unstyled">';
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
                        $("#bs-example-modal-xl").modal("hide");
                        $(".modal-body-data").html('');
                    } else {
                        toastr.error("Something Error Found! Please try again.", 'Error');
                    }
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
                        $('#labour-charges-dropdown').html(res.labourCharges);

                        select2function('spare-parts-dropdown');
                        select2function('lubes-dropdown');
                        select2function('tools-dropdown');
                        select2function('accessory-dropdown');
                        $('#labour-charges-dropdown').select2();
                        $('#labour-charges-dropdown').parents('td').find('.select2-container').addClass(
                        'w-100');
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

        // function getJobCardPrice(e) {
        //     let row = $(e).parents('tr').attr('data-row');
        //     const table = $(e).parents('table');

        //     let qty = table.find('input[name="jobcard_quantity[]"]').eq(row - 1).val();
        //     let price = table.find('input[name="jobcard_price[]"]').eq(row - 1).val();
        //     let discount = table.find('input[name="jobcard_discount[]"]').eq(row - 1).val();

        //     let totalAmount = parseInt(qty) * parseInt(price);
        //     let finalAmount = totalAmount - (parseInt(discount) || 0);

        //     table.find('input[name="jobcard_total_amount[]"]').eq(row - 1).val(totalAmount);
        //     table.find('input[name="jobcard_final_amount[]"]').eq(row - 1).val(finalAmount);

        //     tableCounter(table.attr('id'));
        // }

        function removeJobCardRow(e) {

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    const table = $(e).parents('table');
                    $(e).parents('tr').remove();

                    let i = 1;
                    $('#' + table.attr('id') + ' tbody tr').each(function() {
                        $(this).attr('data-row', i++);
                    });

                    tableCounter(table.attr('id'));
                    Swal.fire({
                        title: "Deleted!",
                        text: "The row has been deleted.",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });

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
            let chargeAmount = 0;

            @if ($jobcard->status == 2 || $jobcard->status == 3)
                let final_discount = 0;
                let final_paid = 0;
            @endif

            // Calculate chargeAmount
            $('input[name="charge[]"]').each(function() {
                let chargeValue = parseFloat($(this).val()) || 0;
                chargeAmount += chargeValue;
                trcount++;
            });

            // Calculate jobcard totals
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

            // Add chargeAmount to the total
            total += chargeAmount;
            finalTotal += chargeAmount
            // Update UI
            $('#total-counter span.tr-count').text(trcount);
            $('#total-counter span.total-count').text(total);
            $('#total-counter span.discount-count').text(discount);
            $('#total-counter span.final-total-count').text(finalTotal);

            $('#cost-estimate').val(total);
            $('#total-amount').val(total);
            $('#total-discount').val(discount);
            @if ($jobcard->status == 2 || $jobcard->status == 3)
                finalTotal -= (parseFloat($('#final_discount').val()) || 0);
            @endif

            $('#final-amount').val(finalTotal);

            let pendingAmount = parseFloat(finalTotal) - parseFloat($('#advance').val());

            @if ($jobcard->status == 2 || $jobcard->status == 3)
                pendingAmount -= (parseFloat($('#final_paid').val()) || 0);
            @endif

            $('#pending-amount').val(pendingAmount);
        }

        function addForm(e) {
            var contentUrl = "{{ route('purchase_spare_part.create') }}";
            $("#bs-example-modal-xl .modal-body-data").html('');
            $.ajax({
                type: "GET",
                url: contentUrl,
                success: function(data) {
                    $("#bs-example-modal-xl .modal-body-data").html(data);
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
            let tableId = $(e).parents('table').attr('id');
            let row = $(e).parents('tr').attr('data-row');

            if (!row) {
                console.error("Row attribute is missing in the parent <tr>.");
                return;
            }

            let itemId = [];
            $('#' + tableId + ' tbody select[name="item[]"]').each(function() {
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
                        $('#' + tableId + ' tbody select[name="item[]"]').eq(row - 1).html(res
                            .html);
                        $('#' + tableId + ' tbody input[name="quantity[]"]').eq(row - 1).val(
                            '0');
                        $('#' + tableId + ' tbody input[name="price[]"]').eq(row - 1).val('0');
                        $('#' + tableId + ' tbody input[name="total_price[]"]').eq(row - 1).val(
                            '0');

                        totalAmount();
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
                        $('input[name="quantity[]"]').eq(row - 1).attr('max', res.stock).val(
                            '1');
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

            if (totalAmount > 0) {
                $('#pay-for-PO').show();
            } else {
                $('#pay-for-PO').hide();
            }
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
            var contentUrl = "{{ route('newjobcard.addDentMark') }}";
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
            var contentUrl = "{{ route('newjobcard.customerVoice') }}";
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
            var jobcard_number = $('#jobcard_number').val();
            var contentUrl = "{{ route('newjobcard.workNotes') }}";
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
                error: function() {
                    alert("Failed to load content.");
                }
            });
        }

        function accessories(e) {
            var jobcard_number = $('#jobcard_number').val();
            var contentUrl = "{{ route('newjobcard.accessories') }}";
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
                    toastr.error("Failed to load content: " + error);
                }
            });
        }


        function getVehicle(e) {

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('newjobcard.getVehicle') }}",
                method: "post",
                data: {
                    customer_id: $(e).val()
                },
                success: function(res) {
                    if (res.status == 1) {
                        $('#vehicle-id').html(res.html);
                        $('.hidden_customer_id').val($(e).val());
                        $('#vehicle-id').select2();
                    } else {
                        toastr.info(res.msg, "INFO");
                        $('#vehicle-id').html(
                            '<option value="" selected disabled>-- Select vehicle --</option>'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error: " + error);
                    console.error("Status: " + status);
                    console.error("Response: " + xhr.responseText);
                }
            });
        }

        function stepCheck() {
            $('#step').val('1');
        }

        function qtyCheck(status) {

            let qtyCheck = false;
            $('input[name="jobcard_quantity[]"]').each(function() {
                if ($(this).val() == 0 || $(this).val() == null || $(this).val() == '') {
                    qtyCheck = true;
                }
            });

            $('input[name="jobcard_price[]"]').each(function() {
                if ($(this).val() == 0 || $(this).val() == null || $(this).val() == '') {
                    qtyCheck = true;
                }
            });

            if (qtyCheck) {
                toastr.info("Please check Item QTY or price. Value must me equal to or more than 1.",
                    "INFO");
                return;
            }

            $('#status').val(status);

            $('#jobcard-form').submit();
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
                        $('.jobcardImage').html(data.imageId)
                        toastr.success(data.message, 'Success');
                        $("#bs-example-modal-xl").modal("hide");
                        dataTable.draw(false);

                    } else if (data.success == 0) {
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


        function form_submit_customer_view(e) {
            $(e).find('.st_loader').show();
            $.ajax({
                url: $(e).attr('action'),
                method: "POST",
                dataType: "json",
                data: $(e).serialize(),
                success: function(data) {

                    if (data.success == 1) {
                        // alert(data.cardinspiration)
                        $('.customerVoiceCount').html(data.cardinspiration)
                        toastr.success(data.message, 'Success');
                        $("#bs-example-modal-xl").modal("hide");
                        dataTable.draw(false);


                    } else if (data.success == 0) {
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



        function form_submit_accessary(e) {

            $(e).find('.st_loader').show();
            $.ajax({
                url: $(e).attr('action'),
                method: "POST",
                dataType: "json",
                data: $(e).serialize(),
                success: function(data) {

                    if (data.success == 1) {
                        $('.accessaryCount').html(data.accessaryCount);
                        toastr.success(data.message, 'Success');
                        $("#bs-example-modal-xl").modal("hide");
                        dataTable.draw(false);

                    } else if (data.success == 0) {
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


        function form_submit_work_note(e) {

            $(e).find('.st_loader').show();
            $.ajax({
                url: $(e).attr('action'),
                method: "POST",
                dataType: "json",
                data: $(e).serialize(),
                success: function(data) {

                    if (data.success == 1) {
                        $('.workNoteCount').html(data.workNoteCount)
                        toastr.success(data.message, 'Success');
                        $("#bs-example-modal-xl").modal("hide");
                        dataTable.draw(false);
                        $('.worknotecount').html(data.countworknotes);

                    } else if (data.success == 0) {
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

        function addextracharge(e) {
            let emptyCharges = false;
            $('input[name="charge[]"]').each(function() {
                if ($(this).val() <= 0 || $(this).val() == '' || $(this).val() == null) {
                    $(this).addClass('is-invalid');
                    emptyCharges = true;
                }
                let label = $(this).parents('tr').find('input[name="label[]"]');
                if (label.val() == '' || label.val() == null) {
                    label.addClass('is-invalid');
                    emptyCharges = true;
                }
            });

            if (emptyCharges) {
                Swal.fire({
                    position: "top-end",
                    icon: "info",
                    title: "Please fill in all fields first.",
                    showConfirmButton: false,
                    timer: 3000
                });
                return;
            }

            let price = $('#labour-charges-dropdown option:selected').attr('data-price') || 0;
            let title = $('#labour-charges-dropdown option:selected').attr('data-title') || '';

            var contentUrl = "{{ route('newjobcard.addextrafields') }}";
            $.ajax({
                type: "GET",
                url: contentUrl,
                data: {
                    price: price,
                    title: title
                },
                success: function(data) {
                    $('#labour-table tbody').append(data.newfield);
                    $('.newInputFieldOuter').removeClass('d-none');
                    $('#labour-charges-dropdown').val('');
                    $('#labour-charges-dropdown').select2();
                    getextracharges();
                },
                error: function() {
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: "Failed to load content.",
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            });
        }

        $(document).on('click', '.remove-field', function() {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).parents('tr').remove();
                    if ($('.remove-field').length < 1) {
                        $('.newInputFieldOuter').addClass('d-none');
                    }
                    $('.select2').select2();
                    getextracharges();
                    Swal.fire({
                        title: "Deleted!",
                        text: "Labour field has been deleted.",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });




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


        function getextracharges() {
            let trcount = 0;
            let labour = 0;

            $('#labour-table tbody input[name="charge[]"]').each(function() {
                labour += parseFloat($(this).val()) || 0;
                trcount++;
            });

            $('#labour-table-counter span.tr-count').text(trcount);
            $('#labour-table-counter span.total-count').text(labour);
            $('#labour-table-counter span.final-total-count').text(labour);

            totalCounter();
        }

        function sendMail(contentUrl) {
            if (btnClick) {
                return;
            }

            btnClick = true;
            showLoader();
            $.ajax({
                type: "GET",
                url: contentUrl,
                success: function(res) {
                    if (res.status == 1) {
                        Swal.fire({
                            position: "top",
                            icon: "success",
                            title: "Success",
                            html: res.msg,
                            showConfirmButton: false,
                            timer: 3000
                        });
                    } else {
                        Swal.fire({
                            position: "top",
                            icon: "error",
                            title: "Error",
                            html: res.msg,
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                    btnClick = false;
                    hideLoader();
                },
                error: function() {
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: "Failed to load content.",
                        showConfirmButton: false,
                        timer: 2000
                    });
                    btnClick = false;
                    hideLoader();
                }
            });
        }

        function downloadPdf() {

            let type = $('input[name="pdf_type"]:checked').val();
            let url = "{{ route('download.mechanic.sheet', [$jobcard->id, 0]) }}";
            if (type == 1) {
                url = "{{ route('download.mechanic.sheet', [$jobcard->id, 0]) }}";
            } else if (type == 2) {
                url = "{{ route('download.estimate.invoice', [$jobcard->id, 0]) }}";
            }

            const link = document.createElement('a');
            link.href = url;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            $('#pdfDownload').modal('hide');
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Success",
                html: "PDF generated successfully.",
                showConfirmButton: false,
                timer: 3000
            });

        }

        function printPdf() {

            let type = $('input[name="print_pdf_type"]:checked').val();
            let url = "{{ base64_encode(route('download.mechanic.sheet', [$jobcard->id, 1])) }}";
            if (type == 1) {
                url = "{{ base64_encode(route('download.mechanic.sheet', [$jobcard->id, 1])) }}";
            } else if (type == 2) {
                url = "{{ base64_encode(route('download.estimate.invoice', [$jobcard->id, 1])) }}";
            }

            const link = document.createElement('a');
            link.href = "{{ route('pdf.view') }}?url=" + url;
            link.target = "_blank";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            $('#pdfPrint').modal('hide');

        }

        function showLoader() {
            document.getElementById('loader').style.visibility = 'visible';
        }

        // Hide the loader
        function hideLoader() {
            document.getElementById('loader').style.visibility = 'hidden';
        }

        function addStock(e) {
            var contentUrl = "{{ route('stock.add') }}";
            $("#bs-example-modal-xl .modal-body-data").html('');
            $.ajax({
                method: "GET",
                url: contentUrl,
                dataType: "json",
                success: function(data) {
                    if (data.status == 1) {
                        $("#bs-example-modal-xl .modal-body-data").html(data.html);
                        $("#bs-example-modal-xl").modal("show");
                    }
                },
                error: function() {
                    console.error("Error: ", error);
                    alert("Failed to load content.");
                }
            });
        }

        function addStockRowmodel() {
            var contentUrl = "{{ route('stock.add.row') }}";
            $.ajax({
                method: "GET",
                url: contentUrl,
                dataType: "json",
                success: function(data) {
                    if (data.status == 1) {
                        $('#addStock-Form tbody').append(data.html);
                    }
                },
                error: function() {
                    console.error("Error: ", error);
                    alert("Failed to load content.");
                }
            });
        }

        function removeAddStockRow(e) {
            $(e).parents('tr').remove();
        }

        function addSearch(e) {
            let text = $(e).text();
            $(e).parents('td').find('input').val(text);
            let resultsContainer = $(e).parents('tr').find('.searchResults');
            resultsContainer.empty();
            resultsContainer.hide();
        }

        function getSearchData(e) {
            let query = $(e).val();

            if (query.length > 0) {
                $.ajax({
                    url: "{{ route('stock.search') }}",
                    method: 'GET',
                    data: {
                        query: query
                    },
                    success: function(data) {
                        let resultsContainer = $(e).parents('tr').find('.searchResults');
                        resultsContainer.empty();
                        let html = `<ul class="list-unstyled">`;
                        if (data.length > 0) {
                            data.forEach(function(item) {
                                html +=
                                    `<li class="dropdown-item border-bottom" onclick="addSearch(this)">${item.title}</li>`;
                            });
                            html += `</ul>`;
                            resultsContainer.append(html);
                            resultsContainer.show();
                        } else {
                            resultsContainer.hide();
                        }
                    }
                });
            } else {
                $(e).parents('tr').find('.searchResults').hide();
            }
        }

        // async function makePayment() {
        //     const amount = document.getElementById('totalAmount').value;


        //     const response = await fetch("{!! url('create-order') !!}", {
        //         method: 'POST',
        //         headers: {
        //             'Content-Type': 'application/json',
        //             'X-CSRF-TOKEN': '{{ csrf_token() }}',
        //         },
        //         body: JSON.stringify({
        //             amount
        //         }),
        //     });

        //     const {
        //         order_id,
        //         amount: orderAmount
        //     } = await response.json();

        //     const options = {
        //         key: '{{ config('services.razorpay.key') }}',
        //         amount: orderAmount,
        //         currency: 'INR',
        //         order_id: order_id,
        //         handler: function(response) {
        //             fetch("{!! url('store-payment') !!}", {
        //                 method: 'POST',
        //                 headers: {
        //                     'Content-Type': 'application/json',
        //                     'X-CSRF-TOKEN': '{{ csrf_token() }}',
        //                 },
        //                 body: JSON.stringify({
        //                     razorpay_payment_id: response.razorpay_payment_id,
        //                     razorpay_order_id: response.razorpay_order_id,
        //                     razorpay_signature: response.razorpay_signature,
        //                     amount: orderAmount,
        //                 }),
        //             }).then(() => {
        //                 const paymentId = response.razorpay_payment_id;
        //                 const customParam = "example_value";
        //                 const redirectUrl =
        //                     `{!! url('payment-success') !!}?payment_id=${paymentId}&order_id=${order_id}&amount=${orderAmount / 100}&custom_param=${customParam}`;
        //                 window.location.href = redirectUrl;
        //             });
        //         },
        //     };

        //     const rzp = new Razorpay(options);
        //     rzp.open();
        // }

        function createCustomer() {
            var contentUrl = "{{ route('create.razorpay.customer') }}";
            let name = "{{ Auth::user()->name ?? '' }}";
            let email = "{{ Auth::user()->email ?? '' }}";
            let contact = "{{ Auth::user()->mobile_no ?? '' }}";

            if (!name.trim() || !email.trim() || !contact.trim()) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    html: "User details are not proper! <br> Please update your profile.",
                    footer: '<a href="{{ url('setting/profile') }}" target="_blank">Update profile</a>'
                });
                return;
            }

            $.ajax({
                method: "post",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                url: contentUrl,
                dataType: "json",
                data: {
                    name: name,
                    email: email,
                    contact: contact,
                },
                success: function(data) {
                    if (data.success) {
                        $('#customer_id').val(data.customer_id);
                        makePayment();
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: data.message,
                            icon: "error",
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error: ", error);
                    alert("Failed to create customer. Please try again.");
                }
            });
        }

        async function makePayment() {
            const amount = document.getElementById('totalAmount').value;
            let user = "{{ Auth::id() }}";

            const response = await fetch("{!! url('create-order') !!}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    amount: amount,
                    customer_id: $('#customer_id').val(),
                }),
            });

            const {
                success,
                order_id,
                amount: orderAmount
            } = await response.json();

            if (success) {
                const options = {
                    key: '{{ config('services.razorpay.key') }}',
                    amount: orderAmount,
                    currency: 'INR',
                    name: '{{ Auth::user()->name }}',
                    description: 'Payment for purchase order',
                    order_id: order_id,
                    handler: function(response) {
                        fetch("{{ route('payment.store') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                },
                                body: JSON.stringify({
                                    razorpay_payment_id: response.razorpay_payment_id,
                                    razorpay_order_id: response.razorpay_order_id,
                                    razorpay_signature: response.razorpay_signature,
                                    amount: orderAmount,
                                    user: user,
                                }),
                            }).then((res) => res.json())
                            .then((data) => {
                                if (data.success) {
                                    Swal.fire({
                                        title: "Success",
                                        text: 'Payment Successful!',
                                        icon: "success",
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    $('#payment_id').val(data.payment_id);
                                    $('#pay-for-PO').hide();
                                } else {
                                    Swal.fire({
                                        title: "Error",
                                        text: "Payment failed. Please try again.",
                                        icon: "error",
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                }
                            })
                            .catch((error) => {
                                console.error("Error:", error);
                                Swal.fire({
                                    title: "Error",
                                    text: "Something went wrong. Please try again.",
                                    icon: "error",
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            });
                    },
                };

                const rzp = new Razorpay(options);
                rzp.open();
            } else {
                Swal.fire({
                    title: "Error",
                    text: "Failed to create order!",
                    icon: "error",
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        }
    </script>
@endsection
