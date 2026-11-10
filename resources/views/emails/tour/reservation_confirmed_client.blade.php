@component('mail::message')
# You're all set{{ !empty($reservation['name']) ? ', ' . $reservation['name'] : '' }}! 🎉

Your reservation is **confirmed** — thank you! Your guide already has it and will contact you soon to talk through the plan.

@include('emails.tour.partials.reservation_table', ['reservation' => $reservation, 'show_phone' => true])

## What happens next

1. **Your guide contacts you** — usually within a day or two — to agree on the meeting point, the plan and the payment.
2. **Get ready** — the guide will tell you what to bring; ask anything you like.
3. **Enjoy the mountains!** 🏔

@php($your_guides = !empty($reservation['guide']) ? [$reservation['guide']] : ($reservation['guides'] ?? []))
@if(!empty($your_guides))
## Your guide

@foreach($your_guides as $guide)
**{{ $guide['name'] }}**@if(!empty($guide['phone'])) · 📞 {{ $guide['phone'] }}@endif @if(!empty($guide['email'])) · ✉️ {{ $guide['email'] }}@endif<br>
@endforeach
@endif

@if(!empty($reservation['tour_info']))
<br>

## About the tour

**{{ $reservation['tour_info']['title'] }}**

@if(mb_strlen($reservation['tour_info']['short_description'] ?? '') > 3)
{{ $reservation['tour_info']['short_description'] }}
@endif

@if(!empty($reservation['tour_info']['location']))
📍 {{ $reservation['tour_info']['location'] }}<br>
@endif
@if(!empty($reservation['tour_info']['duration']))
⏱ {{ $reservation['tour_info']['duration'] }}
@endif

@component('mail::button', ['url' => $reservation['tour_info']['url']])
See the tour page
@endcomponent
@endif

<p style="font-size:13px;color:#87939b;">@if(empty($reservation['price']))The price for your group is set by the guide — they'll tell you when they get in touch.@else The price comes from the tour's price list for your dates and group size — you pay the guide, nothing is charged online.@endif</p>

Need to change something or have a question? Just **reply to this email** — we're happy to help.

Have a great trip! 🧗<br>
The {{ config('app.name') }} team
@endcomponent
