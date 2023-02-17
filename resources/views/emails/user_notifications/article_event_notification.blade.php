@component('mail::message')
{{-- # Introduction --}}

{{ $data['text'] }}

@component('mail::button', ['url' => $data['url']])
Wisit this page
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
