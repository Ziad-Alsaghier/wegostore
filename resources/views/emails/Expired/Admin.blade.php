<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Subscription Expiry Alert</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f9f9f9;
    }

    .email-wrapper {
      width: 100%;
      padding: 20px;
      background-color: #f4f4f4;
    }

    .email-content {
      max-width: 650px;
      margin: 0 auto;
      background-color: #ffffff;
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      padding: 20px;
    }

    .email-header {
      text-align: center;
      font-size: 24px;
      font-weight: bold;
      color: #333;
      margin-bottom: 20px;
    }

    .email-body {
      font-size: 16px;
      color: #555;
      line-height: 1.6;
      margin-bottom: 20px;
    }

    .email-footer {
      font-size: 14px;
      text-align: center;
      color: #888;
      margin-top: 30px;
    }

    .highlight {
      font-weight: bold;
      color: #007bff;
    }

    .details-table {
      width: 100%;
      margin: 20px 0;
      border-collapse: collapse;
    }

    .details-table th, .details-table td {
      padding: 10px;
      text-align: left;
      border: 1px solid #ddd;
    }

    .details-table th {
      background-color: #f1f1f1;
      color: #333;
    }

    .cta-button {
      display: inline-block;
      padding: 10px 20px;
      background-color: #007bff;
      color: #fff;
      text-decoration: none;
      border-radius: 5px;
      font-size: 16px;
      text-align: center;
      margin-top: 20px;
    }

    .cta-button:hover {
      background-color: #0056b3;
    }

  </style>
</head>
<body>

  <div class="email-wrapper">
    <div class="email-content">
      <div class="email-header">
        Subscription Expiry Alert
      </div>

      <div class="email-body">
        <p>Hello Admin,</p>

        <p>We wanted to inform you about the following subscription details:</p>

        <table class="details-table">
          <tr>
            <th>User Name</th>
            <td class="highlight">{{$data['user']['name']}}</td>
          </tr>
          <tr>
            <th>User Email</th>
            <td>{{$data['user']['email']}}</td>
          </tr>
          <tr>
            <th>Subscription Type</th>
            <td>{{$data['type']}}</td>
          </tr>
          <tr>
            <th>Service Name</th>
            <td>{{$data['service_name']}}</td>
          </tr>
          <tr>
            <th>Expiry Date</th>
            <td class="highlight">{{$data['time']}}</td>
          </tr>
        </table>

        <p>This subscription will expire on <span class="highlight">{{$data['time']}}</span>. Please take the necessary action.</p>

        <a href="#" class="cta-button">Review Subscription</a>
      </div>

      <div class="email-footer">
        <p>&copy; {{date('Y')}} Wegostores | All rights reserved</p>
      </div>
    </div>
  </div>

</body>
</html>
