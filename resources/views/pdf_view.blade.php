<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View PDF</title>
</head>
<body>
    @php
        $url = base64_decode($_GET['url']);
    @endphp
    <iframe 
        name="pdfFrame"
        src="{{ $url }}" 
        style="width: 100%; height: 90vh;" 
        frameborder="0"
        onload="this.contentWindow.print()">
    </iframe>
</body>
</html>
