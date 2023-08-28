@component('mail::message')
# Message from - {{ $from_site }}

<p>{{$msg}}</p>

@component('mail::button', ['url' => $unfollow_url])
Unfollow
@endcomponent

Thanks!<br>
{{ config('app.name') }}
@endcomponent
