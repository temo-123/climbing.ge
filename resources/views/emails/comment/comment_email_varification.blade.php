@component('mail::message')
# One click to publish your comment ✍️

Thanks for joining the conversation on climbing.ge!

To keep spam away, we just need you to **confirm your email**. Your comment becomes visible to everyone as soon as you do.

@component('mail::button', ['url' => $action_url, 'color' => 'success'])
Confirm my email
@endcomponent

<p style="font-size:13px;color:#87939b;">Didn't write a comment? No worries — just ignore this email and nothing will be published.</p>

Happy climbing! 🧗<br>
The {{ config('app.name') }} team
@endcomponent
