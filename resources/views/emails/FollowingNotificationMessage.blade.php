@component('mail::message')
# You're following {{ $from_site }}

Hi there,

{{ $msg }}

@if(!empty($unfollow_url))
We'll only email you when there's something worth sharing. If you ever change your mind, you can unsubscribe any time:

@component('mail::button', ['url' => $unfollow_url, 'color' => 'error'])
Unfollow {{ $from_site }}
@endcomponent
@endif

Thanks for being part of the community!<br>
{{ config('app.name') }}
@endcomponent
