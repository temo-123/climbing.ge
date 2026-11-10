@component('mail::message')
# Thanks for your order! 🛒

We've got it and we're excited to get your gear ready.

There's just **one quick step**: please confirm your order, so we know everything is right.

@component('mail::button', ['url' => $action_url, 'color' => 'success'])
Confirm my order
@endcomponent

As soon as you confirm, we'll start packing. You can follow the order any time in your account.

<p style="font-size:13px;color:#87939b;">Didn't place this order? Just ignore this email.</p>

Happy climbing! 🧗<br>
The {{ config('app.name') }} team
@endcomponent
