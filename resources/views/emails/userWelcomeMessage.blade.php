@component('mail::message')
# Welcome on climbing.ge

<p>{{$text}}</p>

@component('mail::button', ['url' => $action_url])
Visit your profile
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
