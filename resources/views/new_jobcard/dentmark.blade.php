<div class="modal-header">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<input type="hidden" id="jobcard_no" value="{{ $jobcard_numbers }}">
@if(!empty($jobCardsDentImage))
<img id="carImageTag" src="{{ URL::asset('public/'.$jobCardsDentImage->Image->path) }}">

<div class="row">
    <div class="col-6 mx-2">
        <button 
            id="deleteBtn" 
            onclick="imageDelete(<?= $jobCardsDentImage->id ?>, <?= $jobCardsDentImage->Image->id ?>)" 
            class="btn btn-danger mt-3 rounded text-white border-white" 
            fdprocessedid="3j5af">
            Delete Image
        </button>
    </div>
</div>

<!-- Delete Button -->

<script>
    function imageDelete(dentimageId, imageId) {
        var contentUrl = "{{route('newjobcard.deleteDentMark')}}";
        $.ajax({
            type: "GET" ,
             url: contentUrl,
              data: {
                dentimageId: dentimageId , 
                imageId: imageId,
                 }, 
                 success: function(data) { 
               toastr.success(data.message, 'Success');  
              $("#bs-example-modal-lg").removeClass("show").attr("aria-hidden", "true").hide();
              $(".modal-backdrop").remove();
                
            }, 
            error: function() {
                alert("Failed to load content.");
            }
        });
    }

</script>
@else
<!-- Control Buttons -->
<div class="controls">
    <button id="dentBtn" class="active">Dent</button>
    <button id="scratchBtn">Scratch</button>
    <button id="breakBtn">Break</button>
    <button id="eraseBtn">Erase</button>
    <button id="clearBtn">Clear All</button>
    <button id="saveBtn">Save Image</button>
</div>

<!-- Car Image (Hidden) -->
<img id="carImageTag" src="{{ URL::asset('public/dentcar/car.jpg') }}" style="display:none;">

<!-- Canvas Container -->
<div class="canvas-container">
    <!-- Background Canvas -->
    <canvas id="backgroundCanvas"></canvas>
    <!-- Overlay Canvas -->
    <canvas id="overlayCanvas"></canvas>
</div>


<!-- Bootstrap JS for Modal -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Canvas Setup
    const backgroundCanvas = document.getElementById('backgroundCanvas');
    const overlayCanvas = document.getElementById('overlayCanvas');
    const bgCtx = backgroundCanvas.getContext('2d');
    const overlayCtx = overlayCanvas.getContext('2d');

    const carImageTag = document.getElementById('carImageTag');
    const carImage = new Image();
    carImage.src = carImageTag.src;

    carImage.onload = () => {
        const scaleFactor = 0.7; // Resize image to 70%
        const newWidth = carImage.width * scaleFactor;
        const newHeight = carImage.height * scaleFactor;

        // Set canvas dimensions
        backgroundCanvas.width = newWidth;
        backgroundCanvas.height = newHeight;
        overlayCanvas.width = newWidth;
        overlayCanvas.height = newHeight;

        // Draw the image on the background canvas
        bgCtx.drawImage(carImage, 0, 0, newWidth, newHeight);
    };

    // Drawing Logic
    let drawing = false;
    let mode = 'dent'; // Default mode

    const penSettings = {
        dent: { color: 'yellow', size: 5 },
        scratch: { color: 'green', size: 5 },
        break: { color: 'red', size: 5 },
        erase: { size: 15 } // Eraser size
    };

    // Mode Switching
    const buttons = document.querySelectorAll('.controls button');
    buttons.forEach(button => {
        button.addEventListener('click', () => {
            buttons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            mode = button.id.replace('Btn', '');
        });
    });

    // Overlay Canvas Event Listeners
    overlayCanvas.addEventListener('mousedown', () => {
        drawing = true;
        overlayCtx.beginPath();
    });
    overlayCanvas.addEventListener('mouseup', () => {
        drawing = false;
        overlayCtx.beginPath();
    });
    overlayCanvas.addEventListener('mousemove', draw);

    function draw(event) {
        if (!drawing) return;

        const rect = overlayCanvas.getBoundingClientRect();
        const x = event.clientX - rect.left;
        const y = event.clientY - rect.top;

        overlayCtx.lineCap = 'round';

        if (mode === 'erase') {
            overlayCtx.globalCompositeOperation = 'destination-out';
            overlayCtx.lineWidth = penSettings.erase.size;
        } else {
            overlayCtx.globalCompositeOperation = 'source-over';
            overlayCtx.strokeStyle = penSettings[mode].color;
            overlayCtx.lineWidth = penSettings[mode].size;
        }

        overlayCtx.lineTo(x, y);
        overlayCtx.stroke();
        overlayCtx.beginPath();
        overlayCtx.moveTo(x, y);
    }

    // Clear Overlay Canvas
    document.getElementById('clearBtn').addEventListener('click', () => {
        overlayCtx.clearRect(0, 0, overlayCanvas.width, overlayCanvas.height);
    });

    // Save Merged Image and Send to Laravel
    document.getElementById('saveBtn').addEventListener('click', () => {
        if (!confirm("Are you sure you want to save the changes?")) return;

        const mergedCanvas = document.createElement('canvas');
        const mergedCtx = mergedCanvas.getContext('2d');

        mergedCanvas.width = backgroundCanvas.width;
        mergedCanvas.height = backgroundCanvas.height;

        mergedCtx.drawImage(backgroundCanvas, 0, 0);
        mergedCtx.drawImage(overlayCanvas, 0, 0);

        const mergedImageData = mergedCanvas.toDataURL('image/png');
       const jobcardNo = document.getElementById('jobcard_no').value;
        fetch('save-marked-image', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                 image_data: mergedImageData,
                   jobcard_no: jobcardNo 
                  })
        })
        .then(response => response.json())
        .then(data => {
            toastr.success(data.message, 'Success');
           
            $("#bs-example-modal-lg").removeClass("show").attr("aria-hidden", "true").hide();
 $(".modal-backdrop").remove();
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('Failed to save the image.', 'Error');
        });
    });
</script>
@endif


<!-- Bootstrap JS for Modal -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
