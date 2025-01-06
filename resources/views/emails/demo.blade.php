@component('mail::message')
# Order Summary

Thank you for your order! Below are the details of your purchase:

@foreach ($items as $item)
- **{{ $item->name }}**: ${{ number_format($item->amount_cents, 2) }}
@endforeach

@if ($totalAmount)
<br><br>
## Total Amount: {{ number_format($totalAmount, 2) }}
@endif

@component('mail::button', ['url' => 'https://web.wegostores.com/dashboard_user'])
View Order Details
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent