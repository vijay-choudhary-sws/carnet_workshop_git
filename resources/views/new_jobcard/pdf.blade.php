  
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Template</title>
    
    <style>
         body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            width: 100%; 
            background-color: #f9f9f9;
        }
        .inner-container {
            background-color: #ffffff;
            border: 1px solid #ddd;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;  
        }
        
        .header .logo {
            max-width: 200px;
            height: auto;
        }
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-top: 20px; 
            padding-bottom: 20px;
        }
        .info-box {
            width: 45%;
        }
        .info-box p {
            margin: 5px 0;
            font-size: 14px;
        }
        .status {
            font-size: 14px;
            font-weight: bold;
        }
        .status.open {
            color: #ffffff;
            background-color: #d9534f;
            padding: 2px 8px;
            border-radius: 3px;
        }
        .table-container {
            margin-top: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            color: #333;
        }
        .table th, .table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .table th {
            background-color: #f1f1f1;
        } 
        .phoneIMG ,.userIMG,.phoneIMGdd{
            max-width: 15px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="inner-container ">
     
            <div class="main_header" style="display:flex">
                <!-- Header Section -->
                <div class="header">
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('general_setting/' . $logo->logo_image))) }}" alt="Logo" class="logo">
                    <div class="info-section">
                        <div class="info-box">
                        <p> <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/icons/Vector (15).png'))) }}" alt="Logo" class="logo"> {{ $logo->email }}</p>
                        <p><img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/icons/phoneimg1.png'))) }}" alt="Logo" class="phoneIMG"> {{ $logo->phone_number }}</p> 
                        <p> <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/icons/Vector (14).png'))) }}" alt="Logo" class="logo"> Test, Ahmedabad, Gujarat, India</p>
                    </div>
                </div>
                </div>
    
                <!-- Info Section -->
                <div class="info-section">
                    <div class="info-box">
                        <p><img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/icons/user_img.png'))) }}" alt="Logo" class="userIMG"> {{ $customers->name }} {{ $customers->lastname }}</p>
                        <p><img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/icons/Vector (14).png'))) }}" alt="Logo" class="logo"> Jawahar Colony, Madhya Pradesh, India</p>
                        <p><img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/icons/phoneimg1.png'))) }}" alt="Logo" class="phoneIMG"> 9798765413210</p>
                        <p><img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/icons/Vector (15).png'))) }}" alt="Logo" class="logo"> {{ $customers->email }}</p>
                    </div>
                    <div class="info-box">
                        <p><strong>Status:</strong> <span class="status open">Open</span></p>
                        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($newjobcard->entry_date)->format('d-m-Y h:i A') ?? '-' }} </p>
                    </div>
                </div>
            </div>
              

            

            <!-- Jobcard Table -->
            <table class="table">
                <thead>
                    <tr>
                        <th>Jobcard Number</th>
                        <th>Coupon Number</th>
                        <th>Vehicle Name</th>
                        <th>Number Plate</th>
                        <th>In Date</th>
                        <th>Out Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $newjobcard->jobcard_number }}</td>
                        <td>Paid Service</td>
                        <td>{{ $vehicles->vehiclebrand_id }}/{{ $vehicles->modelname }}</td>
                        <td>{{ $vehicles->number_plate }}</td>
                        <td>{{ \Carbon\Carbon::parse($newjobcard->entry_date)->format('d-m-Y h:i A') ?? '-' }}</td>
                        <td>2024-12-15 10:08:24</td>
                    </tr>
                </tbody>
            </table>

            <div class="partCharges" style="margin-top:20px;"><strong>Part Charges</strong></div>
            <!-- Spare Parts Table -->
            <table class="table" style="margin-top:20px !important">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Mechanic Name</th>
                        <th>Quantity</th>
                        <th>Price (INR)</th>
                        <th>Total Price (INR)</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($jobCardSpareParts) > 0)
                        @php $i = 1; @endphp
                        @foreach ($jobCardSpareParts as $jobCardSparePart)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $jobCardSparePart->stock_label_name }}</td>
                                <td>{{ $jobCardSparePart->User->display_name }}</td>
                                <td>{{ $jobCardSparePart->quantity }}</td>
                                <td>{{ $jobCardSparePart->price }}</td>
                                <td>{{ $jobCardSparePart->final_amount }}</td>
                            </tr>
                            @php $i++; @endphp
                        @endforeach
                    @endif
                </tbody>
            </table>

            <div class="extraCharges" style="margin-top:20px;"><strong>Extra Charges</strong></div>

            <table class="table" style="margin-top:20px !important">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Label</th>
                        <th>Charges</th>
                      
                    </tr>
                </thead>
                <tbody>
                    @if (count($jobCardExtraCharges) > 0)
                        @php $i = 1; @endphp
                        @foreach ($jobCardExtraCharges as $jobCardExtraCharge)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $jobCardExtraCharge->label }}</td>
                                <td>{{ $jobCardExtraCharge->charges }}</td> 
                            </tr>
                            @php $i++; @endphp
                        @endforeach
                    @endif
                </tbody>
            </table>


            <!-- Summary -->
            <div class="table-container">
                <table class="table">
                    <tbody>
                        <tr>
                            <td align="right"><strong>Total Service Amount (INR):</strong></td>
                            <td align="right">{{ number_format($newjobcard->amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td align="right"><strong>Discount (INR):</strong></td>
                            <td align="right">{{ $newjobcard->discount }}</td>
                        </tr>
                        <tr>
                            <td align="right"><strong>Total (INR):</strong></td>
                            <td align="right">{{ number_format($newjobcard->final_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td align="right"><strong>Paid Amount (INR):</strong></td>
                            <td align="right">{{ number_format($newjobcard->advance, 2) }}</td>
                        </tr>
                        <tr>
                            <td align="right"><strong>Total Due Amount (INR):</strong></td>
                            <td align="right">{{ number_format($newjobcard->balance_amount, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
