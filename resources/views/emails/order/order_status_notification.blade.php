@component('mail::message')
# Your order has an update 📦

Hi! Just a quick note — the status of your order **#{{ $order_id }}** changed.

@component('mail::panel')
**New status:** {{ ucfirst(str_replace('_', ' ', (string) $status)) }}<br>
**Updated:** {{ $data_time }}
@endcomponent

You can see all the details and the full history in your orders.

@component('mail::button', ['url' => $action_url])
See my orders
@endcomponent

Questions about your order? Just reply to this email.

Thanks for shopping with us! 🏔<br>
The {{ config('app.name') }} team
@endcomponent
