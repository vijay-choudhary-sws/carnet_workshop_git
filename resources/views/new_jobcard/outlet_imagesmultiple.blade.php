@foreach($files as $fils)
    @php
        $destroy_url = route('newjobcard.multiimagedelete');
    @endphp
    <div class="col-md-2 mt-2">
        <a href="#!" onclick="delete_multiple_image('{{ $destroy_url }}', this)" class="position-relative d-block" data-id="{{ $fils->id }}">
            <span class="position-absolute bg-danger text-white p-0 ">
                <i class="icon-trash-2 feather"></i>
            </span>
        </a>
        <img src="{{ asset('uploads/gumasta/') }}/{{$fils->file}}" id="outlet_images_prev1" class="img-thumbnail" alt="" width="100" height="100">
    </div>
@endforeach