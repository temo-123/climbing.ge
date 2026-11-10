@component('mail::message')
# Someone replied to your comment 💬

Good news — there's a new answer to your comment on **“{{ $article_title }}”**.

Hop over to see what they said and keep the conversation going.

@component('mail::button', ['url' => $url])
Read the reply
@endcomponent

See you on the crag! 🧗<br>
The {{ config('app.name') }} team
@endcomponent
