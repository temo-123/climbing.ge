@component('mail::message')
# Almost there{{ !empty($reservation['name']) ? ', ' . $reservation['name'] : '' }}! 👋

Thanks for choosing {{ !empty($reservation['tour']) ? $reservation['tour'] : 'a tour with us' }} — we're happy you want to go into the mountains with us.

There's just **one small step left**: please confirm your reservation, so we know it's really you. It takes one click.

@component('mail::button', ['url' => $action_url, 'color' => 'success'])
Yes, confirm my reservation
@endcomponent

Here's what you asked for:

@include('emails.tour.partials.reservation_table', ['reservation' => $reservation ?? null, 'show_phone' => false])

**What happens next?** As soon as you confirm, your guide gets the reservation and will get in touch to agree on the details. Nothing is paid now.

<p style="font-size:13px;color:#87939b;">Didn't make this reservation? No worries — just ignore this email and nothing will happen.</p>

See you in the mountains! 🏔<br>
The {{ config('app.name') }} team
@endcomponent
