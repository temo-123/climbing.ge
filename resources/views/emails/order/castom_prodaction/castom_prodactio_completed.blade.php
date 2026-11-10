@component('mail::message')
# Thanks for your request! 🙌

We got your message — thank you for thinking of us.

{{ $message }}

Usually we answer within a day. If you want to add something in the meantime, just reply to this email.

Talk soon! 🧗<br>
The {{ config('app.name') }} team
@endcomponent
