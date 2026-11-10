@component('mail::message')
# Something new for you 🏔

Hi,

{{ $data['text'] }}

@component('mail::button', ['url' => $data['url']])
Take a look
@endcomponent

<p style="font-size:13px;color:#87939b;">You get these emails because you asked to be notified. You can change it any time in your account settings.</p>

See you in the mountains! 🧗<br>
The {{ config('app.name') }} team
@endcomponent
