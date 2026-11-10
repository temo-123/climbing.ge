@component('mail::message')
# New confirmed reservation 🎒

Good news{{ !empty($reservation['tour']) ? ' — someone is coming on ' . $reservation['tour'] : '' }}! The reservation was confirmed by email, so it's real. Please **get in touch with {{ !empty($reservation['name']) ? $reservation['name'] : 'the guest' }} soon** to agree on the details.

@if(!empty($reservation['email']) || !empty($reservation['phone']))
<p>
@if(!empty($reservation['phone']))<a href="tel:{{ preg_replace('/[^0-9+]/', '', $reservation['phone']) }}" style="display:inline-block;margin:0 6px 6px 0;padding:8px 14px;border-radius:999px;background:#18a06f;color:#ffffff;text-decoration:none;font-weight:bold;">📞 Call {{ $reservation['phone'] }}</a>@endif
@if(!empty($reservation['email']))<a href="mailto:{{ $reservation['email'] }}" style="display:inline-block;margin:0 6px 6px 0;padding:8px 14px;border-radius:999px;background:#24343f;color:#ffffff;text-decoration:none;font-weight:bold;">✉️ Email {{ $reservation['email'] }}</a>@endif
</p>
@endif

## Reservation #{{ $reservation['id'] ?? '' }}

@include('emails.tour.partials.reservation_table', ['reservation' => $reservation, 'show_phone' => true, 'show_fee' => true])

@if(empty($reservation['guide']) && !empty($reservation['guides']))
**Guide(s) of this tour:** @foreach($reservation['guides'] as $i => $guide){{ $i ? ', ' : '' }}{{ $guide['name'] }}@endforeach
@endif

@if(!empty($action_url))
@component('mail::button', ['url' => $action_url])
Open in the dashboard
@endcomponent
@endif

Thanks for taking care of our guests! 🙌<br>
The {{ config('app.name') }} team
@endcomponent
