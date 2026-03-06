@component('mail::message')

<h1>{{$title}}</h1>

<p>{{$text}}</p>

@component('mail::button', ['url' => $action_url])
Task panel
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
