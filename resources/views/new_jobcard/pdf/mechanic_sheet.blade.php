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
      </style>
  </head>

  <body>
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
                          <h2>Mechanic Copy</h2>
                          </p>
                          <p>Job Card Number : <strong>{{ $newjobcard->jobcard_number }}</strong></p>
                          <p>Date :
                              <strong>{{ \Carbon\Carbon::parse($newjobcard->entry_date)->format('d-m-Y') ?? '-' }}</strong>
                          </p>
                          <p>Time :
                              <strong>{{ \Carbon\Carbon::parse($newjobcard->entry_date)->format('h:i A') ?? '-' }}</strong>
                          </p>
                      </div>

                  </div>
              </div>




              <!-- Vehicle Info -->
              <table class="table" style="height:200px;">
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
                          <td>Time :
                              <strong>{{ \Carbon\Carbon::parse($newjobcard->entry_date)->format('h:i A') ?? '-' }}</strong>
                          </td>
                      </tr>
                      <tr>
                          <td>Chassis No. <strong>{{ $vehicles->chassisno }}</strong></td>
                          <td></td>
                      </tr>
                  </tbody>
              </table>


              {{-- <div class="customer_accessories" style="">  --}}
              <table class="table" style="height:auto;">
                  <tr>
                      <td style="width:100%; vertical-align: top;">
                          <div class="customer_voice" style="">
                              <!-- Customer Voice Table -->
                              <table class="table" style="">
                                  <thead>
                                      <tr>
                                          <th>Customer Voice</th>
                                      </tr>
                                  </thead>
                                  <tbody style="height: 100%">
                                      @php $i = 1; @endphp
                                      @foreach ($jobCardCustomerVoice as $jobCardCustomerVoices)
                                          <tr>
                                              <td>{{ $i }}.
                                                  <strong>{{ $jobCardCustomerVoices->customer_voice }}</strong>
                                              </td>
                                          </tr>
                                          @php $i++; @endphp
                                      @endforeach
                                  </tbody>
                              </table>
                          </div>
                      </td>

                      <!-- Info Section -->
                      <td style="width:100%;vertical-align:top;">
                          <div class="accessories" style="">
                              <!-- Accessories Table -->
                              <table class="table" style="">
                                  <thead>
                                      <tr>
                                          <th>Work Note</th>
                                      </tr>
                                  </thead>
                                  <tbody style="height: 100%">
                                      @php $j = 1; @endphp
                                      @foreach ($jobCardAccessories as $jobCardAccessorie)
                                          <tr>
                                              <td>{{ $j }}.
                                                  <strong>{{ $jobCardAccessorie->customer_voice }}</strong>
                                              </td>
                                          </tr>
                                          @php $j++; @endphp
                                      @endforeach
                                  </tbody>
                              </table>
                          </div>
                      </td>
                  </tr>
              </table>
              {{-- </div>  --}}

              @if (count($jobCardSpareParts->where('category', 1)) > 0)
                  <table class="table" style="margin-top: 10px;">
                      <thead>
                          <tr>
                              <th style="width: 25px;">#</th>
                              <th style="width: 45%;">Accessory Items</th>
                              <th>Status</th>
                              <th>Mechanic</th>
                              <th>Remark</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($jobCardSpareParts->where('category', 1) as $jobCardSparePart)
                              <tr>
                                  <td>{{ $loop->iteration }}</td>
                                  <td>{{ $jobCardSparePart->labels?->title ?? '-' }}</td>
                                  <td></td>
                                  <td>{{ getUserFullName($jobCardSparePart->machanic_id) }}</td>
                                  <td></td>
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
                              <th>Status</th>
                              <th>Mechanic</th>
                              <th>Remark</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($jobCardSpareParts->where('category', 2) as $jobCardSparePart)
                              <tr>
                                  <td>{{ $loop->iteration }}</td>
                                  <td>{{ $jobCardSparePart->labels?->title ?? '-' }}</td>
                                  <td></td>
                                  <td>{{ getUserFullName($jobCardSparePart->machanic_id) }}</td>
                                  <td></td>
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
                              <th>Status</th>
                              <th>Mechanic</th>
                              <th>Remark</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($jobCardSpareParts->where('category', 3) as $jobCardSparePart)
                              <tr>
                                  <td>{{ $loop->iteration }}</td>
                                  <td>{{ $jobCardSparePart->labels?->title ?? '-' }}</td>
                                  <td></td>
                                  <td>{{ getUserFullName($jobCardSparePart->machanic_id) }}</td>
                                  <td></td>
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
                              <th>Status</th>
                              <th>Mechanic</th>
                              <th>Remark</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($jobCardSpareParts->where('category', 4) as $jobCardSparePart)
                              <tr>
                                  <td>{{ $loop->iteration }}</td>
                                  <td>{{ $jobCardSparePart->labels?->title ?? '-' }}</td>
                                  <td></td>
                                  <td>{{ getUserFullName($jobCardSparePart->machanic_id) }}</td>
                                  <td></td>
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
                              <th style="width: 45%;">Labour Items</th>
                              <th>Status</th>
                              <th>Mechanic</th>
                              <th>Remark</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($jobCardExtraCharges as $jobCardExtraCharge)
                              <tr>
                                  <td>{{ $loop->iteration }}</td>
                                  <td>{{ $jobCardExtraCharge->label ?? '-' }}</td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                              </tr>
                          @endforeach
                      </tbody>
                  </table>
              @endif

          </div>
          @if ($newjobcard->step == 1)
              <div class="inner-container">
                  <p style="text-align: center">Vehicle need to be ready by :
                      {{ \carbon\carbon::parse($newjobcard->delivery_date)->format('d-m-Y') }}
                      {{ \carbon\carbon::parse($newjobcard->delivery_time)->format('h:i A') }}</p>
              </div>
          @endif
      </div>

  </body>

  </html>
