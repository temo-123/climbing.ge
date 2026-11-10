@component('mail::message')
@if(!empty($for_office))
# Reservation #{{ $reservation['id'] ?? '' }} has a new guide 🧭

**{{ $reservation['guide']['name'] ?? 'Another guide' }}** is now the guide in charge{{ !empty($reservation['tour']) ? ' of ' . $reservation['tour'] : '' }}.@if(!empty($previous['name'])) It was with {{ $previous['name'] }} before.@endif @if(!empty($by)) Changed by {{ $by }}.@endif

@else
# A reservation is now yours 🧭

Hi{{ !empty($reservation['guide']['name']) ? ' ' . $reservation['guide']['name'] : '' }},

you're now the guide in charge of **reservation #{{ $reservation['id'] ?? '' }}**{{ !empty($reservation['tour']) ? ' — ' . $reservation['tour'] : '' }}.@if(!empty($previous['name'])) It was with {{ $previous['name'] }} before.@endif
@endif

@if(!empty($for_office))
@elseif(!empty($reservation['verified']))
The guest has **confirmed** the reservation — please get in touch with {{ !empty($reservation['name']) ? $reservation['name'] : 'them' }} soon to agree on the details.
@else
The guest hasn't confirmed their email yet. You'll get another email as soon as they do — no need to contact them before that.
@endif

@if(!empty($reservation['commission']))
**About the fee:** {{ !empty($for_office) ? 'the new guide is' : "you're" }} not the general guide of this tour, so **{{ $reservation['commission']['percent'] }}%**{{ !empty($reservation['commission']['amount']) ? ' (' . $reservation['commission']['amount'] . ')' : '' }} of the total goes to climbing.ge. Please pay it after the tour — we'll mark it in the dashboard.
@endif

@if(!empty($note))
@component('mail::panel')
**Note{{ !empty($by) ? ' from ' . $by : '' }}:** {{ $note }}
@endcomponent
@endif

@if(!empty($reservation['verified']) && (!empty($reservation['email']) || !empty($reservation['phone'])))
<p>
@if(!empty($reservation['phone']))<a href="tel:{{ preg_replace('/[^0-9+]/', '', $reservation['phone']) }}" style="display:inline-block;margin:0 6px 6px 0;padding:8px 14px;border-radius:999px;background:#18a06f;color:#ffffff;text-decoration:none;font-weight:bold;">📞 Call {{ $reservation['phone'] }}</a>@endif
@if(!empty($reservation['email']))<a href="mailto:{{ $reservation['email'] }}" style="display:inline-block;margin:0 6px 6px 0;padding:8px 14px;border-radius:999px;background:#24343f;color:#ffffff;text-decoration:none;font-weight:bold;">✉️ Email {{ $reservation['email'] }}</a>@endif
</p>
@endif

@include('emails.tour.partials.reservation_table', ['reservation' => $reservation, 'show_phone' => true, 'show_fee' => true])

@component('mail::button', ['url' => $action_url])
Open in the dashboard
@endcomponent

Thanks for taking care of our guests! 🙌<br>
The {{ config('app.name') }} team
@endcomponent
