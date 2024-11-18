<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Subscription Expiry Notification</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }
    .email-wrapper {
      width: 100%;
      background-color: #ffffff;
      padding: 30px;
      box-sizing: border-box;
    }
    .email-content {
      max-width: 600px;
      margin: 0 auto;
      background-color: #ffffff;
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      padding: 20px;
    }
    .email-header {
      text-align: center;
      font-size: 24px;
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
    }
    .highlight {
      font-weight: bold;
      color: #ff5722;
    }
    .button {
      display: inline-block;
      padding: 10px 20px;
      background-color: #ff5722;
      color: #fff;
      text-decoration: none;
      border-radius: 5px;
      text-align: center;
      font-size: 16px;
    }
  </style>
</head>
<body>

  <div class="email-wrapper">
    <div class="email-content">
      <div class="email-header">
        Subscription Expiry Notification
      </div>

      <div class="email-body">
        <p>Hello <span class="highlight">{{$data['user']['name']}}</span>,</p>

        <p>This is a reminder that your <span class="highlight">{{$data['type']}}</span> subscription for the service <span class="highlight">{{$data['service_name']}}</span> will expire in <span class="highlight">{{$data['time']}}</span>.</p>

        <p>If you wish to continue using our service, please renew your subscription as soon as possible.</p>

      </div>

      <div class="email-footer">
        <p>&copy; {{date('Y')}} Wegostores | All rights reserved</p>
      </div>
    </div>
  </div>

</body>
</html>
