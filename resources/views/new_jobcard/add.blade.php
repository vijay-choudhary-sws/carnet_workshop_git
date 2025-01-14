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

        #labour-table th,
        #labour-table .add-spare-btn,
        .bg-yellow-orange {
            background: #A52A2A !important;
            color: #fff !important;
        }

        .table-view table th {
            text-wrap: nowrap !important;
        }


        #counters div.card{
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        #counters div.card:hover{
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
    </style>
    <div class="right_col " role="main">
        <div class="">
            <div class="page-title">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
                            <a href="{{ route('newjobcard.list') }}" id=""><span class="titleup"><img
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
                                                <label for="customer-dropdown" class="form-label">Customer Name</label>
                                                <div class="input-group mb-3">
                                                    <select name="customer_name" id="customer-dropdown"
                                                        class="form-control select2" style="width: 75%;"
                                                        onchange="getVehicle(this)">
                                                    </select>

                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#myModal"
                                                        class="btn btn-outline-secondary input-group-text fl margin-left-0">{{ trans('+') }}</button>
                                                </div>
                                                <label for="vehicle-id" class="form-label">Vehical Name</label>
                                                <div class="input-group mb-3">
                                                    <select name="vehicle_id" id="vehicle-id" class="form-control select2 modelnameappend"
                                                        style="width: 75% !important;">
                                                        <option value="" selected disabled>-- Select vehicle --
                                                        </option>
                                                    </select>
                                                    <button type="button" data-bs-toggle="modal"
                                                        data-bs-target="#vehiclemymodel"
                                                        class="btn btn-outline-secondary  input-group-text vehiclemodel">{{ trans('+') }}
                                                    </button>
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
                                                                @foreach ($supervisors as $supervisor)
                                                                    <option value="{{ $supervisor->id }}" @selected($loop->first)>{{ getUserFullName($supervisor->id)}}</option>
                                                                @endforeach
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
                                <div class="footer align-items-center">
                                    <div class="form-check form-switch ">
                                        <input class="form-check-input fs-6" type="checkbox" role="switch"
                                            id="sms-alert" name="sms_alert"  value="1">
                                        <label class="form-check-label fs-5" for="sms-alert">SMS Alert</label>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary rounded border-0 text-white" onclick="qtyCheck()">Create Job
                                            Card</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
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
                        let option = "<option value='" + data['customerId'] + "' selected>" + data['customer_fullname'] + "</option>";
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
                        toastr.success('Job Card Added Successfully!');
                        setTimeout(() => {
                            window.location.replace("{{ route('newjobcard.list') }}");
                        }, 2000);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;

                        let errorMessage = '<ul class="list-unstyled"> ';
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
            $('#final-amount').val(finalTotal);

            // Calculate and update pending amount
            let pendingAmount = parseFloat(finalTotal) - parseFloat($('#advance').val());
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
                        $('#' + tableId + ' tbody select[name="item[]"]').eq(row - 1).html(res.html);
                        $('#' + tableId + ' tbody input[name="quantity[]"]').eq(row - 1).val('0');
                        $('#' + tableId + ' tbody input[name="price[]"]').eq(row - 1).val('0');
                        $('#' + tableId + ' tbody input[name="total_price[]"]').eq(row - 1).val('0');
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
                            '<option value="" selected disabled>-- Select vehicle --</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error: " + error);
                    console.error("Status: " + status);
                    console.error("Response: " + xhr.responseText);
                }
            });
        }

        function qtyCheck() {
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
                toastr.info("Please check Item QTY or price. Value must me equal to or more than 1.", "INFO");
                return;
            }
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

            var contentUrl = "{{ route('newjobcard.addextrafields') }}";
            $.ajax({
                type: "GET",
                url: contentUrl,
                success: function(data) {
                    $('#labour-table tbody').append(data.newfield);
                    $('.newInputFieldOuter').removeClass('d-none');
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
                    getextracharges();
                    Swal.fire({
                        title: "Deleted!",
                        text: "Other charge field has been deleted.",
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
    </script>
@endsection
