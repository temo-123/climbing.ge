@component('mail::message')
# Thank you for order

<h1>We have received your order!</h1>

<p>The order was successfully placed, but to make sure that the order is correct, please {{-- check order --}} and clicking the button below!</p>

@component('mail::button', ['url' => $action_url])
Confirm Order
@endcomponent

{{-- @component('mail::button', ['url' => 'user.climbing.loc/orders'])
Confirm Order
@endcomponent --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
