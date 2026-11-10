@component('mail::message')

<p>{{ $message }}</p>

@include('emails.tour.partials.reservation_table', ['reservation' => $reservation ?? null, 'show_phone' => true])

Thanks,<br>
{{ config('app.name') }}
@endcomponent
