@component('mail::message')
# Confirmed tour reservation

The reserver confirmed the reservation by email{{ !empty($reservation['tour']) ? ' for ' . $reservation['tour'] : '' }}. Please contact them to agree on the details.

## Reservation #{{ $reservation['id'] ?? '' }}

@include('emails.tour.partials.reservation_table', ['reservation' => $reservation, 'show_phone' => true])

@if(!empty($reservation['email']) || !empty($reservation['phone']))
**Contact the reserver:**
@if(!empty($reservation['email'])) [{{ $reservation['email'] }}](mailto:{{ $reservation['email'] }}) @endif
@if(!empty($reservation['phone'])) · [{{ $reservation['phone'] }}](tel:{{ preg_replace('/[^0-9+]/', '', $reservation['phone']) }}) @endif
@endif

@if(!empty($reservation['guides']))
**Guide(s) of this tour:** @foreach($reservation['guides'] as $i => $guide){{ $i ? ', ' : '' }}{{ $guide['name'] }}@endforeach
@endif

@if(!empty($action_url))
@component('mail::button', ['url' => $action_url])
Open reservations
@endcomponent
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
