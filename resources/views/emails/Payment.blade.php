<style>
    .receipt-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .receipt-image {
        width: 100%;
        height: auto;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th, td {
        padding: 10px;
        text-align: right;
        border-bottom: 1px solid #ddd;
    }

    th {
        font-weight: bold;
        color: #333;
        text-align: left;
    }

    td {
        color: #555;
    }

    tr:last-child td {
        border-bottom: none;
    }

    .table-header {
        background-color: #f1f1f1;
        text-align: center;
    }

</style>

<div class="receipt-container">
    @if ($data['invoice_image'])
        <img src="{{$data['invoice_image']}}" alt="Receipt Image" class="receipt-image" />
    @endif
    <table>
        <thead>
            <tr class="table-header">
                <th colspan="2">تفاصيل الفاتورة</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$data['user']['name']}}</td>
                <th>الاسم</th>
            </tr>
            <tr>
                <td>{{$data['user']['email']}}</td>
                <th>الايميل</th>
            </tr>
            <tr>
                <td>{{$data['amount']}}</td>
                <th>المبلغ المدفوع</th>
            </tr>
            <tr>
                <td>{{$data['payment_method']['name']}}</td>
                <th>طريقة الدفع</th>
            </tr>
            <tr>
                <td>{{$data['created_at']}}</td>
                <th>التاريخ</th>
            </tr>
            <tr>
                <td>
                    @if ($data['orders'])
                    @foreach ($data['orders'] as $order)
                        @if (!empty($order->domain))
                            Domain : {{$order->domain->name}}
                            <br />
                        @endif
                        @if (!empty($order->extra))
                            Extra : {{$order->extra->name}}
                            <br />
                        @endif
                        @if (!empty($order->plans))
                            Plan : {{$order->plans->name}}
                            <br />
                        @endif
                    @endforeach
                    @endif
                </td>
                <th>المشتريات</th>
            </tr>
        </tbody>
    </table>
</div>
