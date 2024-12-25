<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Modal</title>
  <style>
    /* Basic Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }

    /* Overlay & Modal */
    /* .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      background: rgba(0, 0, 0, 0.6);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .modal {
      background-color: #fff;
      padding: 32px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 600px;
      color: #333;
      overflow-y: auto;

    } */

      /* Main Container */
      .overlay {
      position: fixed;
      inset: 0;
      background-color: rgba(0, 0, 0, 0.3);
      display: flex;
      align-items: center;
      justify-content: center;
      width: 100%;
    }

    .modal {
      background-color: #fff;
      padding: 24px;
      border-radius: 8px;
      box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 100%;
      max-height: 90vh;
      overflow-y: auto;
    }


    /* Header & Sections */
    .header img {
      max-width: 180px;
      margin-bottom: 20px;
      display: block;
      margin-left: auto;
      margin-right: auto;
    }

    h2 {
      color: #1A237E;
      font-size: 1.6rem;
      font-weight: bold;
      margin-bottom: 8px;
    }

    h4 {
      color: #333;
      font-size: 1.2rem;
      font-weight: bold;
      margin-bottom: 8px;
      margin-top: 8px;
    }

    p {
      color: #555;
      font-size: 1rem;
      margin-bottom: 4px;
    }

    .text-blue {
      color: #1A73E8;
    }

    .text-green {
      color: #34A853;
    }

    .text-gray {
      color: #555;
    }

    .section {
      margin-bottom: 20px;
    }
    .sectiondiv{
        display: flex;
        justify-content: space-between;
    }

    /* Divider */
    .divider {
      border-top: 2px solid #1A237E;
      margin: 20px 0;
    }

    /* Order Summary */
    .summary ul {
      list-style-type: none;
    }

    .summary li {
      padding-bottom: 16px;
      margin-bottom: 16px;
      border-bottom: 1px solid #ddd;
    }

    /* Total Section */
    .total {
      background-color: #f5f5f5;
      padding: 16px;
      border-radius: 8px;
      text-align: center;
      font-size: 1.4rem;
      color: #1A237E;
      font-weight: bold;
      margin-bottom: 20px;
    }

    /* Button */
    .button {
      display: block;
      width: 100%;
      max-width: 200px;
      margin: 0 auto;
      padding: 12px;
      background-color: #1A237E;
      color: #fff;
      text-align: center;
      border-radius: 5px;
      font-size: 1.2rem;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .button:hover {
      background-color: #3b82f6;
    }

    /* Responsive */
    @media (min-width: 768px) {
      .modal {
        padding: 40px;
      }
    }
    @media  (max-width: 768px) {
        .sectiondiv{
        display: flex;
        flex-direction: column;
        padding: 0;
    }
    }
  </style>
</head>
<body>
  <div class="overlay">
    <div class="modal">
      <!-- Header -->
      <div class="header">
        <img src="{{url('storage/email/logo.png')}}" alt="wegoStore">
      </div>
    
        <div>
          <div>
              <!-- Order Details -->
              <div class="section">
                  <h2>Order Details:</h2>
                  <p class="font-semibold">Order Number: #12345</p>
                  <p class="font-semibold">Order Date: {{$data['created_at']}}</p>
              </div>
              <!-- Invoice Image -->
              @if (!empty($data['invoice_image']))
                <div class="section">
                  <img src="{{url('storage/' . $data['invoice_image'])}}" alt="receipt" style="width: 100%; border-radius: 8px;">
                </div>
              @endif
              <!-- Customer Information -->
              <div class="section">
                  <h2>Customer Information:</h2>
                  <p class="font-semibold">{{$data['created_at']}}</p>
                  <p class="font-semibold">{{$data['user']['email']}}</p>
                  <p class="font-semibold">{{$data['user']['phone']}}</p>
              </div>
          </div>
          <!-- Payment Details -->
          <div class="section">
              <h2>Payment Details:</h2>
              <div>
                  <p class="font-semibold">{{$data['payment_method']['name']}}</p>
                  <p class="font-semibold">{{$data['description']}}</p>
                  <p class="font-semibold">123-456-7890</p>
              </div>
          </div>
  
       </div>

      <!-- Divider -->
      <div class="divider"></div>

      <!-- Order Summary -->
      <div class="summary">
        <h2>Order Summary:</h2>
        <ul>
            @foreach ($data['orders'] as $item)
                @if (!empty($item['domain']))
                <li>
                  <h4 class="text-green">Domain Details</h4>
                  <p><span class="font-semibold">Domain Name:</span> {{$item['domain']['name']}}</p>
                  <p><span class="font-semibold">Price:</span> {{$item['domain']['price']}} LE</p>
                  <p><span class="font-semibold">Store Name:</span>{{$item['domain']['store']['store_name']}}</p>
                </li>
                @endif
                @if (!empty($item['plans']))
                <li>
                  <h4 class="text-blue">Plan Details</h4>
                  <p><span class="font-semibold">Name:</span> {{$item['plans']['name']}}</p>
                  <p><span class="font-semibold">SetUp Fees:</span> {{$item['plans']['setup_fees']}} LE</p>
                  <p><span class="font-semibold">Price Per Month:</span> {{$item['plans']['price_per_month']}} LE</p>
                  <p><span class="font-semibold">Price Per 3 Months:</span> {{$item['plans']['quarterly']}} LE</p>
                  <p><span class="font-semibold">Price Per 6 Months:</span> {{$item['plans']['semi_annual']}} LE</p>
                  <p><span class="font-semibold">Price Per Year:</span> {{$item['plans']['price_per_year']}} LE</p>
                  <p><span class="font-semibold">Limit Store:</span> {{$item['plans']['limet_store']}}</p>
                  <p><span class="font-semibold">Included App:</span> {{$item['plans']['app'] ? 'True': 'False'}}</p>
                </li>
                @endif
                @if (!empty($item['extra']))
                <li>
                  <h4 class="text-blue">Extra Details</h4>
                  <p><span class="font-semibold">Name:</span> {{$item['extra']['name']}}</p>
                  <p><span class="font-semibold">SetUp Fees:</span> {{$item['extra']['setup_fees']}} LE</p>
                  <p><span class="font-semibold">Price Per Month:</span> {{$item['extra']['monthly']}} LE</p>
                  <p><span class="font-semibold">Price Per 3 Months:</span> {{$item['extra']['quarterly']}} LE</p>
                  <p><span class="font-semibold">Price Per 6 Months:</span> {{$item['extra']['semi_annual']}} LE</p>
                  <p><span class="font-semibold">Price Per Year:</span> {{$item['extra']['yearly']}} LE</p>
                </li>
                @endif
            @endforeach
        </ul>
      </div>

      <!-- Total Section -->
      <div class="total">Discount: {{$data['discount']}} LE</div>
      <div class="total">Total: {{$data['amount']}} LE</div>
  </div>
</body>
</html>
