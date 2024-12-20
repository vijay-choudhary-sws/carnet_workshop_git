
<div class="modal-header">
    <h4 class="modal-title" id="myLargeModalLabel">{{$title}}</h4>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="add_product" action="{{ route('newjobcard.saveCustomerVoice') }}" onsubmit="form_submit_customer_view(this);return false;" method="POST" enctype="multipart/form-data" autocomplete="on">
    @csrf
    <input type="hidden" name="jobcard_numbers" class="jobcard_numbers" value="{{ $jobcard_numbers }}">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body px-sm-3 px-0">
                       
                        <p class="btn btn-success rounded" onclick="addNewField(this)"> Add More</p>
                        <div class="row">
                            @if(empty(@$jobCardsInspection))
                            <div class="mb-3 col-lg-12">
                                <label for="validationCustom01" class="form-label">Customer Voice</label><br>
                                <input type="text" name="customer_voice[]" class="form-control" placeholder="Customer Voice">
                            </div>
                            @else
                            @foreach($jobCardsInspection as $key => $jobCardsInspections)
                            <div class="mb-3 col-lg-12 dynamic-field">
                                <div class="row">
                                    <div class="col-11">
                                        <label for="validationCustom01" class="form-label">Customer Voice</label><br>
                                        <input type="text" class="form-control" name="customer_voice[]" placeholder="Customer Voice" value="{{ $jobCardsInspections['customer_voice'] }}">
                                    </div>
                                    <div class="col-1 mt-4 ">
                                        <button type="button" class="btn btn-danger btn-sm remove-field rounded text-white border-white" style="margin-top: 5px;" fdprocessedid="dak07"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                    </div>

                                </div>

                            </div>
                            @endforeach

                            @endif

                            <div class="newInputField"> </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light rounded border-white " data-bs-dismiss="modal">Close</button>
        <button class="btn btn-primary rounded border-white" type="submit">Save<i class="st_loader fa-btn-loader fa fa-refresh fa-spin fa-1x fa-fw" style="display:none;"></i></button>
    </div>
</form>
<script>

    function addNewField(e) {
        var contentUrl = "{{route('newjobcard.addField')}}";
        $.ajax({
            type: "GET"
            , url: contentUrl
            , success: function(data) {
                $('.newInputField').append(data.newfield);
            }
            , error: function() {
                alert("Failed to load content.");
            }
        });
    }

    $(document).on('click', '.remove-field', function() {
        $(this).closest('.dynamic-field').remove(); // Remove the entire dynamic field
    });

</script>
