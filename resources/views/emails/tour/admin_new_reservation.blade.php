@component('mail::message')
# New tour reservation

@if(!empty($reservation['verified']))
<p>A new tour reservation has arrived. It is <strong>confirmed</strong>.</p>
@else
<p>A new tour reservation has arrived. The reserver has <strong>not confirmed the email yet</strong> — a confirmation link was sent to them.</p>
@endif

@include('emails.tour.partials.reservation_table', ['reservation' => $reservation, 'show_phone' => true])

@component('mail::button', ['url' => $action_url])
Open reservations
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
