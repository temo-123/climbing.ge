@component('mail::message')
# New reservation request 📬

{{ !empty($reservation['name']) ? $reservation['name'] : 'Someone' }} just reserved {{ !empty($reservation['tour']) ? $reservation['tour'] : 'a tour' }}.

@if(!empty($reservation['verified']))
<p style="display:inline-block;padding:6px 12px;border-radius:999px;background:#e6f4ee;color:#1f7a4d;font-weight:bold;">✅ Confirmed</p>
@else
<p style="display:inline-block;padding:6px 12px;border-radius:999px;background:#fff3cd;color:#8a6100;font-weight:bold;">⏳ Waiting for email confirmation</p>

We sent them a confirmation link. The guide gets the reservation once they confirm — you'll get another email then.
@endif

@include('emails.tour.partials.reservation_table', ['reservation' => $reservation, 'show_phone' => true, 'show_fee' => true])

@component('mail::button', ['url' => $action_url])
Open reservations
@endcomponent

Have a good day! ☀️<br>
The {{ config('app.name') }} team
@endcomponent
