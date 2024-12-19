<!-- Modal Header -->
<div class="modal-header"> 
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

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

<!-- Add Bootstrap JS for modal functionality -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Canvas Setup
    const backgroundCanvas = document.getElementById('backgroundCanvas');
    const overlayCanvas = document.getElementById('overlayCanvas');
    const bgCtx = backgroundCanvas.getContext('2d');
    const overlayCtx = overlayCanvas.getContext('2d');

    // Get the car image from the <img> tag
    const carImage = new Image();
    const carImageTag = document.getElementById('carImageTag');
    carImage.src = carImageTag.src; // Use the source of the <img> tag

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

    // Mode Switching Logic
    const buttons = document.querySelectorAll('.controls button');
    buttons.forEach(button => {
        button.addEventListener('click', () => {
            buttons.forEach(btn => btn.classList.remove('active')); // Remove active class
            button.classList.add('active'); // Add active class to clicked button
            mode = button.id.replace('Btn', ''); // Set the current mode
        });
    });

    // Event Listeners for Overlay Canvas
    overlayCanvas.addEventListener('mousedown', () => { drawing = true; overlayCtx.beginPath(); });
    overlayCanvas.addEventListener('mouseup', () => { drawing = false; overlayCtx.beginPath(); });
    overlayCanvas.addEventListener('mousemove', draw);

    function draw(event) {
        if (!drawing) return;

        const rect = overlayCanvas.getBoundingClientRect();
        const x = event.clientX - rect.left;
        const y = event.clientY - rect.top;

        if (mode === 'erase') {
            overlayCtx.globalCompositeOperation = 'destination-out'; // Erase mode
            overlayCtx.lineWidth = penSettings.erase.size;
        } else {
            overlayCtx.globalCompositeOperation = 'source-over'; // Drawing mode
            overlayCtx.strokeStyle = penSettings[mode].color;
            overlayCtx.lineWidth = penSettings[mode].size;
        }

        overlayCtx.lineCap = 'round';
        overlayCtx.lineTo(x, y);
        overlayCtx.stroke();
        overlayCtx.beginPath();
        overlayCtx.moveTo(x, y);
    }

    // Clear Overlay Canvas
    document.getElementById('clearBtn').addEventListener('click', () => {
        overlayCtx.clearRect(0, 0, overlayCanvas.width, overlayCanvas.height);
    });

  // Save Merged Image and Send to Laravel with Confirmation
document.getElementById('saveBtn').addEventListener('click', () => {
    const userConfirmed = confirm("Are you sure you want to save the changes?");

    if (!userConfirmed) {
        return; // Don't proceed if the user cancels
    }

    const mergedCanvas = document.createElement('canvas');
    const mergedCtx = mergedCanvas.getContext('2d');

    // Set dimensions
    mergedCanvas.width = backgroundCanvas.width;
    mergedCanvas.height = backgroundCanvas.height;

    // Merge background and overlay
    mergedCtx.drawImage(backgroundCanvas, 0, 0);
    mergedCtx.drawImage(overlayCanvas, 0, 0);

    // Get the merged image as a Base64 string
    const mergedImageData = mergedCanvas.toDataURL('image/png');

    // Send the Base64 string to Laravel via fetch
    fetch('save-marked-image', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            image_data: mergedImageData
        })
    })
    .then(response => response.json())
    .then(data => {
        toastr.success(data.message, 'Success'); 
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

</script>
