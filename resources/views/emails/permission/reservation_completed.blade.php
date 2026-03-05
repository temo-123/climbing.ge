@component('mail::message')
{{-- # Your coment is delited! --}}

<p>{{ $message }}</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
