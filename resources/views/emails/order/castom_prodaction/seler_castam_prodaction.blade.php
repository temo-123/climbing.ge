@component('mail::message')
# New order request 📦

Someone is interested in a product and is waiting for your answer — the sooner the better!

@component('mail::panel')
**Product:** {{ $product_name }} (#{{ $product_id }})<br>
@if($option_name)**Variant:** {{ $option_name }}<br>@endif
**Buyer:** {{ $messaged_user_name }}<br>
**Email:** [{{ $messaged_user_email }}](mailto:{{ $messaged_user_email }})<br>
@if(!empty($buyer_number))**Phone:** {{ $buyer_number }}@endif
@endcomponent

## Their message

{!! nl2br(e($buyer_message)) !!}

@component('mail::button', ['url' => 'mailto:' . $messaged_user_email, 'color' => 'success'])
Reply to the buyer
@endcomponent

The {{ config('app.name') }} team
@endcomponent
