@component('mail::message')
# New message from {{ $from_site }} ✉️

{{ $name }} {{ $surname }} sent a message through the contact form:

@component('mail::panel')
{!! nl2br(e($msg)) !!}
@endcomponent

**Name:** {{ $name }} {{ $surname }}<br>
**Email:** [{{ $email }}](mailto:{{ $email }})<br>
@if(!empty($num))**Phone:** {{ $num }}<br>@endif
@if(!empty($country))**Country:** {{ $country }}@endif

@component('mail::button', ['url' => 'mailto:' . $email])
Reply to {{ $name }}
@endcomponent

The {{ config('app.name') }} team
@endcomponent
