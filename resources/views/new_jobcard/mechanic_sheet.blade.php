  
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antek123</title>
    
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
            width: 100%;
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
            border: none;
            text-align: left;
        }
        .table th {
            background-color: #ed8e8a5d;
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
     
            <div class="main_header">
                <!-- Header Section -->
                <div class="header"  style="display:inline-block; width:49%">
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
                <div class="info-section"  style="display:inline-block; width:49%;position: relative;top:17px;">
                    <div class="info-box" style="">
                        <p><h2>Mechanic Copy</h2></p>
                        <p>Job Card Number : <strong>{{$newjobcard->jobcard_number}}</strong></p>
                        <p>Date : <strong>{{ \Carbon\Carbon::parse($newjobcard->entry_date)->format('d-m-Y') ?? '-' }}</strong></p>
                        <p>Time : <strong>{{ \Carbon\Carbon::parse($newjobcard->entry_date)->format('h:i A') ?? '-' }}</strong></p>
                    </div>
                  
                </div>
            </div>
              

            

            <!-- Vehicle Info -->
            <table class="table">
                <thead>
                    <tr>
                        <th>Vehicle Info</th> 
                        <th></th> 
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Veh. No. <strong>{{ $vehicles->number_plate }}</strong></td> 
                        <td>Km Read. <strong>{{ $newjobcard->km_runing }}</strong></td> 
                    </tr>
                    <tr>
                        <td>Vehicle Name : <strong>{{ $vehicles->modelname }}</strong></td> 
                        <td>Fuel Level : <strong>{{ $newjobcard->fual_level }}%</strong></td> 
                    </tr> 
                     <tr>
                        <td>Engine No. <strong>{{ $vehicles->modelname }}</strong></td> 
                        <td>Time : <strong>{{ \Carbon\Carbon::parse($newjobcard->entry_date)->format('h:i A') ?? '-' }}</strong></td> 
                    </tr> 
                     <tr>
                        <td>Chassis No. <strong>{{ $vehicles->chassisno }}</strong></td> 
                        <td></td> 
                    </tr>
                </tbody>
            </table> 


            
            <!-- Customer Voice -->
            <table class="table" style="margin-top:10px">
                <thead>
                    <tr>
                        <th>Customer Voice</th> 
                        <th></th> 
                    </tr>
                </thead>
                <tbody>
                  
                    @php $i = 1; @endphp
                    @foreach ($jobCardCustomerVoice as $jobCardCustomerVoices)
                        <tr>
                            <td>{{ $i }} <strong>{{ $jobCardCustomerVoices->customer_voice }}</strong></td> 
                            <td></td>  
                        </tr>
                        @php $i++; @endphp
                    @endforeach 
                  
                </tbody>
            </table> 
        </div>
    </div>
</body>
</html>
