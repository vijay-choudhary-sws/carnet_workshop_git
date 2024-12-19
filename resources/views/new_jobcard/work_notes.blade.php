<div class="modal-header">
    <h4 class="modal-title" id="myLargeModalLabel">{{$title}}</h4>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="add_product" action="{{ route('newjobcard.saveworkNotes') }}" onsubmit="form_submit(this);return false;" method="POST" enctype="multipart/form-data" autocomplete="on">
     @csrf
    <div class="modal-body">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body px-sm-3 px-0">
                            <div class="row">
                                <div class="mb-3 col-lg-12">
                                    <label for="validationCustom01" class="form-label">Work Note</label><br>
                                    <textarea class="workNote" id="w3review" name="work_notes" rows="4" cols="50"></textarea>
                                </div>  
                            </div> 
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-primary" type="submit">Save<i
            class="st_loader fa-btn-loader fa fa-refresh fa-spin fa-1x fa-fw"
            style="display:none;"></i></button>
    </div>
</form>
<script>

  function form_submit(e) {

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