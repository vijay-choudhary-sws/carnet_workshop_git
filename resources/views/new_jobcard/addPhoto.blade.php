<div class="modal-header">
    <h4 class="modal-title" id="myLargeModalLabel">{{ $title }}</h4>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="gumasta_images" action="{{ route('newjobcard.saveimageform') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="container-fluid">
        <div class="row mt-2">
               <div class="col-md-12">
               <label for="Outletname" class="form-label">Gumasta Images</label>
               <div class="custom-file">
               <input type="hidden" name="gumasta_images_path" value="uploads/gumasta/">
               <input type="hidden" name="gumasta_images_name" value="gumasta_images">
                  <input type="file" class="custom-file form-control" name="gumasta_images[]" onchange="upload_gumastaimage($(form),'{{ route('newjobcard.imageupload') }}','gumasta_images','gumasta_images_id');return false;"accept=".jpg,.jpeg,.png"  multiple>
                  <input type="hidden" name="gumasta_images_id[]" id="gumasta_images_id" value="">
                  <label id="lblErrorMessageBannerImage" style="color:red"></label>
                  </div>
               </div>
               <div class="row mt-3 d-flex " id="outlet_images_prev1123">
                   <i class="gumasta_images_loader fa-btn-loader icon-loader feather fa fa-refresh fa-spin fa-1x fa-fw" style="display:none;"></i>
                     <label id="lblErrorMessageBannerImage" style="color:red"></label>
                  </div>
            </div>
    </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-primary" type="submit">Save <i class="st_loader fa-btn-loader fa fa-refresh fa-spin fa-1x fa-fw" style="display:none;"></i></button>
    </div>
</form>

<script>
    function form_submit(e) {

        $(e).find('.st_loader').show();
        $.ajax({
            url: $(e).attr('action')
            , method: "POST"
            , dataType: "json"
            , data: $(e).serialize()
            , success: function(data) {

                if (data.success == 1) {
                    toastr.success(data.message, 'Success');
                    $("#bs-example-modal-xl").modal("hide");
                    dataTable.draw(false);

                } else if (data.success == 0) {
                    toastr.error(data.message, 'Error');
                    $(e).find('.st_loader').hide();
                }
            }
            , error: function(data) {
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
   
  function upload_gumastaimage(form, url, id, input) 
        { 
          $(form).find('.' + id + '_loader').show();
          $.ajax({
            type: "POST",
            url: url + '?type=' + id,
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            contentType: false,
            cache: false,
            processData: false,
            dataType: "json",
            data: new FormData(form[0]),
            success: function (res) {
              if (res.status == 0) {
                $(form).find('.' + id + '_loader').hide();
                toastr.error(res.msg, 'Error');
              } else {
                $(form).find('.' + id + '_loader').hide();
                // $('#' + id + '_prev').attr('src', res.file_path);
                $('#outlet_images_prev1123').html(res.file_path);
                $('#outlet_images_prev1123').addClass('form-image');
                $('#outlet_images_prev1123').show();
                $('#' + input).val(res.file_id);
              }
        
            }
          });
        }

</script>
