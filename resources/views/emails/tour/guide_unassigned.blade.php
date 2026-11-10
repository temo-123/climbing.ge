@component('mail::message')
# A reservation moved to another guide

Hi,

just so you know: **reservation #{{ $reservation['id'] ?? '' }}**{{ !empty($reservation['tour']) ? ' — ' . $reservation['tour'] : '' }}{{ !empty($reservation['check_in']) ? ' (' . \Illuminate\Support\Str::before(str_replace('T', ' ', $reservation['check_in']), ' ') . ')' : '' }} is now with **{{ $next['name'] ?? 'another guide' }}**{{ !empty($by) ? ', changed by ' . $by : '' }}.

You don't need to do anything for this guest anymore. If you were already in touch with them, a short hand-over message to {{ $next['name'] ?? 'the new guide' }} would be great{{ !empty($next['email']) ? ' (' . $next['email'] . ')' : '' }}.

Thanks! 🙌<br>
The {{ config('app.name') }} team
@endcomponent
