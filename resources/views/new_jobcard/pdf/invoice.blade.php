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
              background-color: #ffffff;
          }

          .container {
              width: 100%;
              background-color: #ffffff;
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

          .table th,
          .table td {
              padding: 10px;
              border: none;
              text-align: left;
          }

          .table th {
              background-color: #ed8e8a5d;
              /* margin: 0px !important;
            padding: 0px !important; */
          }

          .phoneIMG,
          .userIMG,
          .phoneIMGdd {
              max-width: 15px;
              height: auto;
          }

          .bill-info,
          .vehicle-info {
              width: 49%;
              display: inline-block;
          }

          .text-end {
              text-align: right !important;
          }

          .footer {
              display: inline-block;
              width: 49%;
              vertical-align: baseline;
              text-align: center;
          }
      </style>
  </head>

  <body>
    @php
        use Illuminate\Support\Number;
    @endphp
      <div class="container">
          <div class="inner-container ">

              <div class="main_header">
                  <!-- Header Section -->
                  <div class="header" style="display:inline-block; width:49%">
                      <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('general_setting/' . $logo->logo_image))) }}"
                          alt="Logo" class="logo">
                      <div class="info-section">
                          <div class="info-box">
                              <p> <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/icons/Vector (15).png'))) }}"
                                      alt="Logo" class="logo"> {{ $logo->email }}</p>
                              <p><img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/icons/phoneimg1.png'))) }}"
                                      alt="Logo" class="phoneIMG"> {{ $logo->phone_number }}</p>
                              <p> <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/icons/Vector (14).png'))) }}"
                                      alt="Logo" class="logo"> Test, Ahmedabad, Gujarat, India</p>
                          </div>
                      </div>
                  </div>

                  <!-- Info Section -->
                  <div class="info-section" style="display:inline-block; width:49%;position: relative;top:17px;">
                      <div class="info-box" style="">
                          <p>
                          <h2>Invoice</h2>
                          </p>
                          <p>Job Card Number : <strong>{{ $newjobcard->jobcard_number }}</strong></p>
                          <p>Date :
                              <strong>{{ \Carbon\Carbon::parse($newjobcard->entry_date)->format('d-m-Y') ?? '-' }}</strong>
                          </p>
                          <p>Time :
                              <strong>{{ \Carbon\Carbon::parse($newjobcard->entry_date)->format('h:i A') ?? '-' }}</strong>
                          </p>
                          <p>Paid Using :</p>
                      </div>

                  </div>
              </div>


              <table style="width:100%;">
                  <tbody>
                      <tr>
                          <td style="width:49%;vertical-align: top;">
                              <table class="table">
                                  <thead>
                                      <tr>
                                          <th>BILL TO</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <tr>
                                          <td>{{ getUserFullName($newjobcard->customer_id) }}</td>
                                      </tr>
                                      <tr>
                                          <td>{{ $customer->mobile_no }}</td>
                                      </tr>
                                      <tr>
                                          <td>{{ $customer->email }}</td>
                                      </tr>
                                  </tbody>
                              </table>
                          </td>
                          <td style="width:49%;vertical-align: top;">
                              <table class="table">
                                  <thead>
                                      <tr>
                                          <th>Vehicle Info</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <tr>
                                          <td>Veh. No. <strong>{{ $vehicles->number_plate }}</strong></td>
                                      </tr>
                                      <tr>
                                          <td>Vehicle Name : <strong>{{ $vehicles->modelname }}</strong></td>
                                      </tr>
                                      <tr>
                                          <td>Engine No. <strong>{{ $vehicles->modelname }}</strong></td>
                                      </tr>
                                      <tr>
                                          <td>Chassis No. <strong>{{ $vehicles->chassisno }}</strong></td>
                                      </tr>
                                      <tr>
                                          <td>Km Read. <strong>{{ $newjobcard->km_runing }}</strong>, Fuel Level :
                                              <strong>{{ $newjobcard->fual_level }}%</strong>
                                          </td>
                                      </tr>
                                  </tbody>
                              </table>
                          </td>
                      </tr>
                  </tbody>
              </table>
              @if (count($jobCardSpareParts->where('category', 1)) > 0)
                  <table class="table" style="margin-top: 10px;">
                      <thead>
                          <tr>
                              <th style="width: 25px;">#</th>
                              <th style="width: 45%;">Accessory Items</th>
                              <th>Qt.</th>
                              <th style="width: 100px;text-align:center;">Rate</th>
                              <th>Disc.</th>
                              <th style="width: 100px;text-align:center;">Amount</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($jobCardSpareParts->where('category', 1) as $jobCardSparePart)
                              <tr>
                                  <td>{{ $loop->iteration }}</td>
                                  <td>{{ $jobCardSparePart->labels?->title ?? '-' }}</td>
                                  <td>{{ $jobCardSparePart->quantity ?? 0 }}</td>
                                  <td class="text-end">{{ number_format($jobCardSparePart->total_amount, 2) ?? 0 }}
                                  </td>
                                  <td>{{ $jobCardSparePart->discount ?? 0 }}</td>
                                  <td class="text-end">{{ number_format($jobCardSparePart->final_amount, 2) ?? 0 }}
                                  </td>
                              </tr>
                          @endforeach
                      </tbody>
                  </table>
              @endif
              @if (count($jobCardSpareParts->where('category', 2)) > 0)
                  <table class="table" style="margin-top: 10px;">
                      <thead>
                          <tr>
                              <th style="width: 25px;">#</th>
                              <th style="width: 45%;">Spare Items</th>
                              <th>Qt.</th>
                              <th style="width: 100px;text-align:center;">Rate</th>
                              <th>Disc.</th>
                              <th style="width: 100px;text-align:center;">Amount</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($jobCardSpareParts->where('category', 2) as $jobCardSparePart)
                              <tr>
                                  <td>{{ $loop->iteration }}</td>
                                  <td>{{ $jobCardSparePart->labels?->title ?? '-' }}</td>
                                  <td>{{ $jobCardSparePart->quantity ?? 0 }}</td>
                                  <td class="text-end">{{ number_format($jobCardSparePart->total_amount, 2) ?? 0 }}
                                  </td>
                                  <td>{{ $jobCardSparePart->discount ?? 0 }}</td>
                                  <td class="text-end">{{ number_format($jobCardSparePart->final_amount, 2) ?? 0 }}
                                  </td>
                              </tr>
                          @endforeach
                      </tbody>
                  </table>
              @endif
              @if (count($jobCardSpareParts->where('category', 3)) > 0)
                  <table class="table" style="margin-top: 10px;">
                      <thead>
                          <tr>
                              <th style="width: 25px;">#</th>
                              <th style="width: 45%;">Tool Items</th>
                              <th>Qt.</th>
                              <th style="width: 100px;text-align:center;">Rate</th>
                              <th>Disc.</th>
                              <th style="width: 100px;text-align:center;">Amount</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($jobCardSpareParts->where('category', 3) as $jobCardSparePart)
                              <tr>
                                  <td>{{ $loop->iteration }}</td>
                                  <td>{{ $jobCardSparePart->labels?->title ?? '-' }}</td>
                                  <td>{{ $jobCardSparePart->quantity ?? 0 }}</td>
                                  <td class="text-end">{{ number_format($jobCardSparePart->total_amount, 2) ?? 0 }}
                                  </td>
                                  <td>{{ $jobCardSparePart->discount ?? 0 }}</td>
                                  <td class="text-end">{{ number_format($jobCardSparePart->final_amount, 2) ?? 0 }}
                                  </td>
                              </tr>
                          @endforeach
                      </tbody>
                  </table>
              @endif
              @if (count($jobCardSpareParts->where('category', 4)) > 0)
                  <table class="table" style="margin-top: 10px;">
                      <thead>
                          <tr>
                              <th style="width: 25px;">#</th>
                              <th style="width: 45%;">Lube Items</th>
                              <th>Qt.</th>
                              <th style="width: 100px;text-align:center;">Rate</th>
                              <th>Disc.</th>
                              <th style="width: 100px;text-align:center;">Amount</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($jobCardSpareParts->where('category', 4) as $jobCardSparePart)
                              <tr>
                                  <td>{{ $loop->iteration }}</td>
                                  <td>{{ $jobCardSparePart->labels?->title ?? '-' }}</td>
                                  <td>{{ $jobCardSparePart->quantity ?? 0 }}</td>
                                  <td class="text-end">{{ number_format($jobCardSparePart->total_amount, 2) ?? 0 }}
                                  </td>
                                  <td>{{ $jobCardSparePart->discount ?? 0 }}</td>
                                  <td class="text-end">{{ number_format($jobCardSparePart->final_amount, 2) ?? 0 }}
                                  </td>
                              </tr>
                          @endforeach
                      </tbody>
                  </table>
              @endif

              @if (count($jobCardExtraCharges) > 0)
                  <table class="table" style="margin-top: 10px;">
                      <thead>
                          <tr>
                              <th style="width: 25px;">#</th>
                              <th>Labour Items</th>
                              <th style="width: 100px;text-align:center;">Amount</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($jobCardExtraCharges as $jobCardExtraCharge)
                              <tr>
                                  <td>{{ $loop->iteration }}</td>
                                  <td>{{ $jobCardExtraCharge->label ?? '-' }}</td>
                                  <td class="text-end">{{ number_format($jobCardExtraCharge->charges, 2) ?? 0 }}</td>
                              </tr>
                          @endforeach
                      </tbody>
                  </table>
              @endif
              <hr>
              <table class="table">
                  <tbody>
                      <tr>
                          <td style="text-align:right;"><b>Total Amount</b></td>
                          <td style="width: 2px;"><b>:</b></td>
                          <td style="width: 100px;text-align:right;">
                              <b>{{ number_format($newjobcard->final_amount, 2) }}</b>
                          </td>
                      </tr>
                      <tr>
                          <td style="text-align:right;"><b>Total Discount</b></td>
                          <td style="width: 2px;"><b>:</b></td>
                          <td style="width: 100px;text-align:right;">
                              <b>{{ number_format($newjobcard->discount, 2) }}</b>
                          </td>
                      </tr>
                      <tr>
                          <td style="text-align:right;"><b>Advance</b></td>
                          <td style="width: 2px;"><b>:</b></td>
                          <td style="width: 100px;text-align:right;">
                              <b>{{ number_format($newjobcard->advance, 2) }}</b>
                          </td>
                      </tr>
                      <tr>
                          <td style="text-align:right;"><b>Balance</b></td>
                          <td style="width: 2px;"><b>:</b></td>
                          <td style="width: 100px;text-align:right;">
                              <b>{{ number_format($newjobcard->balance_amount, 2) }}</b>
                          </td>
                      </tr>
                  </tbody>
              </table>
            </div>
            <div class="inner-container" style="padding-bottom: 0px;">
              <p>BENZ / AUDI / BMW / MINI / VOLKSWAGON / SKODA / LANDROVER / JAGUAR / PORSCHE / VOLVO</p>
              <div style="text-align: center">
                  <p>Thank you for choosing our service.</p>
                  <p><small>Powered by: TTN Garage</small></p>
              </div>
          </div>
          <div class="inner-container" style="padding-bottom: 0px;">
              <div class="footer">Customer Signature</div>
              <div class="footer" style="border-left:1px solid black;">
                  <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('general_setting/' . $logo->logo_image))) }}"
                      alt="Logo" class="logo" width="49%">
                  <p>Authorized Signature</p>
              </div>
          </div>
      </div>
  </body>

  </html>
