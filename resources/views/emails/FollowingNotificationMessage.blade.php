@component('mail::message')
# You're following {{ $from_site }} 🎉

Hi there,

{{ $msg }}

We'll only write when there's something worth sharing — new routes, gear, tours and stories from the mountains.

@if(!empty($unfollow_url))
<p style="font-size:13px;color:#87939b;">Changed your mind? You can stop these emails any time:</p>

@component('mail::button', ['url' => $unfollow_url, 'color' => 'dark'])
Unfollow {{ $from_site }}
@endcomponent
@endif

Thanks for being part of the community! 🏔<br>
The {{ config('app.name') }} team
@endcomponent
