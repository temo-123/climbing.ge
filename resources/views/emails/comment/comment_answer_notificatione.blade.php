@component('mail::message')
# Someone replied to your comment 💬

Good news — there's a new answer to your comment on **“{{ $article_title }}”**.

Hop over to see what they said and keep the conversation going.

@component('mail::button', ['url' => $url])
Read the reply
@endcomponent

@if(!empty($unsubscribe_url))
<p style="font-size:13px;color:#87939b;">Don't want these? <a href="{{ $manage_url }}" style="color:#87939b;text-decoration:underline;">Choose which emails you get</a> or <a href="{{ $unsubscribe_url }}" style="color:#87939b;text-decoration:underline;">unsubscribe from all</a>.</p>

@endif
See you on the crag! 🧗<br>
The {{ config('app.name') }} team
@endcomponent
