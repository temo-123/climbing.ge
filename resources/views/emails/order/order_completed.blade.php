@component('mail::message')
# Your order is complete! 🎉

Hi{{ $name ? ' ' . $name : '' }}! Your order **#{{ $order_id }}** is done. Thanks for gearing up with us.

@if(count($products))
@component('mail::panel')
@foreach($products as $p)
**{{ $p['title'] }}**@if(!empty($p['options'])) — {{ implode(', ', $p['options']) }}@endif<br>
@endforeach
@endcomponent
@endif

How did it go? Tell us about the delivery and rate the gear you got. It takes a minute, and your product reviews help other climbers choose.

@component('mail::button', ['url' => $review_url, 'color' => 'success'])
Rate my order
@endcomponent

<p style="font-size:13px;color:#87939b;">The delivery review is only seen by our team. Product reviews appear on the product pages.</p>

Happy climbing! 🧗<br>
The {{ config('app.name') }} team
@endcomponent
