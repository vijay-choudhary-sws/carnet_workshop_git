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
              color: black;
          }

          .rupee {
              font-family: 'DejaVu Sans', Arial, sans-serif;
          }

          .container {
              width: 100%;
              background-color: #ffffff;
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
              width: 100%;
          }

          .info-box p {
              margin: 5px 0;
              font-size: 12px;
          }

          .status {
              font-size: 12px;
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
              font-size: 12px;
              color: #333;
          }

          .table th,
          .table td {
              padding: 5px;
              font-size: 12px;
              border: 1px solid black;
              text-align: left;
              color: black;
          }

          .table th {
              background-color: #B6CDEF;
          }

          .phoneIMG,
          .userIMG,
          .phoneIMGdd {
              max-width: 15px;
              height: auto;
          }

          .text-center {
              text-align: center !important;
          }

          .text-end {
              text-align: right !important;
          }

          .p-0 {
              padding: 0px !important;
          }

          .m-0 {
              margin: 0px !important;
          }

          .border-0 {
              border: 0px !important;
          }

          .border-bottom-0 {
              border-bottom: 0px !important;
          }

          .my-3 {
              margin: 10px 0 !important;
          }
      </style>
  </head>

  <body>
      <div class="container">
          <div class="inner-container ">
              <div class="text-center" style="margin-bottom: 10px;">INVOICE</div>

              <table class="table my-3">
                  <tbody>
                      <tr>
                          <td class="p-0 border-0">
                              <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('general_setting/' . $logo->logo_image))) }}"
                                  alt="Logo" class="" style="max-width: 200px;">
                          </td>
                          <td class="p-0 border-0">
                              <div class="info-box">
                                  <h3 class="text-end" style="color:#663399;font-size:20px;">
                                      {{ Auth::user()->company_name }}
                                  </h3>
                                  <p class="text-end m-0">
                                      Email:- {{ Auth::user()->email }}
                                  </p>
                                  <p class="text-end m-0">
                                      Phone:- {{ Auth::user()->mobile_no }}
                                  </p>
                                  <p class="text-end m-0">
                                      {{ Auth::user()->address }}
                                  </p>
                              </div>
                          </td>
                      </tr>
                  </tbody>
              </table>

              <table class="table my-3">
                  <thead>
                      <tr>
                          <th>Bill To</th>
                          <th>Vehicle Details</th>
                          <th>Invoice Details</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
                          <td style="vertical-align: top">
                              <p class="p-0 m-0">
                                  Name:- {{ $customers->name }} {{ $customers->lastname }}
                              </p>
                              @if (!empty($customers->mobile_no))
                                  <p class="p-0 m-0">
                                      Mobile No:- {{ $customers->mobile_no ?? '9798765413210' }}
                                  </p>
                              @endif
                              @if (!empty($customers->email))
                                  <p class="p-0 m-0">
                                      Email:- {{ $customers->email }}
                                  </p>
                              @endif
                              @if (!empty($customers->address))
                                  <p class="p-0 m-0">
                                      Address:- {{ $customers->address ?? 'Jawahar Colony, Madhya Pradesh, India' }}
                                  </p>
                              @endif
                          </td>
                          <td style="vertical-align: top">
                              <p class="m-0">
                                  Vehicle Number:- {{ $vehicles->number_plate }}
                              </p>
                              <p class="m-0">
                                  Chassis Number:- {{ $vehicles->chassisno ?? ' - ' }}
                              </p>
                              <p class="m-0">
                                  Model:- {{ $vehicles?->brand?->vehicle_brand . ' / ' }}{{ $vehicles->modelname }}
                              </p>
                              <p class="m-0">
                                  In KM Reading:- {{ $newjobcard->km_runing ?? 0 }}
                              </p>
                              <p class="m-0">
                                  In Time:-
                                  {{ \Carbon\Carbon::parse($newjobcard->entry_date)->format('M d Y, h:i A') ?? '-' }}
                              </p>
                          </td>
                          <td style="vertical-align: top">
                              <p class="m-0">
                                  Invoice Number:- {{ $newjobcard->jobcard_number }}
                              </p>
                              <p class="m-0">
                                  Date:- {{ \Carbon\Carbon::now()->format('M d Y, h:i A') ?? '-' }}
                              </p>
                          </td>
                      </tr>
                  </tbody>
              </table>

              <div class="partCharges my-3"><strong>Parts Charges</strong></div>

              <table class="table my-3">
                  <thead>
                      <tr>
                          <th class="text-center">#</th>
                          <th class="text-center">Parts</th>
                          <th class="text-center">QTY</th>
                          <th class="text-center">Unit Price (Rs.)</th>
                          <th class="text-center">Discount(Rs.)</th>
                          <th class="text-center">Amount(Rs.)</th>
                      </tr>
                  </thead>
                  <tbody>
                      @if (count($jobCardSpareParts) > 0)
                          @php
                              $total = 0;
                          @endphp
                          @foreach ($jobCardSpareParts as $jobCardSparePart)
                              <tr>
                                  <td class="text-center" style="width:20px !important;">{{ $loop->iteration }}</td>
                                  <td>{{ $jobCardSparePart->stock_label_name }}</td>
                                  <td class="text-center" style="width:60px !important;">
                                      {{ $jobCardSparePart->quantity }} Units</td>
                                  <td class="text-end" style="width:80px !important;">
                                      {{ number_format($jobCardSparePart->price, 2) }}
                                  </td>
                                  <td class="text-end" style="width:80px !important;">
                                      {{ number_format($jobCardSparePart->discount, 2) }}
                                  </td>
                                  <td class="text-end" style="width:90px !important;">
                                      {{ number_format($jobCardSparePart->final_amount, 2) }}
                                  </td>
                              </tr>
                              @php
                                  $total += $jobCardSparePart->final_amount;
                              @endphp
                          @endforeach
                      @endif
                  </tbody>
                  <tfoot>
                      <tr>
                          <td class="text-end" colspan="5"><strong>Total(Rs.)</strong></td>
                          <td class="text-end"><strong>{{ number_format($total, 2) }}</strong></td>
                      </tr>
                  </tfoot>
              </table>

              @if (count($jobCardExtraCharges) > 0)
                  <div class="extraCharges my-3"><strong>Extra Charges</strong></div>

                  <table class="table my-3">
                      <thead>
                          <tr>
                              <th class="text-center">#</th>
                              <th>Label</th>
                              <th class="text-center">Charge Price(Rs.)</th>
                              <th class="text-center">Amount(Rs.)</th>

                          </tr>
                      </thead>
                      <tbody>

                          @php $total = 0; @endphp
                          @foreach ($jobCardExtraCharges as $jobCardExtraCharge)
                              <tr>
                                  <td class="text-center" style="width:20px !important;">{{ $loop->iteration }}</td>
                                  <td>{{ $jobCardExtraCharge->label }}</td>
                                  <td class="text-end" style="width:120px !important;">
                                      {{ number_format($jobCardExtraCharge->charges, 2) }}</td>
                                  <td class="text-end" style="width:120px !important;">
                                      {{ number_format($jobCardExtraCharge->charges, 2) }}</td>
                              </tr>
                              @php $total += $jobCardExtraCharge->charges; @endphp
                          @endforeach

                      </tbody>
                      <tfoot>
                          <tr>
                              <td class="text-end" colspan="3"><strong>Total(Rs.)</strong></td>
                              <td class="text-end"><strong>{{ number_format($total, 2) }}</strong></td>
                          </tr>
                      </tfoot>
                  </table>
              @endif

              <table class="table" style="margin-top: 30px;">
                  <tbody>
                      <tr>
                          <th></th>
                          <th colspan="2">Bill Amount Details:</th>
                      </tr>
                      <tr>
                          <td></td>
                          <td style="width: 120px !important;">
                              <p class="text-end m-0"><strong>Total Amount(Rs.)</strong></p>
                              <p class="text-end m-0"><strong>
                                      @if (!empty($newjobcard->final_discount))
                                          *
                                      @endif Discount(Rs.)
                                  </strong></p>
                              <p class="text-end m-0"><strong>Payable(Rs.)</strong></p>
                              <p class="text-end m-0"><strong>Advance(Rs.)</strong></p>
                              @if (!empty($newjobcard->balance_amount))
                                  <p class="text-end m-0"><strong>Balance(Rs.)</strong></p>
                              @endif
                              @if (!empty($newjobcard->final_paid))
                                  <p class="text-end m-0"><strong>Received(Rs.)</strong></p>
                              @endif
                          </td>
                          <td style="width: 120px !important;">
                              <p class="text-end m-0">
                                  <strong>{{ number_format($newjobcard->amount, 2) }}</strong>
                              </p>
                              <p class="text-end m-0">
                                  <strong>{{ number_format($newjobcard->discount + $newjobcard->final_discount, 2) }}</strong>
                              </p>
                              <p class="text-end m-0">
                                  <strong>{{ number_format($newjobcard->final_amount, 2) }}</strong>
                              </p>
                              <p class="text-end m-0">
                                  <strong>{{ number_format($newjobcard->advance, 2) }}</strong>
                              </p>
                              @if (!empty($newjobcard->balance_amount))
                                  <p class="text-end m-0">
                                      <strong>{{ number_format($newjobcard->balance_amount, 2) }}</strong>
                                  </p>
                              @endif
                              @if (!empty($newjobcard->final_paid))
                                  <p class="text-end m-0">
                                      <strong>{{ number_format($newjobcard->final_paid, 2) }}</strong>
                                  </p>
                              @endif
                          </td>
                      </tr>
                      <tr>
                        <td colspan="3" class="p-0  border-0">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Invoice Amount In Words</th>
                                        <th>Authorised Signature and Payment Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="vertical-align: top;">
                                            @php
                                                $formatter = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
                                                echo ucfirst($formatter->format($newjobcard->amount)) . ' Rupees Only';
                                            @endphp
                                        </td>
                                        <td style="width:250px !important;vertical-align: bottom;height:80px;position:relative;">
                                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('general_setting/' . $logo->logo_image))) }}"
                                                alt="Logo" class="" width="150">
                                            @if (!empty($newjobcard->final_paid))
                                                <div style="position:absolute;bottom:0;right:20px;">
                                                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/jobcard_img/paid_stamp.png'))) }}"
                                                        alt="Logo" class="" width="120">
                                                </div>
                                            @else
                                                <div style="position:absolute;bottom:0;right:20px;">
                                                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/jobcard_img/partial_paid.png'))) }}"
                                                        alt="Logo" class="" width="120">
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                      </tr>
                      {{-- <tr>
                          <th>Invoice Amount In Words</th>
                          <th colspan="2">Authorised Signature and Payment Status</th>
                      </tr>
                      <tr>
                          <td style="vertical-align: top;">
                            @php
                                $formatter = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
                                echo ucfirst($formatter->format($newjobcard->amount)) . ' Rupees Only';
                            @endphp
                            </td>
                          <td colspan="2" style="vertical-align: bottom;height:80px;position:relative;">
                              <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('general_setting/' . $logo->logo_image))) }}"
                                  alt="Logo" class="" width="150">
                              @if (!empty($newjobcard->final_paid))
                                  <div style="position:absolute;bottom:0;right:20px;">
                                      <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/jobcard_img/paid_stamp.png'))) }}"
                                          alt="Logo" class="" width="120">
                                  </div>
                              @else
                                  <div style="position:absolute;bottom:0;right:20px;">
                                      <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/jobcard_img/partial_paid.png'))) }}"
                                          alt="Logo" class="" width="120">
                                  </div>
                              @endif
                          </td>
                      </tr> --}}
                      <tr>
                          <td colspan="3" class="">
                              @if (!empty($newjobcard->final_discount))
                                  <p>
                                      *An additional discount of <strong>(Rs.
                                          {{ $newjobcard->final_discount }})</strong> has been applied to the
                                      <strong>Discount</strong> section.
                                  </p>
                              @endif
                              <p>
                                  Thank You! Service Regularly and ride safely. And Visit Again
                              </p>
                          </td>
                      </tr>
                      <tr>
                          <td colspan="3" class="p-0 border-0">
                              <table class="table">
                                  <tr>
                                      <th><strong>Exit Note</strong></th>
                                      <th><strong>Customer Voice</strong></th>
                                  </tr>
                                  <tr>
                                      <td>{{ $exitNote?->note ?? '' }}</td>
                                      <td>
                                          @foreach ($jobCardCustomerVoice as $customerVoice)
                                              {{ !$loop->first ? ', ' : '' }}{{ $loop->iteration . '. ' . $customerVoice->customer_voice }}
                                          @endforeach
                                      </td>
                                  </tr>
                                  <tr>
                                      <th colspan="2"><strong>Work Notes</strong></th>
                                  </tr>
                                  <tr>
                                      <td colspan="2">
                                          @foreach ($jobCardAccessories as $workNot)
                                              {{ !$loop->first ? ', ' : '' }}{{ $loop->iteration . '. ' . $workNot->customer_voice }}
                                          @endforeach
                                      </td>
                                  </tr>
                              </table>
                          </td>
                      </tr>
                  </tbody>
              </table>

              <div class="text-center" style="margin-top: 20px;font-size:12px;">**BENZ / AUDI / BMW / MINI /
                  VOLKSWAGON / SKODA / LANDROVER / JAGUAR / PORSCHE / VOLVO**</div>
          </div>
      </div>
  </body>

  </html>
