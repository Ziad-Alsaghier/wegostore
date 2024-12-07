<!DOCTYPE html>
<html>
<head>
    <title>Order Email</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 20px auto; padding: 20px; background-color: #ffffff; border: 1px solid #ddd; border-radius: 8px;">
        @if (!empty($order['domain_id']))
            <h1 style="color: #333; text-align: center;">Hello, Mr. {{ $order['users']['name'] }}</h1>
            <p style="color: #555; line-height: 1.6;">
                Your domain is ready. Please click the button below to complete your order:
            </p>
            <div style="text-align: center; margin-top: 20px;">
                <a href="" style="background-color: #007bff; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-size: 16px;">Complete Order</a>
            </div>
        @elseif (!empty($order['store_id']))
            <h1 style="color: #333; text-align: center;">Hello, Mr. {{ $order['users']['name'] }}</h1>
            <p style="color: #555; line-height: 1.6; text-align: center;">
                Your store is ready. Below are the details:
            </p>
            <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr style="background-color: #f4f4f4;">
                        <th style="text-align: left; padding: 10px; border: 1px solid #ddd; color: #333;">Details</th>
                        <th style="text-align: right; padding: 10px; border: 1px solid #ddd; color: #333;">Information</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ddd; color: #555;">Store Name</td>
                        <td style="padding: 10px; border: 1px solid #ddd; color: #555;">{{ $order['store']['store_name'] }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ddd; color: #555;">Store Link</td>
                        <td style="padding: 10px; border: 1px solid #ddd; color: #555;">
                            <a href="{{ $order['store']['link_store'] }}" style="color: #007bff; text-decoration: none;">{{ $order['store']['link_store'] }}</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ddd; color: #555;">Cpanel Link</td>
                        <td style="padding: 10px; border: 1px solid #ddd; color: #555;">
                            <a href="{{ $order['store']['link_cbanal'] }}" style="color: #007bff; text-decoration: none;">{{ $order['store']['link_cbanal'] }}</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ddd; color: #555;">Email</td>
                        <td style="padding: 10px; border: 1px solid #ddd; color: #555;">{{ $order['store']['email'] }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ddd; color: #555;">Password</td>
                        <td style="padding: 10px; border: 1px solid #ddd; color: #555;">{{ $order['store']['password'] }}</td>
                    </tr>
                </tbody>
            </table>
        @elseif (!empty($order['extra_id']))
            <h1 style="color: #333; text-align: center;">Hello, Mr. {{ $order['users']['name'] }}</h1>
            <p style="color: #555; line-height: 1.6; text-align: center;">
                Your extra service has been completed: <strong>{{ $order['extra']['name'] }}</strong>
            </p>
        @endif
        <p style="text-align: center; color: #888; font-size: 12px; margin-top: 20px;">
            This is an automated email. Please do not reply.
        </p>
    </div>
</body>
</html>
