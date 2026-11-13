@component('mail::message')
# Something new for you 🏔

Hi,

{{ $data['text'] }}

@component('mail::button', ['url' => $data['url']])
Take a look
@endcomponent

<p style="font-size:13px;color:#87939b;">You get these emails because you asked to be notified. Too many? <a href="{{ $manage_url }}" style="color:#87939b;text-decoration:underline;">Choose which alerts you get</a>@if(!empty($unsubscribe_url)) or <a href="{{ $unsubscribe_url }}" style="color:#87939b;text-decoration:underline;">unsubscribe from all</a>@endif.</p>

See you in the mountains! 🧗<br>
The {{ config('app.name') }} team
@endcomponent
