<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }

        .container {
            width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            color: #333;
        }

        .note-card {
            /* border: 1px solid #ddd; */
            /* border-radius: 8px; */
            padding: 15px;
            margin-bottom: 20px;
            background-color: #fafafa;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .note-header {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .note-text {
            margin-bottom: 10px;
            color: #555;
        }

        .attachment-info {
            font-size: 14px;
            color: #888;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
        }

        .footer a {
            text-decoration: none;
        }

        .footer button {
            background-color: #EA6B00;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: -webkit-fill-available;
        }

        .footer button:hover {
            background-color: #333;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <?php
            $imageUrl = url('public/general_setting/' . getLogoSystem());
            ?>
            <img src="{{ $imageUrl }}" alt="Logo" width="40%">
            <h2>Vehicle Service Completed</h2>
        </div>

        <div class="note-card">
            <p>Dear <b>{{ $data['customer'] }}</b>,</p>

            <p>We are pleased to inform you that the service for your vehicle has been successfully completed.</p>

            <p><strong>Vehicle Details:</strong></p>
            <ul>
                <li>Model: {{ $data['vehicle'] }}</li>
                <li>Vehicle Number: {{ $data['number'] }}</li>
            </ul>

            <p>We have attached the invoice for your reference. Please feel free to contact us if you have any questions.</p>

            <p>Thank you for choosing our service!</p>

            <p>Best regards,</p>
            <p>Your Business Name</p>

            <div style="padding: 20px">
                
            </div>
        </div>
    </div>
</body>

</html>
