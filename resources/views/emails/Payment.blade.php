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

.info-table {
    width: 100%;
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #ddd;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: bold;
    color: #333;
}

.info-value {
    color: #555;
    text-align: right; /* Align values to the right for better readability */
}

</style>

<div class="receipt-container">
    @if ($data['invoice_image'])
        <img src="{{$data['invoice_image']}}" alt="Receipt Image" class="receipt-image" />
    @endif
    <div class="info-table">
        <div class="info-row">
            <div class="info-label">الاسم</div>
            <div class="info-value">{{$data['user']['name']}}</div>
        </div>
        <div class="info-row">
            <div class="info-label">الايميل</div>
            <div class="info-value">{{$data['user']['email']}}</div>
        </div> 
        <div class="info-row">
            <div class="info-label">المبلغ المدفوع</div>
            <div class="info-value">{{$data['amount']}}</div>
        </div>
        <div class="info-row">
            <div class="info-label">طريقة الدفع</div>
            <div class="info-value">{{$data['payment_method']['name']}}</div>
        </div>
        <div class="info-row">
            <div class="info-label">التاريخ</div>
            <div class="info-value">{{$data['created_at']}}</div>
        </div>
        <div class="info-row">
            <div class="info-label">المشتريات</div>
            <div class="info-value">
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
            </div>
        </div>
    </div>
</div>
