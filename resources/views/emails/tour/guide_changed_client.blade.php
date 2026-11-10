@component('mail::message')
# Meet your new guide 🧭

Hi{{ !empty($reservation['name']) ? ' ' . $reservation['name'] : '' }},

a small update about your reservation{{ !empty($reservation['tour']) ? ' for **' . $reservation['tour'] . '**' : '' }}: **{{ $guide['name'] }}** will be your guide. Everything else stays the same — your dates, group and price don't change.

@component('mail::panel')
**{{ $guide['name'] }}**<br>
@if(!empty($guide['phone']))📞 {{ $guide['phone'] }}<br>@endif
@if(!empty($guide['email']))✉️ [{{ $guide['email'] }}](mailto:{{ $guide['email'] }})@endif
@endcomponent

{{ $guide['name'] }} will get in touch with you soon. Questions in the meantime? Just reply to this email.

See you in the mountains! 🏔<br>
The {{ config('app.name') }} team
@endcomponent
