<div class="modal-header">
    <h4 class="modal-title" id="myLargeModalLabel">{{ $title }}</h4>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div> 

<form id="gumasta_images" action="{{ route('newjobcard.saveimageform') }}" onsubmit="form_submit_images(this);return false;" method="POST" enctype="multipart/form-data" autocomplete="on">
    @csrf
<input type="hidden" name="images_id" class="images_id" value="{{ @$jobCardImage->image_id }}">
<input type="hidden" name="main_id" class="main_id" value="{{ @$jobCardImage->id }}">
<input type="hidden" name="jobcard_numbers" class="jobcard_numbers" value="{{ $jobcard_numbers }}">

    <div class="modal-body">
        <div class="container-fluid">
        <div class="row mt-2">
  
               <div class="col-md-12">
               <label for="Outletname" class="form-label">Car Images</label>
               <div class="custom-file">
               <input type="hidden" name="gumasta_images_path" value="uploads/gumasta/">
               <input type="hidden" name="gumasta_images_name" value="gumasta_images">
                  <input type="file" class="custom-file form-control" name="gumasta_images[]" onchange="upload_gumastaimage($(form),'{{ route('newjobcard.imageupload') }}','gumasta_images','gumasta_images_id');return false;"accept=".jpg,.jpeg,.png"  multiple>
                  <label id="lblErrorMessageBannerImage" style="color:red"></label>
                  </div>
               </div> 
            </div>

      <div class="imageresponce d-flex"> 
      {!! $view !!}
      </div>     
    </div>


    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light btn-white border-white" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-primary btn-white border-white" type="submit">Save <i class="st_loader fa-btn-loader fa fa-refresh fa-spin fa-1x fa-fw" style="display:none;"></i></button>
    </div>
</form>

<script>
 
   
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
                  toastr.success(res.message, 'Success');
                $('.imageresponce').html(res.file_path);
                $('#' + id + '_prev').addClass('form-image');
                $('#' + id + '_prev').show(); 
                $('.images_id').val(res.file_id);
              }
        
            }
          });
        }


function delete_multiple_image(url,e){  
  var id = $(e).attr('data-id');
  var ids = $('.images_id').val();
  
   if(confirm('Are you sure you want to delete this?')){
      $.ajax({     
              url :url, 
      headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },  
       method:"POST",  
       data:{id:id,ids:ids},
       success: function(data){ 
        toastr.success(data.message, 'Success');
        $(e).parent().remove();
        $('.images_id').val(data.ids);
       },
       
     }); 
   }else{ 
     return false; 
   }
}

</script>
