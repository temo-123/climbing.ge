@component('mail::message')
# Confirm your reservation

<p>We have received your tour reservation.</p>

<p>Please press the button below and confirm it on the page that opens. Until it is confirmed the reservation is not complete and the guide will not get it.</p>

@include('emails.tour.partials.reservation_table', ['reservation' => $reservation ?? null, 'show_phone' => false])

@component('mail::button', ['url' => $action_url])
Confirm reservation
@endcomponent

<p style="font-size:12px;color:#87939b;">If you did not make this reservation, just ignore this email.</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
